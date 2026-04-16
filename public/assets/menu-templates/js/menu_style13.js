/* Menu Style 14 - Self-contained JS */

document.addEventListener('DOMContentLoaded', function () {

    // ===== MENU DATA (injected from blade via window.s14MenuItems) =====
    const menuItems = window.s14MenuItems || [];

    // ===== AJAX HELPER =====
    var storeId = window.s14StoreId || 0;
    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function cartAjax(url, data, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open(data ? 'POST' : 'GET', url + (data ? '' : '?store_id=' + storeId), true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                cartData = res.cart || [];
                if (callback) callback(res);
            }
        };
        xhr.send(data ? JSON.stringify(data) : null);
    }

    // ===== DEVICE ID (persists across refreshes) =====
    function getDeviceId() {
        var id = localStorage.getItem('s14_device_id');
        if (!id) {
            id = 'dev-' + Date.now().toString(36) + '-' + Math.random().toString(36).substr(2, 8);
            localStorage.setItem('s14_device_id', id);
        }
        return id;
    }
    var deviceId = getDeviceId();

    // ===== SAVED PHONE (acts as simple login) =====
    function getSavedPhone() {
        return localStorage.getItem('s14_phone_' + storeId) || '';
    }
    function savePhone(phone) {
        if (phone) localStorage.setItem('s14_phone_' + storeId, phone);
    }

    // ===== CART STATE (synced with server session) =====
    var cartData = [];
    var placedOrders = [];

    function getCartCount() {
        return cartData.reduce(function (sum, item) { return sum + item.qty; }, 0);
    }

    function getSubtotal() {
        return cartData.reduce(function (sum, item) { return sum + item.price * item.qty; }, 0);
    }

    function getMrpTotal() {
        return cartData.reduce(function (sum, item) { return sum + (item.mrp || item.price) * item.qty; }, 0);
    }

    var storeDeliveryFee = window.storeDeliveryCharge || 0;
    var storeOrderType = window.storeOrderType || 0;
    var activeOrderType = 'dine-in';

    function updateCartBadge() {
        const badge = document.querySelector('.s14-cart-badge');
        const count = getCartCount();
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    function addToCart(index, qty, size, variationPrice, mrpPrice, callback) {
        // Support old calls: addToCart(index, qty, size, price, callback)
        if (typeof mrpPrice === 'function') { callback = mrpPrice; mrpPrice = null; }
        qty = qty || 1;
        size = size || 'default';
        var item = menuItems[index];
        var price = (typeof variationPrice === 'number') ? variationPrice : item.price;
        var mrp = (typeof mrpPrice === 'number') ? mrpPrice : (item ? (item.mrp || item.price) : price);
        var cartKey = index + '-' + size;

        cartAjax('/menu-cart/add', {
            store_id: storeId,
            cart_key: cartKey,
            index: index,
            item_id: item ? (item.id || 0) : 0,
            name: item ? item.name : 'Special Item',
            price: price,
            mrp: mrp,
            img: item ? item.img : '',
            is_veg: item ? item.isVeg : false,
            size: size,
            qty: qty
        }, function (res) {
            updateCartBadge();
            if (callback) callback(res);
        });
    }

    function addToCartDirect(data, callback) {
        cartAjax('/menu-cart/add', {
            store_id: storeId,
            cart_key: data.cartKey,
            index: data.index,
            item_id: data.itemId || 0,
            name: data.name,
            price: data.price,
            mrp: data.mrp || data.price,
            img: data.img || '',
            is_veg: data.isVeg || false,
            size: data.size || 'full',
            qty: data.qty || 1
        }, function (res) {
            updateCartBadge();
            if (callback) callback(res);
        });
    }

    function removeFromCart(cartKey, callback) {
        cartAjax('/menu-cart/remove', {
            store_id: storeId,
            cart_key: cartKey
        }, function (res) {
            updateCartBadge();
            if (callback) callback(res);
        });
    }

    function updateCartQty(cartKey, delta, callback) {
        cartAjax('/menu-cart/update-qty', {
            store_id: storeId,
            cart_key: cartKey,
            delta: delta
        }, function (res) {
            updateCartBadge();
            if (callback) callback(res);
        });
    }

    function clearCart(callback) {
        cartAjax('/menu-cart/clear', {
            store_id: storeId
        }, function (res) {
            updateCartBadge();
            if (callback) callback(res);
        });
    }

    // Load cart from session on page load
    cartAjax('/menu-cart/get', null, function () {
        updateCartBadge();
    });

    // ===== TAB NAVIGATION =====
    const navItems = document.querySelectorAll('.s14-nav-item');
    const tabContents = document.querySelectorAll('.s14-tab-content');
    const navPill = document.querySelector('.s14-nav-pill-bg');

    function moveNavPill(activeBtn) {
        if (!navPill || !activeBtn) return;
        const nav = activeBtn.closest('.s14-bottom-nav');
        const navRect = nav.getBoundingClientRect();
        const btnRect = activeBtn.getBoundingClientRect();
        navPill.style.width = btnRect.width + 'px';
        navPill.style.left = (btnRect.left - navRect.left) + 'px';
        navPill.style.top = ((navRect.height - 38) / 2) + 'px';
        navPill.classList.add('visible');
    }

    function switchTab(tabName) {
        navItems.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tabName);
            if (btn.dataset.tab === tabName) moveNavPill(btn);
        });
        tabContents.forEach(tc => {
            tc.classList.toggle('active', tc.id === 's14-tab-' + tabName);
        });
        if (tabName === 'cart') {
            cartAjax('/menu-cart/get', null, function () {
                renderCart();
            });
        }
        if (tabName === 'order') renderOrders();
        if (tabName === 'reorder') loadReorderItems();
        if (tabName === 'home') loadOrderAgainItems();
    }

    navItems.forEach(btn => {
        btn.addEventListener('click', () => switchTab(btn.dataset.tab));
    });

    // Initialize pill position on active tab
    setTimeout(() => {
        const activeNav = document.querySelector('.s14-nav-item.active');
        if (activeNav) moveNavPill(activeNav);
    }, 100);

    // ===== RENDER CART =====
    function renderCart() {
        const container = document.querySelector('.s14-cart-items-container');
        const summarySection = document.querySelector('.s14-cart-summary-section');

        if (cartData.length === 0) {
            container.innerHTML = `
                <div class="s14-cart-empty">
                    <i class="bi bi-bag-x"></i>
                    <h3>Your cart is empty</h3>
                    <p>Add some delicious items from the menu</p>
                    <button class="s14-browse-btn" onclick="document.querySelector('.s14-nav-item[data-tab=home]').click()">
                        <i class="bi bi-arrow-left"></i> Browse Menu
                    </button>
                </div>`;
            summarySection.style.display = 'none';
            return;
        }

        let html = '';
        cartData.forEach(item => {
            const imgHtml = item.img ? `<img class="s14-cart-item-img" src="${item.img}" alt="${item.name}">` : '';
            const unitMrp = item.mrp || item.price;
            const hasDiscount = unitMrp > item.price;
            const discountPercent = hasDiscount ? Math.round((unitMrp - item.price) / unitMrp * 100) : 0;
            const mrpHtml = hasDiscount ? `<span class="s14-cart-item-discount">${discountPercent}%</span><s class="s14-cart-item-mrp">\u20B9${unitMrp * item.qty}</s> ` : '';
            const discountHtml = '';
            html += `
                <div class="s14-cart-item">
                    ${imgHtml}
                    <div class="s14-cart-item-info">
                        <div class="s14-cart-item-title-row">
                            <span class="s14-cart-item-name">${item.name}</span>
                            <span class="s14-cart-item-size">${item.size}</span>
                        </div>
                        <div class="s14-cart-item-price-row">
                            <span class="s14-cart-item-price">${mrpHtml}\u20B9${item.price * item.qty}${discountHtml}</span>
                            <div class="s14-cart-item-actions">
                                <button class="s14-qty-btn ${item.qty === 1 ? 'delete-btn' : ''}" data-cartkey="${item.cartKey}" data-action="minus">
                                    <i class="bi ${item.qty === 1 ? 'bi-trash' : 'bi-dash'}"></i>
                                </button>
                                <span class="s14-cart-item-qty">${item.qty}</span>
                                <button class="s14-qty-btn" data-cartkey="${item.cartKey}" data-action="plus">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
        });
        container.innerHTML = html;

        // Qty button handlers (AJAX-based)
        container.querySelectorAll('.s14-qty-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const cartKey = btn.dataset.cartkey;
                const action = btn.dataset.action;
                updateCartQty(cartKey, action === 'plus' ? 1 : -1, function () {
                    renderCart();
                });
            });
        });

        // Summary
        const subtotal = getSubtotal();
        const mrpTotal = getMrpTotal();
        const discountAmount = mrpTotal - subtotal;

        // Only include delivery fee when delivery is active (or delivery-only store)
        var showDeliveryFee = storeDeliveryFee > 0 &&
            (storeOrderType == 2 || (storeOrderType == 3 && activeOrderType === 'delivery'));
        var effectiveDeliveryFee = showDeliveryFee ? storeDeliveryFee : 0;
        const total = subtotal + effectiveDeliveryFee;

        document.querySelector('.s14-subtotal').textContent = '\u20B9' + subtotal;
        document.querySelector('.s14-total').textContent = '\u20B9' + total;

        // Show/hide discount row
        var discountRow = document.querySelector('.s14-discount-row');
        if (discountRow) {
            if (discountAmount > 0) {
                discountRow.style.display = '';
                discountRow.querySelector('.s14-discount-amount').textContent = '-\u20B9' + discountAmount;
            } else {
                discountRow.style.display = 'none';
            }
        }

        // Show/hide delivery fee row
        var deliveryRow = document.querySelector('.s14-delivery-row');
        if (deliveryRow) {
            deliveryRow.style.display = showDeliveryFee ? '' : 'none';
        }

        summarySection.style.display = 'block';
    }

    // ===== RENDER ORDERS =====
    function loadOrders(callback) {
        var phone = getSavedPhone();
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/menu-cart/orders?store_id=' + storeId + '&device_id=' + deviceId + (phone ? '&phone=' + encodeURIComponent(phone) : ''), true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                placedOrders = res.orders || [];
            }
            if (callback) callback();
        };
        xhr.onerror = function () {
            if (callback) callback();
        };
        xhr.send();
    }

    function renderOrders() {
        var container = document.querySelector('.s14-orders-list');
        container.innerHTML = '<div class="s14-orders-empty"><i class="bi bi-arrow-repeat s14-spin"></i><p>Loading orders...</p></div>';

        loadOrders(function () {
            if (placedOrders.length === 0) {
                container.innerHTML =
                    '<div class="s14-orders-empty">' +
                        '<i class="bi bi-receipt-cutoff"></i>' +
                        '<h3>No orders yet</h3>' +
                        '<p>Your order history will appear here</p>' +
                    '</div>';
                return;
            }

            var html = '';
            placedOrders.forEach(function (order) {
                var statusClass = 'status-' + order.status;
                var statusLabel = order.status.charAt(0).toUpperCase() + order.status.slice(1);

                var itemsHtml = '';
                var itemCount = 0;
                order.items.forEach(function (item) {
                    var sizeTag = item.size && item.size !== 'default'
                        ? ' <span class="s14-order-item-size">' + item.size + '</span>'
                        : '';
                    itemsHtml +=
                        '<div class="s14-order-item-row">' +
                            '<span>' + item.name + sizeTag + ' x' + item.qty + '</span>' +
                            '<span>\u20B9' + (item.price * item.qty) + '</span>' +
                        '</div>';
                    itemCount += item.qty;
                });

                var orderSummaryHtml = '';
                orderSummaryHtml += '<div class="s14-order-summary-row"><span>Subtotal</span><span>\u20B9' + order.subtotal + '</span></div>';
                if (order.discount > 0) {
                    orderSummaryHtml += '<div class="s14-order-summary-row s14-order-summary-discount"><span>Discount</span><span>-\u20B9' + order.discount + '</span></div>';
                }
                if (order.delivery_fee > 0) {
                    orderSummaryHtml += '<div class="s14-order-summary-row"><span>Delivery Fee</span><span>\u20B9' + order.delivery_fee + '</span></div>';
                }

                html +=
                    '<div class="s14-order-card">' +
                        '<div class="s14-order-header">' +
                            '<span class="s14-order-id">' + order.order_id + '</span>' +
                            '<span class="s14-order-status ' + statusClass + '">' + statusLabel + '</span>' +
                        '</div>' +
                        '<div class="s14-order-date-row">' +
                            '<i class="bi bi-clock"></i> ' + order.date +
                        '</div>' +
                        '<div class="s14-order-items-list">' + itemsHtml + '</div>' +
                        '<div class="s14-order-summary">' + orderSummaryHtml + '</div>' +
                        '<div class="s14-order-footer">' +
                            '<span class="s14-order-type ' + order.order_type + '">' +
                                (order.order_type === 'dine-in' ? '<i class="bi bi-shop"></i> Dine-In' : '<i class="bi bi-truck"></i> Delivery') +
                            '</span>' +
                            '<div class="s14-order-total-block">' +
                                '<span class="s14-order-total-label">' + itemCount + ' item' + (itemCount !== 1 ? 's' : '') + '</span>' +
                                '<span class="s14-order-total">\u20B9' + order.total + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
            });
            container.innerHTML = html;
        });
    }

    // ===== ORDER AGAIN SECTION =====
    function loadOrderAgainItems() {
        var section = document.getElementById('s14-order-again');
        if (!section) return;
        var phone = getSavedPhone();
        var url = '/menu-cart/ordered-items?store_id=' + storeId + '&device_id=' + deviceId + (phone ? '&phone=' + encodeURIComponent(phone) : '');
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                var items = res.items || [];
                if (items.length === 0) {
                    section.style.display = 'none';
                    return;
                }
                renderOrderAgainItems(items);
                section.style.display = '';
                var viewAllBtn = document.getElementById('s14-oa-viewall');
                if (viewAllBtn) viewAllBtn.style.display = '';
            }
        };
        xhr.send();
    }

    function buildOaCardHtml(item) {
        var hasDiscount = item.price < item.mrp;
        var discountTag = '';
        if (hasDiscount) {
            discountTag = item.discount_type === 'percent'
                ? '<span class="s14-card-discount">' + item.discount + '% OFF</span>'
                : '<span class="s14-card-discount">\u20B9' + item.discount + ' OFF</span>';
        }
        return '<div class="s14-menu-card s14-oa-card" data-item-id="' + item.id + '">' +
                '<div class="s14-card-img">' +
                    '<img src="' + item.image + '" alt="' + item.name + '">' +
                    '<div class="s14-badge ' + (item.veg == 1 ? 'veg' : 'non-veg') + '"></div>' +
                    discountTag +
                '</div>' +
                '<div class="s14-card-body">' +
                    '<h3 class="s14-card-name">' + item.name + '</h3>' +
                    '<div class="s14-card-footer">' +
                        '<div class="s14-price-group">' +
                            '<span class="s14-card-price">\u20B9' + item.price + '</span>' +
                            (hasDiscount ? '<span class="s14-card-mrp"><s>\u20B9' + item.mrp + '</s></span>' : '') +
                        '</div>' +
                        '<button class="s14-add-btn s14-oa-add-btn" data-oa-id="' + item.id + '" data-oa-name="' + item.name.replace(/"/g, '&quot;') + '" data-oa-price="' + item.price + '" data-oa-mrp="' + item.mrp + '" data-oa-img="' + item.image + '" data-oa-veg="' + (item.veg == 1 ? 'true' : 'false') + '"><i class="bi bi-plus-lg"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function attachOaAddHandlers(container) {
        container.querySelectorAll('.s14-oa-add-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var itemId = parseInt(btn.dataset.oaId);
                var menuItem = menuItems.find(function (m) { return m.id === itemId; });
                if (menuItem && menuItem.foodVariations && menuItem.foodVariations.length > 0 &&
                    menuItem.foodVariations.some(function (v) { return v.values && v.values.length > 0; })) {
                    var idx = menuItems.indexOf(menuItem);
                    openSizePicker(idx, btn);
                    return;
                }
                addToCartDirect({
                    cartKey: 'item-' + itemId + '-default',
                    index: 0,
                    itemId: itemId,
                    name: btn.dataset.oaName,
                    price: parseFloat(btn.dataset.oaPrice),
                    mrp: parseFloat(btn.dataset.oaMrp),
                    img: btn.dataset.oaImg,
                    isVeg: btn.dataset.oaVeg === 'true',
                    size: 'default',
                    qty: 1
                }, function () {
                    showAddFeedback(btn);
                });
            });
        });
    }

    var oaItemsCache = [];

    function renderOrderAgainItems(items) {
        oaItemsCache = items;
        var scrollContainer = document.querySelector('.s14-order-again-scroll');
        var gridContainer = document.getElementById('s14-oa-grid');
        if (!scrollContainer) return;

        var html = '';
        items.forEach(function (item) { html += buildOaCardHtml(item); });
        scrollContainer.innerHTML = html;
        attachOaAddHandlers(scrollContainer);

        if (gridContainer) {
            gridContainer.innerHTML = html;
            attachOaAddHandlers(gridContainer);
        }
    }

    // View All toggle
    var oaViewAllBtn = document.getElementById('s14-oa-viewall');
    var oaExpanded = false;
    if (oaViewAllBtn) {
        oaViewAllBtn.addEventListener('click', function () {
            var scrollContainer = document.querySelector('.s14-order-again-scroll');
            var gridContainer = document.getElementById('s14-oa-grid');
            oaExpanded = !oaExpanded;
            if (oaExpanded) {
                scrollContainer.style.display = 'none';
                gridContainer.style.display = '';
                oaViewAllBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
            } else {
                scrollContainer.style.display = '';
                gridContainer.style.display = 'none';
                oaViewAllBtn.innerHTML = '<i class="bi bi-grid"></i>';
            }
        });
    }

    // ===== REORDER TAB =====
    var reorderLoaded = false;
    function loadReorderItems() {
        var listContainer = document.querySelector('.s14-reorder-list');
        var emptyContainer = document.querySelector('.s14-reorder-empty');
        if (!listContainer) return;
        if (reorderLoaded) return;

        listContainer.innerHTML = '<div style="text-align:center;padding:40px 0;"><div class="spinner-border" style="width:2rem;height:2rem;border:3px solid rgba(0,0,0,0.1);border-top-color:var(--s14-accent);border-radius:50%;animation:spin .6s linear infinite;display:inline-block;"></div></div>';

        var phone = getSavedPhone();
        var url = '/menu-cart/ordered-items?store_id=' + storeId + '&device_id=' + deviceId + (phone ? '&phone=' + encodeURIComponent(phone) : '');
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                var items = res.items || [];
                if (items.length === 0) {
                    listContainer.innerHTML = '';
                    if (emptyContainer) emptyContainer.style.display = '';
                    return;
                }
                if (emptyContainer) emptyContainer.style.display = 'none';
                var html = '<div class="s14-reorder-grid">';
                items.forEach(function (item) { html += buildOaCardHtml(item); });
                html += '</div>';
                listContainer.innerHTML = html;
                attachOaAddHandlers(listContainer);
                reorderLoaded = true;
            }
        };
        xhr.send();
    }

    // Load order again items on page load
    loadOrderAgainItems();

    // ===== CHECKOUT =====
    const checkoutBtn = document.querySelector('.s14-checkout-btn');
    const deliveryBtn = document.querySelector('.s14-delivery-btn');

    function setActiveOrderType(type) {
        activeOrderType = type;
        var btns = document.querySelectorAll('.s14-checkout-buttons .order-type-btn');
        btns.forEach(function (b) {
            if (b.dataset.type === type) {
                b.classList.add('active');
            } else {
                b.classList.remove('active');
            }
        });
        renderCart();
    }

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            if (cartData.length === 0) return;
            if (storeOrderType == 3) {
                // Blurred → just activate, don't open form
                if (activeOrderType !== 'dine-in') {
                    setActiveOrderType('dine-in');
                    return;
                }
            }
            showDineInForm();
        });
    }

    if (deliveryBtn) {
        deliveryBtn.addEventListener('click', () => {
            if (cartData.length === 0) return;
            if (storeOrderType == 3) {
                // Blurred → just activate, don't open form
                if (activeOrderType !== 'delivery') {
                    setActiveOrderType('delivery');
                    return;
                }
            }
            showDeliveryForm();
        });
    }

    function placeOrder(type, deliveryInfo) {
        var payload = {
            store_id: storeId,
            device_id: deviceId,
            order_type: type,
            customer_name: (deliveryInfo && deliveryInfo.name) ? deliveryInfo.name : null,
            customer_phone: (deliveryInfo && deliveryInfo.phone) ? deliveryInfo.phone : null,
            delivery_address: (deliveryInfo && deliveryInfo.address) ? deliveryInfo.address : null,
            instructions: (deliveryInfo && deliveryInfo.instructions) ? deliveryInfo.instructions : null
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/menu-cart/place-order', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                cartData = [];
                updateCartBadge();
                showThankYou({
                    order_id: res.order_id,
                    total: res.total,
                    item_count: res.item_count,
                    order_type: type
                });
            } else {
                alert('Failed to place order. Please try again.');
            }
        };
        xhr.onerror = function () {
            alert('Network error. Please try again.');
        };
        xhr.send(JSON.stringify(payload));
    }

    // ===== THANK YOU POPUP (old centered card - commented for later use) =====
    /*
    function showThankYou(order) {
        const overlay = document.createElement('div');
        overlay.className = 's14-thankyou-overlay';
        overlay.innerHTML = `
            <div class="s14-thankyou-card">
                <div class="s14-checkmark-circle">
                    <svg viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="16" stroke-width="2"/>
                        <path d="M10 18 L16 24 L26 13"/>
                    </svg>
                </div>
                <h3>Order Placed!</h3>
                <p>Thank you for your order</p>
                <span class="s14-order-ref">Order #${order.id}</span>
                <button class="s14-thankyou-close">Done</button>
            </div>`;
        document.body.appendChild(overlay);
        requestAnimationFrame(() => { overlay.classList.add('active'); });
        const closeBtn = overlay.querySelector('.s14-thankyou-close');
        function closeThankyou() {
            overlay.classList.remove('active');
            setTimeout(() => { overlay.remove(); switchTab('order'); }, 300);
        }
        closeBtn.addEventListener('click', closeThankyou);
        overlay.addEventListener('click', (e) => { if (e.target === overlay) closeThankyou(); });
    }
    */

    // ===== THANK YOU BOTTOM SHEET =====
    function showThankYou(order) {
        var existing = document.querySelector('.s14-ty-overlay');
        if (existing) existing.remove();
        var existingSheet = document.querySelector('.s14-ty-sheet');
        if (existingSheet) existingSheet.remove();

        var itemCount = order.item_count || 0;
        var orderId = order.order_id || 'ORD-' + Date.now().toString(36).toUpperCase().slice(-6);

        var html =
            '<div class="s14-ty-overlay"></div>' +
            '<div class="s14-ty-sheet">' +
                '<div class="s14-ty-header">' +
                    '<h3 class="s14-ty-header-title">Order Confirmed</h3>' +
                    '<button class="s14-ty-close-btn"><i class="bi bi-x-lg"></i></button>' +
                '</div>' +
                '<div class="s14-ty-body">' +
                    '<div class="s14-ty-checkmark">' +
                        '<svg viewBox="0 0 52 52">' +
                            '<circle cx="26" cy="26" r="25" fill="none"/>' +
                            '<path fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>' +
                        '</svg>' +
                    '</div>' +
                    '<h2 class="s14-ty-title">Thank You!</h2>' +
                    '<p class="s14-ty-subtitle">Your order has been placed successfully</p>' +
                    '<div class="s14-ty-order-info">' +
                        '<div class="s14-ty-row">' +
                            '<span>Order ID</span>' +
                            '<strong>' + orderId + '</strong>' +
                        '</div>' +
                        '<div class="s14-ty-row">' +
                            '<span>Items</span>' +
                            '<strong>' + itemCount + ' item' + (itemCount !== 1 ? 's' : '') + '</strong>' +
                        '</div>' +
                        '<div class="s14-ty-row total">' +
                            '<span>Total</span>' +
                            '<strong>\u20B9' + order.total + '</strong>' +
                        '</div>' +
                    '</div>' +
                    '<p class="s14-ty-message">Your food is being prepared with love. Please sit back and relax!</p>' +
                    (window.trackingPhone ? '<div class="s14-ty-tracking">' +
                        '<p class="s14-ty-tracking-label">For order tracking, call us at</p>' +
                        '<a href="tel:' + window.trackingPhone + '" class="s14-ty-tracking-phone">' +
                            '<i class="bi bi-telephone-fill"></i> ' +
                            window.trackingPhone +
                        '</a>' +
                    '</div>' : '') +
                '</div>' +
                '<div class="s14-ty-footer">' +
                    '<button class="s14-ty-done-btn">' +
                        'Done <i class="bi bi-check2"></i>' +
                    '</button>' +
                '</div>' +
            '</div>';

        document.body.insertAdjacentHTML('beforeend', html);

        var overlay = document.querySelector('.s14-ty-overlay');
        var sheet = document.querySelector('.s14-ty-sheet');
        var closeBtn = sheet.querySelector('.s14-ty-close-btn');
        var doneBtn = sheet.querySelector('.s14-ty-done-btn');

        requestAnimationFrame(function () {
            overlay.classList.add('active');
            sheet.classList.add('active');
        });

        document.body.style.overflow = 'hidden';

        function closeSheet() {
            overlay.classList.remove('active');
            sheet.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(function () {
                overlay.remove();
                sheet.remove();
                switchTab('order');
            }, 400);
        }

        closeBtn.addEventListener('click', closeSheet);
        doneBtn.addEventListener('click', closeSheet);
        overlay.addEventListener('click', closeSheet);
    }

    // ===== DELIVERY FORM =====
    function showDineInForm() {
        const overlay = document.createElement('div');
        overlay.className = 's14-delivery-overlay';
        document.body.appendChild(overlay);

        const popup = document.createElement('div');
        popup.className = 's14-delivery-popup';
        popup.innerHTML = `
            <div class="s14-delivery-header">
                <h3><i class="bi bi-shop"></i> Dine-In Details</h3>
                <button class="s14-delivery-close"><i class="bi bi-x-lg"></i></button>
            </div>
            <form class="s14-delivery-form">
                <div class="s14-form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" placeholder="Enter your mobile number" value="${getSavedPhone()}" required>
                    <div class="s14-form-error">Please enter a valid phone number</div>
                </div>

                <button type="submit" class="s14-submit-delivery">
                    <i class="bi bi-check2-circle"></i> Place Order
                </button>
            </form>`;
        document.body.appendChild(popup);

        requestAnimationFrame(() => {
            overlay.classList.add('active');
            popup.classList.add('active');
        });

        function closeForm() {
            overlay.classList.remove('active');
            popup.classList.remove('active');
            setTimeout(() => {
                overlay.remove();
                popup.remove();
            }, 350);
        }

        overlay.addEventListener('click', closeForm);
        popup.querySelector('.s14-delivery-close').addEventListener('click', closeForm);

        popup.querySelector('.s14-delivery-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const form = e.target;
            const phone = form.phone.value.trim();

            if (!phone || phone.length < 10) {
                form.querySelector('[name="phone"]').classList.add('error');
                return;
            } else {
                form.querySelector('[name="phone"]').classList.remove('error');
            }

            savePhone(phone);
            closeForm();
            setTimeout(() => {
                placeOrder('dine-in', { phone });
            }, 400);
        });
    }

    function showDeliveryForm() {
        const overlay = document.createElement('div');
        overlay.className = 's14-delivery-overlay';
        document.body.appendChild(overlay);

        const popup = document.createElement('div');
        popup.className = 's14-delivery-popup';
        popup.innerHTML = `
            <div class="s14-delivery-header">
                <h3><i class="bi bi-truck"></i> Delivery Details</h3>
                <button class="s14-delivery-close"><i class="bi bi-x-lg"></i></button>
            </div>
            <form class="s14-delivery-form">
                <div class="s14-form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" placeholder="Enter your name" required>
                    <div class="s14-form-error">Please enter your name</div>
                </div>
                <div class="s14-form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" placeholder="Enter phone number" value="${getSavedPhone()}" required>
                    <div class="s14-form-error">Please enter a valid phone number</div>
                </div>
                <div class="s14-form-group">
                    <label>Delivery Address *</label>
                    <textarea name="address" placeholder="Enter full delivery address" required></textarea>
                    <div class="s14-form-error">Please enter your address</div>
                </div>
                <div class="s14-form-group">
                    <label>Special Instructions</label>
                    <input type="text" name="instructions" placeholder="Any special instructions (optional)">
                </div>
                <button type="submit" class="s14-submit-delivery">
                    <i class="bi bi-check2-circle"></i> Place Order
                </button>
            </form>`;
        document.body.appendChild(popup);

        requestAnimationFrame(() => {
            overlay.classList.add('active');
            popup.classList.add('active');
        });

        function closeForm() {
            overlay.classList.remove('active');
            popup.classList.remove('active');
            setTimeout(() => {
                overlay.remove();
                popup.remove();
            }, 350);
        }

        overlay.addEventListener('click', closeForm);
        popup.querySelector('.s14-delivery-close').addEventListener('click', closeForm);

        // Form submission
        popup.querySelector('.s14-delivery-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const form = e.target;
            const name = form.name.value.trim();
            const phone = form.phone.value.trim();
            const address = form.address.value.trim();
            const instructions = form.instructions.value.trim();

            let valid = true;

            // Validate
            if (!name) {
                form.querySelector('[name="name"]').classList.add('error');
                valid = false;
            } else {
                form.querySelector('[name="name"]').classList.remove('error');
            }
            if (!phone || phone.length < 10) {
                form.querySelector('[name="phone"]').classList.add('error');
                valid = false;
            } else {
                form.querySelector('[name="phone"]').classList.remove('error');
            }
            if (!address) {
                form.querySelector('[name="address"]').classList.add('error');
                valid = false;
            } else {
                form.querySelector('[name="address"]').classList.remove('error');
            }

            if (!valid) return;

            savePhone(phone);
            closeForm();
            setTimeout(() => {
                placeOrder('delivery', { name, phone, address, instructions });
            }, 400);
        });
    }

    // ===== BANNER CAROUSEL =====
    const bannerTrack = document.querySelector('.s14-banner-track');
    const bannerSlides = document.querySelectorAll('.s14-banner-slide');
    const bannerDots = document.querySelectorAll('.s14-dot');
    const prevBtn = document.querySelector('.s14-banner-nav.prev');
    const nextBtn = document.querySelector('.s14-banner-nav.next');
    let currentSlide = 0;
    let autoSlideInterval;

    function goToSlide(index) {
        if (index < 0) index = bannerSlides.length - 1;
        if (index >= bannerSlides.length) index = 0;
        currentSlide = index;
        bannerTrack.style.transform = 'translateX(-' + (currentSlide * 100) + '%)';
        bannerDots.forEach((dot, i) => dot.classList.toggle('active', i === currentSlide));
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => goToSlide(currentSlide + 1), 4000);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    prevBtn.addEventListener('click', () => { goToSlide(currentSlide - 1); resetAutoSlide(); });
    nextBtn.addEventListener('click', () => { goToSlide(currentSlide + 1); resetAutoSlide(); });
    bannerDots.forEach((dot, i) => {
        dot.addEventListener('click', () => { goToSlide(i); resetAutoSlide(); });
    });

    // Touch swipe
    let touchStartX = 0;
    const bannerEl = document.querySelector('.s14-banner');
    bannerEl.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
    bannerEl.addEventListener('touchend', (e) => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) goToSlide(currentSlide + 1);
            else goToSlide(currentSlide - 1);
            resetAutoSlide();
        }
    }, { passive: true });

    startAutoSlide();

    // ===== BOTTOM NAV HIDE/SHOW ON SCROLL =====
    const bottomNav = document.querySelector('.s14-bottom-nav');
    let lastScrollY = window.scrollY;
    let scrollTicking = false;

    window.addEventListener('scroll', () => {
        if (!scrollTicking) {
            requestAnimationFrame(() => {
                const currentScrollY = window.scrollY;
                if (currentScrollY > lastScrollY && currentScrollY > 80) {
                    bottomNav.classList.add('hidden');
                } else {
                    bottomNav.classList.remove('hidden');
                }
                lastScrollY = currentScrollY;
                scrollTicking = false;
            });
            scrollTicking = true;
        }
    }, { passive: true });

    // ===== SEARCH =====
    const searchToggle = document.querySelector('.s14-search-toggle');
    const searchSection = document.querySelector('.s14-search-section');
    const searchInput = document.querySelector('.s14-search-input');
    const clearSearchBtn = document.querySelector('.s14-clear-search');
    let currentFilter = 'all';
    let searchQuery = '';

    searchToggle.addEventListener('click', () => {
        const visible = searchSection.style.display !== 'none';
        searchSection.style.display = visible ? 'none' : 'block';
        searchToggle.classList.toggle('active', !visible);
        if (!visible) searchInput.focus();
    });

    searchInput.addEventListener('input', () => {
        searchQuery = searchInput.value.trim().toLowerCase();
        clearSearchBtn.style.display = searchQuery ? 'flex' : 'none';
        applyFilters();
    });

    clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        searchQuery = '';
        clearSearchBtn.style.display = 'none';
        applyFilters();
        searchInput.focus();
    });

    // ===== FILTER DROPDOWN =====
    const filterDropdown = document.querySelector('.s14-filter-dropdown');
    const filterBtn = document.querySelector('.s14-filter-btn');
    const filterOptions = document.querySelectorAll('.s14-filter-option');

    filterBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        filterDropdown.classList.toggle('open');
    });

    document.addEventListener('click', () => {
        filterDropdown.classList.remove('open');
    });

    filterOptions.forEach(opt => {
        opt.addEventListener('click', (e) => {
            e.stopPropagation();
            currentFilter = opt.dataset.filter;
            filterOptions.forEach(o => o.classList.toggle('active', o === opt));

            // Update button icon and text
            const btnIcon = filterBtn.querySelector('.s14-filter-icon');
            const btnText = filterBtn.querySelector('.s14-filter-text');
            btnIcon.className = 's14-filter-icon ' + opt.querySelector('.s14-filter-icon').className.split(' ').pop();
            btnText.textContent = opt.querySelector('span:last-child').textContent;

            filterDropdown.classList.remove('open');
            applyFilters();
        });
    });

    // ===== APPLY FILTERS =====
    function applyFilters() {
        const cards = document.querySelectorAll('.s14-menu-card');
        const sections = document.querySelectorAll('.s14-menu-section');
        let totalVisible = 0;

        cards.forEach(card => {
            const idx = parseInt(card.dataset.index);
            const item = menuItems[idx];
            let show = true;

            // Search filter (name, description, tags)
            if (searchQuery) {
                var nameMatch = item.name.toLowerCase().includes(searchQuery);
                var descMatch = item.desc && item.desc.toLowerCase().includes(searchQuery);
                var tagMatch = item.tags && item.tags.toLowerCase().includes(searchQuery);
                if (!nameMatch && !descMatch && !tagMatch) {
                    show = false;
                }
            }

            // Veg/Non-veg filter
            if (currentFilter === 'veg' && !item.isVeg) show = false;
            if (currentFilter === 'non-veg' && item.isVeg) show = false;

            card.style.display = show ? '' : 'none';
            if (show) totalVisible++;
        });

        // Hide empty sections
        sections.forEach(section => {
            const visibleCards = section.querySelectorAll('.s14-menu-card:not([style*="display: none"])');
            section.style.display = visibleCards.length > 0 ? '' : 'none';
        });

        // No results
        let noResults = document.querySelector('.s14-no-results');
        if (totalVisible === 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 's14-no-results';
                noResults.innerHTML = '<i class="bi bi-search"></i><p>No items found</p>';
                document.querySelector('.s14-menu-content').appendChild(noResults);
            }
            noResults.style.display = '';
        } else if (noResults) {
            noResults.style.display = 'none';
        }
    }

    // ===== CATEGORY WITH IMAGES (commented for later use) =====
    /*
    const catItems = document.querySelectorAll('.s14-cat-img-item');
    const menuSections = document.querySelectorAll('.s14-menu-section');

    catItems.forEach(item => {
        item.addEventListener('click', () => {
            catItems.forEach(c => c.classList.remove('active'));
            item.classList.add('active');

            const cat = item.dataset.category;
            if (cat === 'all') {
                menuSections.forEach(s => s.style.display = '');
            } else {
                menuSections.forEach(s => {
                    s.style.display = s.dataset.category === cat ? '' : 'none';
                });
            }

            if (searchQuery || currentFilter !== 'all') {
                applyFilters();
            }
        });
    });
    */

    // ===== CATEGORY WITH IMAGES (shows first 2 + View All) =====
    const catImgItems = document.querySelectorAll('.s14-cat-img-item');
    const menuSections = document.querySelectorAll('.s14-menu-section');
    let activeCategory = 'all';

    function selectCategory(cat) {
        activeCategory = cat;

        // Update image nav active states
        catImgItems.forEach(function (c) {
            c.classList.toggle('active', c.dataset.category === cat);
        });

        // Show/hide menu sections
        if (cat === 'all') {
            menuSections.forEach(function (s) { s.style.display = ''; });
        } else {
            menuSections.forEach(function (s) {
                s.style.display = s.dataset.category === cat ? '' : 'none';
            });
        }

        // Update popup active state
        document.querySelectorAll('.s14-cat-popup-item').forEach(function (item) {
            item.classList.toggle('active', item.dataset.category === cat);
        });

        // Re-apply search/filter on visible sections
        if (searchQuery || currentFilter !== 'all') {
            applyFilters();
        }
    }

    catImgItems.forEach(function (item) {
        item.addEventListener('click', function () {
            selectCategory(item.dataset.category);
        });
    });

    // ===== VIEW ALL CATEGORY POPUP =====
    var viewAllBtn = document.getElementById('s14-viewall-btn');
    var catOverlay = document.getElementById('s14-cat-overlay');
    var catPopup = document.getElementById('s14-cat-popup');
    var catCloseBtn = document.getElementById('s14-cat-close');

    function openCatPopup() {
        catOverlay.classList.add('active');
        catPopup.classList.add('active');
    }

    function closeCatPopup() {
        catOverlay.classList.remove('active');
        catPopup.classList.remove('active');
    }

    viewAllBtn.addEventListener('click', openCatPopup);
    catOverlay.addEventListener('click', closeCatPopup);
    catCloseBtn.addEventListener('click', closeCatPopup);

    document.querySelectorAll('.s14-cat-popup-item').forEach(function (item) {
        item.addEventListener('click', function () {
            selectCategory(item.dataset.category);
            closeCatPopup();
        });
    });

    // ===== SIZE PICKER POPUP (quick add from card) =====
    const sizePickerOverlay = document.querySelector('.s14-sizepicker-overlay');
    const sizePickerPopup = document.querySelector('.s14-sizepicker-popup');
    const sizePickerTitle = document.querySelector('.s14-sizepicker-title');
    const sizePickerClose = document.querySelector('.s14-sizepicker-close');
    const sizePickerBtns = document.querySelectorAll('.s14-sizepicker-btn');
    const sizePickerMinus = document.querySelector('.s14-sizepicker-minus');
    const sizePickerPlus = document.querySelector('.s14-sizepicker-plus');
    const sizePickerQtyVal = document.querySelector('.s14-sizepicker-qty-val');
    const sizePickerTotal = document.querySelector('.s14-sizepicker-total');
    const sizePickerAddBtn = document.querySelector('.s14-sizepicker-add');
    let sizePickerIndex = -1;
    let sizePickerSourceBtn = null;
    let sizePickerSize = 'quarter';
    let sizePickerQty = 1;

    let sizePickerVariationData = null; // holds the active food_variation for the picker

    function getSelectedVariationMrp() {
        var item = menuItems[sizePickerIndex];
        if (!sizePickerVariationData) return item ? (item.mrp || item.price) : 0;
        var val = sizePickerVariationData.values.find(function (v) { return v.label === sizePickerSize; });
        return val ? parseFloat(val.optionPrice) : (item ? (item.mrp || item.price) : 0);
    }

    function calcSizePickerDiscount(rawPrice) {
        var item = menuItems[sizePickerIndex];
        if (!item || !item.discount || item.discount <= 0) return rawPrice;
        if (item.discountType === 'percent') {
            return Math.round(rawPrice - (rawPrice * item.discount / 100));
        }
        return Math.max(0, rawPrice - item.discount);
    }

    function updateSizePickerTotal() {
        var mrp = getSelectedVariationMrp();
        var price = calcSizePickerDiscount(mrp);
        sizePickerTotal.textContent = '\u20B9' + (price * sizePickerQty);
    }

    function openSizePicker(index, sourceBtn) {
        var item = menuItems[index];
        var variations = item.foodVariations || [];
        // Use the first variation that has values
        var variation = null;
        for (var i = 0; i < variations.length; i++) {
            if (variations[i].values && variations[i].values.length > 0) {
                variation = variations[i];
                break;
            }
        }

        sizePickerIndex = index;
        sizePickerSourceBtn = sourceBtn;
        sizePickerQty = 1;
        sizePickerVariationData = variation;
        sizePickerTitle.textContent = item.name;
        sizePickerQtyVal.textContent = sizePickerQty;

        // Build variation options dynamically
        var optionsContainer = document.querySelector('.s14-sizepicker-options');
        optionsContainer.innerHTML = '';

        sizePickerTitle.innerHTML = '<i class="bi bi-sliders"></i> ' + (variation.name ? ('Select ' + variation.name) : 'Select Size');

        var hasDiscount = item.discount && item.discount > 0;

        // Default option (base price)
        var defaultMrp = item.mrp || item.price;
        var defaultDiscounted = calcSizePickerDiscount(defaultMrp);
        var defaultBtn = document.createElement('button');
        defaultBtn.className = 's14-sizepicker-btn active';
        defaultBtn.dataset.size = 'default';
        var defaultPriceHTML = '';
        if (hasDiscount && defaultDiscounted < defaultMrp) {
            defaultPriceHTML = '<s style="color:#999;font-weight:400;font-size:0.8rem;margin-right:4px">\u20B9' + defaultMrp + '</s> \u20B9' + defaultDiscounted;
        } else {
            defaultPriceHTML = '\u20B9' + defaultMrp;
        }
        defaultBtn.innerHTML =
            '<span class="s14-sizepicker-radio"></span>' +
            '<span class="s14-sizepicker-name">Default</span>' +
            '<span class="s14-sizepicker-price">' + defaultPriceHTML + '</span>';
        defaultBtn.addEventListener('click', function () {
            optionsContainer.querySelectorAll('.s14-sizepicker-btn').forEach(function (b) { b.classList.remove('active'); });
            defaultBtn.classList.add('active');
            sizePickerSize = 'default';
            updateSizePickerTotal();
        });
        optionsContainer.appendChild(defaultBtn);

        variation.values.forEach(function (val, vi) {
            var optPrice = parseFloat(val.optionPrice);
            var discountedPrice = calcSizePickerDiscount(optPrice);
            var btn = document.createElement('button');
            btn.className = 's14-sizepicker-btn';
            btn.dataset.size = val.label;
            var priceHTML = '';
            if (hasDiscount && discountedPrice < optPrice) {
                priceHTML = '<s style="color:#999;font-weight:400;font-size:0.8rem;margin-right:4px">\u20B9' + optPrice + '</s> \u20B9' + discountedPrice;
            } else {
                priceHTML = '\u20B9' + optPrice;
            }
            btn.innerHTML =
                '<span class="s14-sizepicker-radio"></span>' +
                '<span class="s14-sizepicker-name">' + val.label + '</span>' +
                '<span class="s14-sizepicker-price">' + priceHTML + '</span>';

            btn.addEventListener('click', function () {
                optionsContainer.querySelectorAll('.s14-sizepicker-btn').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                sizePickerSize = val.label;
                updateSizePickerTotal();
            });

            optionsContainer.appendChild(btn);
        });

        sizePickerSize = 'default';
        updateSizePickerTotal();
        sizePickerOverlay.classList.add('active');
        sizePickerPopup.classList.add('active');
    }

    function closeSizePicker() {
        sizePickerOverlay.classList.remove('active');
        sizePickerPopup.classList.remove('active');
    }

    sizePickerClose.addEventListener('click', closeSizePicker);
    sizePickerOverlay.addEventListener('click', closeSizePicker);

    // Qty controls
    sizePickerMinus.addEventListener('click', () => {
        if (sizePickerQty > 1) {
            sizePickerQty--;
            sizePickerQtyVal.textContent = sizePickerQty;
            updateSizePickerTotal();
        }
    });

    sizePickerPlus.addEventListener('click', () => {
        if (sizePickerQty < 20) {
            sizePickerQty++;
            sizePickerQtyVal.textContent = sizePickerQty;
            updateSizePickerTotal();
        }
    });

    // Add to cart from size picker
    sizePickerAddBtn.addEventListener('click', () => {
        var mrp = getSelectedVariationMrp();
        var price = calcSizePickerDiscount(mrp);
        addToCart(sizePickerIndex, sizePickerQty, sizePickerSize, price, mrp, function () {
            closeSizePicker();
            // Button feedback on source
            if (sizePickerSourceBtn) {
                var spOriginalIcon = sizePickerSourceBtn.innerHTML;
                sizePickerSourceBtn.classList.add('added');
                sizePickerSourceBtn.innerHTML = '<i class="bi bi-check"></i>';
                createFloatAnimation(sizePickerSourceBtn);
                setTimeout(() => {
                    sizePickerSourceBtn.classList.remove('added');
                    sizePickerSourceBtn.innerHTML = spOriginalIcon;
                }, 800);
            }
        });
    });

    // ===== ADD BUTTON FEEDBACK HELPER =====
    function showAddFeedback(btn) {
        var originalIcon = btn.innerHTML;
        btn.classList.add('added');
        btn.innerHTML = '<i class="bi bi-check"></i>';
        createFloatAnimation(btn);
        setTimeout(function () {
            btn.classList.remove('added');
            btn.innerHTML = originalIcon;
        }, 800);
    }

    // ===== ADD TO CART BUTTONS =====
    document.querySelectorAll('.s14-add-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const card = btn.closest('.s14-menu-card');
            const index = parseInt(card.dataset.index);
            const item = menuItems[index];
            const hasVariations = item.foodVariations && item.foodVariations.length > 0 &&
                item.foodVariations.some(function (v) { return v.values && v.values.length > 0; });

            if (hasVariations) {
                openSizePicker(index, btn);
            } else {
                addToCart(index, 1, 'default', item.price, function () {
                    showAddFeedback(btn);
                });
            }
        });
    });

    // ===== NO-IMAGE EXTRAS ADD BUTTONS =====
    document.querySelectorAll('.s14-noimg-add').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var noImgItem = btn.closest('.s14-noimg-item');
            var index = parseInt(noImgItem.dataset.index);
            var menuItem = menuItems[index];
            addToCart(index, 1, 'default', menuItem ? menuItem.price : 0, function () {
                btn.classList.add('added');
                btn.innerHTML = '<i class="bi bi-check"></i>';
                createFloatAnimation(btn);
                setTimeout(function () {
                    btn.classList.remove('added');
                    btn.innerHTML = '<i class="bi bi-plus"></i>';
                }, 800);
            });
        });
    });

    // ===== FLOAT ANIMATION =====
    function createFloatAnimation(sourceEl) {
        const rect = sourceEl.getBoundingClientRect();
        const cartNav = document.querySelector('.s14-nav-item[data-tab="cart"]');
        const cartRect = cartNav.getBoundingClientRect();

        const floater = document.createElement('div');
        floater.className = 's14-float-item';
        floater.style.left = rect.left + 'px';
        floater.style.top = rect.top + 'px';
        document.body.appendChild(floater);

        requestAnimationFrame(() => {
            floater.classList.add('fly');
            floater.style.left = (cartRect.left + cartRect.width / 2 - 20) + 'px';
            floater.style.top = (cartRect.top + cartRect.height / 2 - 20) + 'px';
        });

        setTimeout(() => floater.remove(), 700);
    }

    // ===== ITEM DETAIL POPUP =====
    const detailOverlay = document.querySelector('.s14-detail-overlay');
    const detailPopup = document.querySelector('.s14-detail-popup');
    const detailBadge = document.querySelector('.s14-detail-badge');
    const detailName = document.querySelector('.s14-detail-name');
    const detailDesc = document.querySelector('.s14-detail-desc');
    const detailPrice = document.querySelector('.s14-detail-price');
    const detailMrp = document.querySelector('.s14-detail-mrp');
    const detailDiscount = document.querySelector('.s14-detail-discount');
    const detailQtyVal = document.querySelector('.s14-detail-qty-val');
    const detailTotal = document.querySelector('.s14-detail-total');
    const detailClose = document.querySelector('.s14-detail-close');
    const detailMinus = document.querySelector('.s14-detail-minus');
    const detailPlus = document.querySelector('.s14-detail-plus');
    const detailAddBtn = document.querySelector('.s14-detail-add');
    const detailSliderTrack = document.querySelector('.s14-detail-slider-track');
    const detailSliderDots = document.querySelector('.s14-detail-slider-dots');
    const detailSliderPrev = document.querySelector('.s14-detail-slider-nav.prev');
    const detailSliderNext = document.querySelector('.s14-detail-slider-nav.next');
    let detailIndex = -1;
    let detailItemId = -1;
    let detailQty = 1;
    let detailSize = 'default';
    let detailVariationData = null;
    let detailItemPrice = 0;
    let detailItemDiscount = 0;
    let detailItemDiscountType = 'percent';
    let detailServerData = null;
    let detailSliderCurrent = 0;
    let detailSliderCount = 0;
    const detailSizeContainer = document.querySelector('.s14-size-options');

    // ===== IMAGE SLIDER LOGIC =====
    function renderDetailImages(images) {
        detailSliderTrack.innerHTML = '';
        detailSliderDots.innerHTML = '';
        detailSliderCurrent = 0;

        if (!images || images.length === 0) {
            detailSliderTrack.innerHTML = '<img src="" alt="">';
            detailSliderCount = 1;
            detailSliderPrev.classList.add('hidden');
            detailSliderNext.classList.add('hidden');
            return;
        }

        detailSliderCount = images.length;

        images.forEach(function (src, i) {
            var img = document.createElement('img');
            img.src = src;
            img.alt = 'Item image ' + (i + 1);
            detailSliderTrack.appendChild(img);

            if (images.length > 1) {
                var dot = document.createElement('span');
                if (i === 0) dot.className = 'active';
                dot.addEventListener('click', function () { goToDetailSlide(i); });
                detailSliderDots.appendChild(dot);
            }
        });

        if (images.length <= 1) {
            detailSliderPrev.classList.add('hidden');
            detailSliderNext.classList.add('hidden');
        } else {
            detailSliderPrev.classList.remove('hidden');
            detailSliderNext.classList.remove('hidden');
        }

        updateSliderPosition();
    }

    function goToDetailSlide(i) {
        detailSliderCurrent = i;
        updateSliderPosition();
    }

    function updateSliderPosition() {
        detailSliderTrack.style.transform = 'translateX(-' + (detailSliderCurrent * 100) + '%)';
        var dots = detailSliderDots.querySelectorAll('span');
        dots.forEach(function (d, i) {
            d.classList.toggle('active', i === detailSliderCurrent);
        });
    }

    detailSliderPrev.addEventListener('click', function (e) {
        e.stopPropagation();
        if (detailSliderCurrent > 0) goToDetailSlide(detailSliderCurrent - 1);
    });

    detailSliderNext.addEventListener('click', function (e) {
        e.stopPropagation();
        if (detailSliderCurrent < detailSliderCount - 1) goToDetailSlide(detailSliderCurrent + 1);
    });

    function getDetailVariationPrice() {
        if (!detailVariationData) return detailItemPrice;
        var val = detailVariationData.values.find(function (v) { return v.label === detailSize; });
        return val ? parseFloat(val.optionPrice) : detailItemPrice;
    }

    function updateDetailTotal() {
        var basePrice = getDetailVariationPrice();
        var discounted = calculateDiscountedPrice(basePrice);
        detailTotal.textContent = '\u20B9' + (discounted * detailQty);
    }

    function calculateDiscountedPrice(price) {
        if (detailItemDiscount <= 0) return price;
        if (detailItemDiscountType === 'percent') {
            return Math.round(price - (price * detailItemDiscount / 100));
        }
        return Math.max(0, price - detailItemDiscount);
    }

    function updateDetailPriceForSize() {
        var basePrice = getDetailVariationPrice();
        var discounted = calculateDiscountedPrice(basePrice);

        if (detailItemDiscount > 0 && discounted < basePrice) {
            detailMrp.innerHTML = '<s>\u20B9' + basePrice + '</s>';
            detailPrice.textContent = '\u20B9' + discounted;
            if (detailItemDiscountType === 'percent') {
                detailDiscount.textContent = detailItemDiscount + '% OFF';
            } else {
                detailDiscount.textContent = '\u20B9' + detailItemDiscount + ' OFF';
            }
        } else {
            detailPrice.textContent = '\u20B9' + basePrice;
            detailMrp.innerHTML = '';
            detailDiscount.textContent = '';
            discounted = basePrice;
        }
        detailTotal.textContent = '\u20B9' + (discounted * detailQty);
    }

    function renderDetailVariations(variations, basePrice) {
        var variation = null;
        for (var i = 0; i < variations.length; i++) {
            if (variations[i].values && variations[i].values.length > 0) {
                variation = variations[i];
                break;
            }
        }
        detailVariationData = variation;

        if (variation) {
            detailSizeContainer.style.display = '';
            var sizeLabel = detailSizeContainer.querySelector('.s14-size-label');
            if (sizeLabel) sizeLabel.textContent = variation.name ? ('Choose ' + variation.name) : 'Choose an option';
            var sizeGroup = detailSizeContainer.querySelector('.s14-size-group');
            sizeGroup.innerHTML = '';

            // Default option (base price, no variation)
            var defaultBtn = document.createElement('button');
            defaultBtn.className = 's14-size-btn active';
            defaultBtn.dataset.size = 'default';
            defaultBtn.innerHTML =
                '<span class="s14-size-name">Default</span>' +
                '<span class="s14-size-price">\u20B9' + basePrice + '</span>';
            defaultBtn.addEventListener('click', function () {
                sizeGroup.querySelectorAll('.s14-size-btn').forEach(function (b) { b.classList.remove('active'); });
                defaultBtn.classList.add('active');
                detailSize = 'default';
                updateDetailPriceForSize();
            });
            sizeGroup.appendChild(defaultBtn);

            variation.values.forEach(function (val, vi) {
                var optPrice = parseFloat(val.optionPrice);
                var btn = document.createElement('button');
                btn.className = 's14-size-btn';
                btn.dataset.size = val.label;
                btn.innerHTML =
                    '<span class="s14-size-name">' + val.label + '</span>' +
                    '<span class="s14-size-price">\u20B9' + optPrice + '</span>';

                btn.addEventListener('click', function () {
                    sizeGroup.querySelectorAll('.s14-size-btn').forEach(function (b) { b.classList.remove('active'); });
                    btn.classList.add('active');
                    detailSize = val.label;
                    updateDetailPriceForSize();
                });

                sizeGroup.appendChild(btn);
            });

            detailSize = 'default';
        } else {
            detailSizeContainer.style.display = 'none';
            detailSize = 'default';
        }
    }

    // Fullscreen loader for item detail
    var detailLoader = document.createElement('div');
    detailLoader.className = 's14-detail-loader';
    detailLoader.innerHTML = '<div class="s14-detail-loader-spinner"><div></div><div></div><div></div></div>';
    document.body.appendChild(detailLoader);

    function showDetailLoader() {
        detailLoader.classList.add('active');
    }
    function hideDetailLoader() {
        detailLoader.classList.remove('active');
    }

    function openDetail(index, itemId) {
        detailIndex = index;
        detailItemId = itemId;
        detailQty = 1;

        // Reset popup content
        detailName.textContent = '';
        detailDesc.textContent = '';
        renderDetailImages([]);
        detailPrice.textContent = '';
        detailMrp.innerHTML = '';
        detailDiscount.textContent = '';
        detailTotal.textContent = '';
        detailQtyVal.textContent = '1';
        detailSizeContainer.style.display = 'none';

        // Show loader
        showDetailLoader();

        // Fetch item detail from server
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/menu-item/detail?item_id=' + itemId, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            hideDetailLoader();
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                detailServerData = data;
                detailItemPrice = data.price;
                detailItemDiscount = data.discount || 0;
                detailItemDiscountType = data.discount_type || 'percent';

                // Build image list: thumbnail + additional images
                var allImages = [];
                if (data.image) allImages.push(data.image);
                if (data.images && data.images.length > 0) {
                    data.images.forEach(function (img) {
                        if (allImages.indexOf(img) === -1) allImages.push(img);
                    });
                }
                renderDetailImages(allImages);

                detailBadge.className = 's14-detail-badge ' + (data.veg == 1 ? 'veg' : 'non-veg');
                detailName.textContent = data.name;
                detailDesc.textContent = data.description;
                detailQtyVal.textContent = detailQty;

                renderDetailVariations(data.food_variations || [], data.price);
                updateDetailPriceForSize();

                // Open popup after data is ready
                detailOverlay.classList.add('active');
                detailPopup.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        };
        xhr.onerror = function () {
            hideDetailLoader();
        };
        xhr.send();
    }

    function closeDetail() {
        detailOverlay.classList.remove('active');
        detailPopup.classList.remove('active');
        document.body.style.overflow = '';
    }

    detailClose.addEventListener('click', closeDetail);
    detailOverlay.addEventListener('click', closeDetail);

    detailMinus.addEventListener('click', () => {
        if (detailQty > 1) {
            detailQty--;
            detailQtyVal.textContent = detailQty;
            updateDetailTotal();
        }
    });

    detailPlus.addEventListener('click', () => {
        if (detailQty < 20) {
            detailQty++;
            detailQtyVal.textContent = detailQty;
            updateDetailTotal();
        }
    });

    detailAddBtn.addEventListener('click', () => {
        if (!detailServerData) return;
        var mrpPrice = getDetailVariationPrice();
        var price = calculateDiscountedPrice(mrpPrice);
        addToCartDirect({
            cartKey: detailIndex + '-' + detailSize,
            index: detailIndex,
            itemId: detailServerData.id || 0,
            name: detailServerData.name,
            price: price,
            mrp: mrpPrice,
            img: detailServerData.image,
            isVeg: detailServerData.veg == 1,
            size: detailSize,
            qty: detailQty
        }, function () {
            closeDetail();
            // Flash the cart nav
            const cartNav = document.querySelector('.s14-nav-item[data-tab="cart"]');
            cartNav.style.transform = 'scale(1.15)';
            setTimeout(() => { cartNav.style.transform = ''; }, 300);
        });
    });

    // Card click opens detail
    document.querySelectorAll('.s14-menu-card').forEach(card => {
        card.addEventListener('click', () => {
            const index = parseInt(card.dataset.index);
            const itemId = parseInt(card.dataset.itemId);
            openDetail(index, itemId);
        });
    });

    // ===== TODAY SPECIAL ITEMS =====
    const specialItems = window.s14SpecialItems || [];

    // Find a special item's index in the main menuItems array by matching item id
    function findMenuIndex(itemId) {
        for (var i = 0; i < menuItems.length; i++) {
            if (menuItems[i].id === itemId) return i;
        }
        return -1;
    }

    function addSpecialToCart(specialIndex, btn) {
        const item = specialItems[specialIndex];
        if (!item) return;

        // Check if the item has variations via its menuItems entry
        var menuIndex = findMenuIndex(item.id);
        if (menuIndex !== -1) {
            var menuItem = menuItems[menuIndex];
            var hasVariations = menuItem.foodVariations && menuItem.foodVariations.length > 0 &&
                menuItem.foodVariations.some(function (v) { return v.values && v.values.length > 0; });

            if (hasVariations) {
                openSizePicker(menuIndex, btn);
                return;
            }
        }

        addToCartDirect({
            cartKey: (menuIndex !== -1 ? menuIndex : ('special-' + item.id)) + '-default',
            index: menuIndex,
            itemId: item.id,
            name: item.name,
            price: item.price,
            mrp: item.mrp || item.price,
            img: item.img,
            isVeg: item.isVeg,
            size: 'default',
            qty: 1
        }, function () {
            // Button feedback
            btn.classList.add('added');
            btn.innerHTML = '<i class="bi bi-check"></i> Added';
            createFloatAnimation(btn);
            setTimeout(function () {
                btn.classList.remove('added');
                btn.innerHTML = '<i class="bi bi-plus"></i> Add';
            }, 900);
        });
    }

    document.querySelectorAll('.s14-special-add-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var card = btn.closest('.s14-special-card');
            var specialIndex = parseInt(card.dataset.special);
            addSpecialToCart(specialIndex, btn);
        });
    });

    // Click on special card (not the add button) opens item detail
    document.querySelectorAll('.s14-special-card').forEach(function (card) {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function (e) {
            if (e.target.closest('.s14-special-add-btn')) return;
            var specialIndex = parseInt(card.dataset.special);
            var item = specialItems[specialIndex];
            if (!item) return;
            var menuIndex = findMenuIndex(item.id);
            if (menuIndex !== -1) {
                openDetail(menuIndex, item.id);
            } else if (item.id) {
                openDetail(-1, item.id);
            }
        });
    });

    // ===== POWERED BY MINI HEADER =====
    function initPoweredByHeader(options) {
        options = options || {};
        var brandName = window.poweredBy || 'Qrscop';
        var icon = options.icon || 'bi-lightning-charge-fill';
        var link = options.link || null;
        var position = options.position || 'top';

        if (document.querySelector('.powered-by-header')) return;

        var headerHTML = link
            ? '<div class="powered-by-header">' +
                  '<a href="' + link + '" target="_blank">' +
                      '<span class="powered-icon"><i class="bi ' + icon + '"></i></span>' +
                      '<span>Powered by</span>' +
                      '<strong>' + brandName + '</strong>' +
                  '</a>' +
              '</div>'
            : '<div class="powered-by-header">' +
                  '<span class="powered-icon"><i class="bi ' + icon + '"></i></span>' +
                  '<span>Powered by</span>' +
                  '<strong>' + brandName + '</strong>' +
              '</div>';

        if (position === 'top') {
            document.body.insertAdjacentHTML('afterbegin', headerHTML);
        } else {
            document.body.insertAdjacentHTML('beforeend', headerHTML);
        }
    }

    initPoweredByHeader();

    // ===== BANNER POPUP (shows once per tab) =====
    function showBannerPopup() {
        if (sessionStorage.getItem('s15-banner-shown')) return;

        var overlay = document.getElementById('s14-welcome-overlay') || document.getElementById('s14-imgbanner-overlay');
        if (!overlay) return;

        sessionStorage.setItem('s15-banner-shown', '1');

        requestAnimationFrame(function () {
            overlay.classList.add('active');
        });

        function closeBanner() {
            overlay.classList.remove('active');
        }

        overlay.querySelector('.s14-welcome-close').addEventListener('click', closeBanner);
        var extraClose = overlay.querySelector('.s14-welcome-btn') || overlay.querySelector('.s14-imgbanner-img');
        if (extraClose) extraClose.addEventListener('click', closeBanner);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeBanner();
        });
    }

    setTimeout(showBannerPopup, 800);

});
