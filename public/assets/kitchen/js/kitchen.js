"use strict";

// CSRF token for AJAX
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// ===== Toast Notification System =====
function showToast(message, type) {
    type = type || 'success';
    var container = document.getElementById('k-toast-container');
    if (!container) return;

    var toast = document.createElement('div');
    toast.className = 'k-toast k-toast-' + type;

    var iconSvg = type === 'success'
        ? '<svg class="k-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
        : '<svg class="k-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';

    toast.innerHTML = iconSvg +
        '<span class="k-toast-msg">' + message + '</span>' +
        '<button class="k-toast-close">&times;</button>';

    container.appendChild(toast);

    toast.querySelector('.k-toast-close').addEventListener('click', function() {
        removeToast(toast);
    });

    setTimeout(function() {
        removeToast(toast);
    }, 3000);
}

function removeToast(toast) {
    if (!toast || toast.classList.contains('hiding')) return;
    toast.classList.add('hiding');
    setTimeout(function() {
        if (toast.parentNode) toast.parentNode.removeChild(toast);
    }, 300);
}

// ===== Update Order Status =====
function updateOrderStatus(orderId, newStatus) {
    fetch('/kitchen/orders/' + orderId + '/status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            showToast('Order #' + orderId + ' updated to ' + newStatus, 'success');
            // Update the badge and card in-place
            var card = document.querySelector('[data-order-card="' + orderId + '"]');
            if (card) {
                var badge = card.querySelector('.k-order-status-badge');
                if (badge) {
                    badge.className = 'k-badge k-badge-' + newStatus + ' k-order-status-badge';
                    badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                }
                // Hide card if completed/cancelled on dashboard active list
                if ((newStatus === 'completed' || newStatus === 'cancelled') && card.closest('[data-auto-refresh]')) {
                    card.style.display = 'none';
                }
                // Remove actions if completed/cancelled
                if (newStatus === 'completed' || newStatus === 'cancelled') {
                    var actions = card.querySelector('.k-order-actions');
                    if (actions) actions.remove();
                }
            }
            // Reload after a short delay to update counts
            setTimeout(function() { location.reload(); }, 1500);
        } else {
            showToast('Failed to update order status', 'error');
        }
    })
    .catch(function(err) {
        console.error('Error:', err);
        showToast('Something went wrong', 'error');
    });
}

// ===== Toggle Item Status =====
function toggleItemStatus(itemId, checkbox) {
    var newStatus = checkbox.checked ? 1 : 0;
    var statusText = checkbox.checked ? 'active' : 'inactive';

    fetch('/kitchen/items/' + itemId + '/status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            showToast('Item marked as ' + statusText, 'success');
        } else {
            checkbox.checked = !checkbox.checked;
            showToast('Failed to update item', 'error');
        }
    })
    .catch(function(err) {
        console.error('Error:', err);
        checkbox.checked = !checkbox.checked;
        showToast('Something went wrong', 'error');
    });
}

// Order status change from select dropdown
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('k-status-select')) {
        var orderId = e.target.dataset.orderId;
        var newStatus = e.target.value;
        updateOrderStatus(orderId, newStatus);
    }
});

// Item toggle handler
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('k-item-toggle')) {
        var itemId = e.target.dataset.itemId;
        toggleItemStatus(itemId, e.target);
    }
});

// Quick status buttons
document.addEventListener('click', function(e) {
    var btn = e.target.closest('[data-status-action]');
    if (btn) {
        var orderId = btn.dataset.orderId;
        var newStatus = btn.dataset.statusAction;
        updateOrderStatus(orderId, newStatus);
    }
});

// Load hourly chart on dashboard
function loadChart() {
    var chartContainer = document.getElementById('k-hourly-chart');
    if (!chartContainer) return;

    fetch('/kitchen/dashboard/stats', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        var bars = chartContainer.querySelector('.k-chart-bars');
        var labels = chartContainer.querySelector('.k-chart-labels');
        if (!bars || !labels) return;

        var hourly = data.hourly || [];
        var maxCount = Math.max.apply(null, hourly.map(function(h) { return h.count; }).concat([1]));

        bars.innerHTML = '';
        labels.innerHTML = '';

        hourly.forEach(function(h, i) {
            var pct = (h.count / maxCount) * 100;
            var bar = document.createElement('div');
            bar.className = 'k-chart-bar';
            bar.style.height = Math.max(pct, 2) + '%';
            bar.title = h.hour + ': ' + h.count + ' orders';
            bars.appendChild(bar);

            var label = document.createElement('div');
            label.className = 'k-chart-label';
            label.textContent = (i % 3 === 0) ? h.hour.replace(':00','') : '';
            labels.appendChild(label);
        });
    })
    .catch(function(err) { console.error('Chart error:', err); });
}

// Auto-refresh dashboard every 30 seconds
if (document.getElementById('k-hourly-chart')) {
    loadChart();
    setInterval(function() {
        loadChart();
    }, 30000);
}

// ===== Order Detail Modal =====
function buildOrderModalHtml(order) {
    var html = '';

    if (order.table_no) {
        html += '<div class="k-modal-row">';
        html += '<span class="k-modal-row-label">Table-No</span>';
        html += '<span class="k-modal-row-value">' + order.table_no + '</span>';
        html += '</div>';
    }

    // Status & Type
    html += '<div class="k-modal-row">';
    html += '<span class="k-modal-row-label">Status</span>';
    html += '<span><span class="k-badge k-badge-' + order.status + '">' + order.status.charAt(0).toUpperCase() + order.status.slice(1) + '</span></span>';
    html += '</div>';

    if (order.order_type) {
        html += '<div class="k-modal-row">';
        html += '<span class="k-modal-row-label">Order Type</span>';
        html += '<span class="k-modal-row-value">' + order.order_type.charAt(0).toUpperCase() + order.order_type.slice(1) + '</span>';
        html += '</div>';
    }

    html += '<div class="k-modal-row">';
    html += '<span class="k-modal-row-label">Time</span>';
    html += '<span class="k-modal-row-value">' + order.created_at + ' (' + order.time_ago + ')</span>';
    html += '</div>';

    // Customer
    if (order.customer_name || order.customer_phone) {
        html += '<hr class="k-modal-divider">';
        if (order.customer_name) {
            html += '<div class="k-modal-row">';
            html += '<span class="k-modal-row-label">Customer</span>';
            html += '<span class="k-modal-row-value">' + order.customer_name + '</span>';
            html += '</div>';
        }
        if (order.customer_phone) {
            html += '<div class="k-modal-row">';
            html += '<span class="k-modal-row-label">Phone</span>';
            html += '<span class="k-modal-row-value">' + order.customer_phone + '</span>';
            html += '</div>';
        }
    }

    if (order.delivery_address) {
        html += '<div class="k-modal-row">';
        html += '<span class="k-modal-row-label">Address</span>';
        html += '<span class="k-modal-row-value">' + order.delivery_address + '</span>';
        html += '</div>';
    }

    // Items
    html += '<hr class="k-modal-divider">';
    html += '<div class="k-modal-items-title">Items</div>';
    for (var i = 0; i < order.items.length; i++) {
        var item = order.items[i];
        html += '<div class="k-modal-item">';
        html += '<span><span class="k-modal-item-qty">' + item.quantity + 'x</span> ' + item.name;
        if (item.size) html += ' <span class="k-modal-item-size">(' + item.size + ')</span>';
        html += '</span>';
        html += '<span>' + (item.price * item.quantity).toFixed(2) + '</span>';
        html += '</div>';
    }

    // Totals
    html += '<hr class="k-modal-divider">';
    if (order.subtotal > 0) {
        html += '<div class="k-modal-row">';
        html += '<span class="k-modal-row-label">Subtotal</span>';
        html += '<span class="k-modal-row-value">' + order.subtotal.toFixed(2) + '</span>';
        html += '</div>';
    }
    if (order.discount > 0) {
        html += '<div class="k-modal-row">';
        html += '<span class="k-modal-row-label">Discount</span>';
        html += '<span class="k-modal-row-value" style="color:var(--k-success)">-' + order.discount.toFixed(2) + '</span>';
        html += '</div>';
    }
    if (order.delivery_fee > 0) {
        html += '<div class="k-modal-row">';
        html += '<span class="k-modal-row-label">Delivery Fee</span>';
        html += '<span class="k-modal-row-value">' + order.delivery_fee.toFixed(2) + '</span>';
        html += '</div>';
    }
    html += '<div class="k-modal-total-row">';
    html += '<span>Total</span>';
    html += '<span>' + order.total.toFixed(2) + '</span>';
    html += '</div>';

    if (order.instructions) {
        html += '<div class="k-modal-note">Note: ' + order.instructions + '</div>';
    }

    return html;
}

function showOrderDetailModal(order) {
    var modal = document.getElementById('k-order-modal');
    var title = document.getElementById('k-modal-title');
    var body = document.getElementById('k-modal-body');
    if (!modal || !body) return;

    title.textContent = 'Order -' + order.order_id;
    body.innerHTML = buildOrderModalHtml(order);
    modal.style.display = 'flex';
}

function openOrderModal(orderId) {
    var dataEl = document.getElementById('order-data-' + orderId);
    if (!dataEl) return;

    var order;
    try { order = JSON.parse(dataEl.textContent); } catch(e) { return; }
    showOrderDetailModal(order);
}

function closeOrderModal() {
    var modal = document.getElementById('k-order-modal');
    if (modal) modal.style.display = 'none';
    if (needsReload) {
        location.reload();
    }
}

// View button click
document.addEventListener('click', function(e) {
    var btn = e.target.closest('[data-view-order]');
    if (btn) {
        openOrderModal(btn.dataset.viewOrder);
        return;
    }
});

// Close modal
document.addEventListener('click', function(e) {
    if (e.target.id === 'k-modal-close' || e.target.id === 'k-order-modal') {
        closeOrderModal();
    }
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeOrderModal();
        dismissNewOrderAlert();
    }
});


// ===== New Order Notification System =====
var lastKnownOrderId = (typeof kitchenConfig !== 'undefined') ? kitchenConfig.lastKnownOrderId : 0;
var checkNewOrdersUrl = (typeof kitchenConfig !== 'undefined') ? kitchenConfig.checkNewOrdersUrl : '';
var pendingNewOrders = [];
var notificationAudio = document.getElementById('k-notification-sound');
var isAlertShowing = false;
var audioUnlocked = false;
var soundLoopInterval = null;

// Unlock audio on first user interaction (browser autoplay policy)
function unlockAudio() {
    if (audioUnlocked || !notificationAudio) return;
    // Play a silent/short burst then immediately pause to unlock
    notificationAudio.volume = 0;
    notificationAudio.play().then(function() {
        notificationAudio.pause();
        notificationAudio.currentTime = 0;
        notificationAudio.volume = 1;
        audioUnlocked = true;
    }).catch(function() {});
}

['click', 'touchstart', 'keydown'].forEach(function(evt) {
    document.addEventListener(evt, unlockAudio, { once: false });
});

function playNotificationSound() {
    if (!notificationAudio) return;
    // Play immediately
    notificationAudio.currentTime = 0;
    notificationAudio.volume = 1;
    notificationAudio.play().catch(function() {});

    // Loop: replay every time it ends while alert is open
    stopSoundLoop();
    notificationAudio.onended = function() {
        if (isAlertShowing) {
            notificationAudio.currentTime = 0;
            notificationAudio.play().catch(function() {});
        }
    };
}

function stopSoundLoop() {
    if (notificationAudio) {
        notificationAudio.onended = null;
    }
}

function stopNotificationSound() {
    if (!notificationAudio) return;
    stopSoundLoop();
    notificationAudio.pause();
    notificationAudio.currentTime = 0;
}

function showNewOrderAlert(order) {
    isAlertShowing = true;
    var alert = document.getElementById('k-new-order-alert');
    var alertText = document.getElementById('k-alert-text');
    if (!alert) return;

    var itemCount = order.items ? order.items.length : 0;
    var typeText = order.order_type ? ' (' + order.order_type + ')' : '';
    alertText.innerHTML = 'Order -<strong>' + order.order_id + '</strong>' + typeText +
        '<br>' + itemCount + ' item' + (itemCount !== 1 ? 's' : '') +
        ' &middot; Total: <strong>' + order.total.toFixed(2) + '</strong>';

    alert.style.display = 'flex';
    playNotificationSound();

    // Store current order for "View Order" button
    alert._currentOrder = order;
}

var needsReload = false;

function dismissNewOrderAlert(skipReload) {
    var alert = document.getElementById('k-new-order-alert');
    if (alert) alert.style.display = 'none';
    stopNotificationSound();
    isAlertShowing = false;
    needsReload = true;

    // Show next pending order if any
    if (pendingNewOrders.length > 0) {
        var nextOrder = pendingNewOrders.shift();
        setTimeout(function() {
            showNewOrderAlert(nextOrder);
        }, 300);
    } else if (!skipReload) {
        location.reload();
    }
}

// View Order button in alert
document.addEventListener('click', function(e) {
    if (e.target.id === 'k-alert-view-btn' || e.target.closest('#k-alert-view-btn')) {
        var alert = document.getElementById('k-new-order-alert');
        var order = alert ? alert._currentOrder : null;
        dismissNewOrderAlert(true);
        if (order) {
            showOrderDetailModal(order);
        }
    }
});

// Dismiss button in alert
document.addEventListener('click', function(e) {
    if (e.target.id === 'k-alert-dismiss-btn' || e.target.closest('#k-alert-dismiss-btn')) {
        dismissNewOrderAlert();
    }
});

// Poll for new orders every 10 seconds
function checkForNewOrders() {
    if (!checkNewOrdersUrl) return;

    fetch(checkNewOrdersUrl + '?last_id=' + lastKnownOrderId, {
        headers: { 'Accept': 'application/json' }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        var newOrders = data.new_orders || [];
        if (newOrders.length > 0) {
            // Update last known ID
            for (var i = 0; i < newOrders.length; i++) {
                if (newOrders[i].id > lastKnownOrderId) {
                    lastKnownOrderId = newOrders[i].id;
                }
            }

            // Update nav badge
            var navBadge = document.querySelector('.k-nav-badge');
            if (navBadge && data.active_count) {
                navBadge.textContent = data.active_count;
            }

            // Queue alerts
            if (isAlertShowing) {
                for (var j = 0; j < newOrders.length; j++) {
                    pendingNewOrders.push(newOrders[j]);
                }
            } else {
                // Show first one immediately, queue rest
                showNewOrderAlert(newOrders[0]);
                for (var k = 1; k < newOrders.length; k++) {
                    pendingNewOrders.push(newOrders[k]);
                }
            }
        }
    })
    .catch(function(err) { console.error('Poll error:', err); });
}

// Start polling
if (checkNewOrdersUrl) {
    setInterval(checkForNewOrders, 10000);
}

// ===== Filter Panel Toggle =====
document.addEventListener('click', function(e) {
    var btn = e.target.closest('#k-filter-toggle');
    if (btn) {
        var panel = document.getElementById('k-filter-panel');
        if (panel) {
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }
    }
});

// ===== Logout Confirmation =====
document.addEventListener('click', function(e) {
    if (e.target.id === 'k-logout-btn' || e.target.closest('#k-logout-btn')) {
        var modal = document.getElementById('k-logout-modal');
        if (modal) modal.style.display = 'flex';
    }
    if (e.target.id === 'k-logout-cancel' || e.target.closest('#k-logout-cancel')) {
        var modal = document.getElementById('k-logout-modal');
        if (modal) modal.style.display = 'none';
    }
    if (e.target.id === 'k-logout-modal') {
        e.target.style.display = 'none';
    }
});
