/* Menu Style 14 (style-14) - Self-contained JS */

document.addEventListener('DOMContentLoaded', function () {

    // ===== MENU DATA (injected from blade via window.s15MenuItems) =====
    const menuItems = window.s15MenuItems || [];

    // ===== AJAX HELPER =====
    var storeId = window.s15StoreId || 0;
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

    // ===== DEVICE ID =====
    function getDeviceId() {
        var id = localStorage.getItem('s15_device_id');
        if (!id) {
            id = 'dev-' + Date.now().toString(36) + '-' + Math.random().toString(36).substr(2, 8);
            localStorage.setItem('s15_device_id', id);
        }
        return id;
    }
    var deviceId = getDeviceId();

    // ===== SAVED PHONE =====
    function getSavedPhone() {
        return localStorage.getItem('s15_phone_' + storeId) || '';
    }
    function savePhone(phone) {
        if (phone) localStorage.setItem('s15_phone_' + storeId, phone);
    }

    // ===== CART STATE =====
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
        const badge = document.querySelector('.s15-cart-badge');
        const count = getCartCount();
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    function addToCart(index, qty, size, variationPrice, mrpPrice, callback) {
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

    // Load cart on page load
    cartAjax('/menu-cart/get', null, function () {
        updateCartBadge();
    });

    // ===== TAB NAVIGATION =====
    var navItems = document.querySelectorAll('.s15-nav-item');
    var tabContents = document.querySelectorAll('.s15-tab-content');
    var navPill = document.querySelector('.s15-nav-pill-bg');

    function moveNavPill(activeBtn) {
        if (!navPill || !activeBtn) return;
        var nav = activeBtn.closest('.s15-bottom-nav');
        var navRect = nav.getBoundingClientRect();
        var btnRect = activeBtn.getBoundingClientRect();
        navPill.style.width = btnRect.width + 'px';
        navPill.style.left = (btnRect.left - navRect.left) + 'px';
        navPill.style.top = ((navRect.height - 38) / 2) + 'px';
        navPill.classList.add('visible');
    }

    function switchTab(tabName) {
        navItems.forEach(function (btn) {
            btn.classList.toggle('active', btn.dataset.tab === tabName);
            if (btn.dataset.tab === tabName) moveNavPill(btn);
        });
        tabContents.forEach(function (tc) {
            tc.classList.toggle('active', tc.id === 's15-tab-' + tabName);
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

    navItems.forEach(function (btn) {
        btn.addEventListener('click', function () { switchTab(btn.dataset.tab); });
    });

    // Initialize pill position
    setTimeout(function () {
        var activeNav = document.querySelector('.s15-nav-item.active');
        if (activeNav) moveNavPill(activeNav);
    }, 100);

    // ===== RENDER CART =====
    function renderCart() {
        var container = document.querySelector('.s15-cart-items-container');
        var summarySection = document.querySelector('.s15-cart-summary-section');

        if (cartData.length === 0) {
            container.innerHTML =
                '<div class="s15-cart-empty">' +
                    '<i class="bi bi-bag-x"></i>' +
                    '<h3>Your cart is empty</h3>' +
                    '<p>Add some delicious items from the menu</p>' +
                    '<button class="s15-browse-btn" onclick="document.querySelector(\'.s15-nav-item[data-tab=home]\').click()">' +
                        '<i class="bi bi-arrow-left"></i> Browse Menu' +
                    '</button>' +
                '</div>';
            summarySection.style.display = 'none';
            return;
        }

        var html = '';
        cartData.forEach(function (item) {
            var imgHtml = item.img ? '<img class="s15-cart-item-img" src="' + item.img + '" alt="' + item.name + '">' : '';
            var unitMrp = item.mrp || item.price;
            var hasDiscount = unitMrp > item.price;
            var discountPercent = hasDiscount ? Math.round((unitMrp - item.price) / unitMrp * 100) : 0;
            var mrpHtml = hasDiscount ? '<span class="s15-cart-item-discount">' + discountPercent + '%</span><s class="s15-cart-item-mrp">\u20B9' + (unitMrp * item.qty) + '</s> ' : '';
            var discountHtml = '';
            html +=
                '<div class="s15-cart-item">' +
                    imgHtml +
                    '<div class="s15-cart-item-info">' +
                        '<div class="s15-cart-item-title-row">' +
                            '<span class="s15-cart-item-name">' + item.name + '</span>' +
                            '<span class="s15-cart-item-size">' + item.size + '</span>' +
                        '</div>' +
                        '<div class="s15-cart-item-price-row">' +
                            '<span class="s15-cart-item-price">' + mrpHtml + '\u20B9' + (item.price * item.qty) + discountHtml + '</span>' +
                            '<div class="s15-cart-item-actions">' +
                                '<button class="s15-qty-btn ' + (item.qty === 1 ? 'delete-btn' : '') + '" data-cartkey="' + item.cartKey + '" data-action="minus">' +
                                    '<i class="bi ' + (item.qty === 1 ? 'bi-trash' : 'bi-dash') + '"></i>' +
                                '</button>' +
                                '<span class="s15-cart-item-qty">' + item.qty + '</span>' +
                                '<button class="s15-qty-btn" data-cartkey="' + item.cartKey + '" data-action="plus">' +
                                    '<i class="bi bi-plus"></i>' +
                                '</button>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
        });
        container.innerHTML = html;

        container.querySelectorAll('.s15-qty-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var cartKey = btn.dataset.cartkey;
                var action = btn.dataset.action;
                updateCartQty(cartKey, action === 'plus' ? 1 : -1, function () {
                    renderCart();
                });
            });
        });

        var subtotal = getSubtotal();
        var mrpTotal = getMrpTotal();
        var discountAmount = mrpTotal - subtotal;

        var showDeliveryFee = storeDeliveryFee > 0 &&
            (storeOrderType == 2 || (storeOrderType == 3 && activeOrderType === 'delivery'));
        var effectiveDeliveryFee = showDeliveryFee ? storeDeliveryFee : 0;
        var total = subtotal + effectiveDeliveryFee;

        document.querySelector('.s15-subtotal').textContent = '\u20B9' + subtotal;
        document.querySelector('.s15-total').textContent = '\u20B9' + total;

        var discountRow = document.querySelector('.s15-discount-row');
        if (discountRow) {
            if (discountAmount > 0) {
                discountRow.style.display = '';
                discountRow.querySelector('.s15-discount-amount').textContent = '-\u20B9' + discountAmount;
            } else {
                discountRow.style.display = 'none';
            }
        }

        var deliveryRow = document.querySelector('.s15-delivery-row');
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
        xhr.onerror = function () { if (callback) callback(); };
        xhr.send();
    }

    function renderOrders() {
        var container = document.querySelector('.s15-orders-list');
        container.innerHTML = '<div class="s15-orders-empty"><i class="bi bi-arrow-repeat s15-spin"></i><p>Loading orders...</p></div>';

        loadOrders(function () {
            if (placedOrders.length === 0) {
                container.innerHTML =
                    '<div class="s15-orders-empty">' +
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
                        ? ' <span class="s15-order-item-size">' + item.size + '</span>'
                        : '';
                    itemsHtml +=
                        '<div class="s15-order-item-row">' +
                            '<span>' + item.name + sizeTag + ' x' + item.qty + '</span>' +
                            '<span>\u20B9' + (item.price * item.qty) + '</span>' +
                        '</div>';
                    itemCount += item.qty;
                });

                var orderSummaryHtml = '';
                orderSummaryHtml += '<div class="s15-order-summary-row"><span>Subtotal</span><span>\u20B9' + order.subtotal + '</span></div>';
                if (order.discount > 0) {
                    orderSummaryHtml += '<div class="s15-order-summary-row s15-order-summary-discount"><span>Discount</span><span>-\u20B9' + order.discount + '</span></div>';
                }
                if (order.delivery_fee > 0) {
                    orderSummaryHtml += '<div class="s15-order-summary-row"><span>Delivery Fee</span><span>\u20B9' + order.delivery_fee + '</span></div>';
                }

                html +=
                    '<div class="s15-order-card">' +
                        '<div class="s15-order-header">' +
                            '<span class="s15-order-id">' + order.order_id + '</span>' +
                            '<span class="s15-order-status ' + statusClass + '">' + statusLabel + '</span>' +
                        '</div>' +
                        '<div class="s15-order-date-row">' +
                            '<i class="bi bi-clock"></i> ' + order.date +
                        '</div>' +
                        '<div class="s15-order-items-list">' + itemsHtml + '</div>' +
                        '<div class="s15-order-summary">' + orderSummaryHtml + '</div>' +
                        '<div class="s15-order-footer">' +
                            '<span class="s15-order-type ' + order.order_type + '">' +
                                (order.order_type === 'dine-in' ? '<i class="bi bi-shop"></i> Dine-In' : '<i class="bi bi-truck"></i> Delivery') +
                            '</span>' +
                            '<div class="s15-order-total-block">' +
                                '<span class="s15-order-total-label">' + itemCount + ' item' + (itemCount !== 1 ? 's' : '') + '</span>' +
                                '<span class="s15-order-total">\u20B9' + order.total + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
            });
            container.innerHTML = html;
        });
    }

    // ===== ORDER AGAIN SECTION =====
    function loadOrderAgainItems() {
        var section = document.getElementById('s15-order-again');
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
                var viewAllBtn = document.getElementById('s15-oa-viewall');
                if (viewAllBtn) viewAllBtn.style.display = '';
            }
        };
        xhr.send();
    }

    function buildOaCardHtml(item) {
        var imgSrc = item.image;
        var hasDiscount = item.price < item.mrp;
        var discountTag = '';
        if (hasDiscount) {
            discountTag = item.discount_type === 'percent'
                ? '<span class="s15-oa-offer">' + item.discount + '% OFF</span>'
                : '<span class="s15-oa-offer">\u20B9' + item.discount + ' OFF</span>';
        }
        return '<div class="s15-oa-card" data-item-id="' + item.id + '">' +
                '<div class="s15-oa-img-wrap">' +
                    '<img src="' + imgSrc + '" alt="' + item.name + '">' +
                    '<span class="s15-veg-badge ' + (item.veg == 1 ? 'veg' : 'nonveg') + '"></span>' +
                    discountTag +
                '</div>' +
                '<div class="s15-oa-body">' +
                    '<h4 class="s15-oa-name">' + item.name + '</h4>' +
                    '<div class="s15-oa-price-row">' +
                        '<span class="s15-oa-price">\u20B9' + item.price + '</span>' +
                        (hasDiscount ? '<span class="s15-oa-mrp"><s>\u20B9' + item.mrp + '</s></span>' : '') +
                        '<button class="s15-oa-add-btn" data-oa-id="' + item.id + '" data-oa-name="' + item.name.replace(/"/g, '&quot;') + '" data-oa-price="' + item.price + '" data-oa-mrp="' + item.mrp + '" data-oa-img="' + imgSrc + '" data-oa-veg="' + (item.veg == 1 ? 'true' : 'false') + '"><i class="bi bi-plus-lg"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function attachOaAddHandlers(container) {
        container.querySelectorAll('.s15-oa-add-btn').forEach(function (btn) {
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
                var dataIndex = menuItems.findIndex(function (m) { return m.id === itemId; });
                if (dataIndex === -1) dataIndex = 0;
                addToCartDirect({
                    cartKey: dataIndex + '-default',
                    index: dataIndex,
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
        var scrollContainer = document.querySelector('.s15-order-again-scroll');
        var gridContainer = document.getElementById('s15-oa-grid');
        if (!scrollContainer) return;

        // Render horizontal scroll view
        var html = '';
        items.forEach(function (item) { html += buildOaCardHtml(item); });
        scrollContainer.innerHTML = html;
        attachOaAddHandlers(scrollContainer);

        // Render grid view
        if (gridContainer) {
            gridContainer.innerHTML = html;
            attachOaAddHandlers(gridContainer);
        }
    }

    // View All toggle
    var oaViewAllBtn = document.getElementById('s15-oa-viewall');
    var oaExpanded = false;
    if (oaViewAllBtn) {
        oaViewAllBtn.addEventListener('click', function () {
            var scrollContainer = document.querySelector('.s15-order-again-scroll');
            var gridContainer = document.getElementById('s15-oa-grid');
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
        var listContainer = document.querySelector('.s15-reorder-list');
        var emptyContainer = document.querySelector('.s15-reorder-empty');
        if (!listContainer) return;
        if (reorderLoaded) return;

        listContainer.innerHTML = '<div style="text-align:center;padding:40px 0;"><div style="width:2rem;height:2rem;border:3px solid rgba(255,255,255,0.1);border-top-color:var(--s15-accent);border-radius:50%;animation:spin .6s linear infinite;display:inline-block;"></div></div>';

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
                var html = '<div class="s15-reorder-grid">';
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
    var checkoutBtn = document.querySelector('.s15-checkout-btn');
    var deliveryBtn = document.querySelector('.s15-delivery-btn');

    function setActiveOrderType(type) {
        activeOrderType = type;
        var btns = document.querySelectorAll('.s15-checkout-buttons .order-type-btn');
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
        checkoutBtn.addEventListener('click', function () {
            if (cartData.length === 0) return;
            if (storeOrderType == 3) {
                if (activeOrderType !== 'dine-in') {
                    setActiveOrderType('dine-in');
                    return;
                }
            }
            showDineInForm();
        });
    }

    if (deliveryBtn) {
        deliveryBtn.addEventListener('click', function () {
            if (cartData.length === 0) return;
            if (storeOrderType == 3) {
                if (activeOrderType !== 'delivery') {
                    setActiveOrderType('delivery');
                    return;
                }
            }
            showDeliveryForm();
        });
    }

    function showOrderLoader() {
        var loader = document.createElement('div');
        loader.className = 's15-order-loader';
        loader.innerHTML = '<div class="s15-order-loader-content"><div class="s15-order-spinner"></div><p>Placing your order...</p></div>';
        document.body.appendChild(loader);
    }

    function hideOrderLoader() {
        var loader = document.querySelector('.s15-order-loader');
        if (loader) loader.remove();
    }

    function getTableNo() {
        const params = new URLSearchParams(window.location.search);
        return params.get('table');
    }

    function placeOrder(type, deliveryInfo) {
        showOrderLoader();

        var payload = {
            store_id: storeId,
            device_id: deviceId,
            order_type: type,
            customer_name: (deliveryInfo && deliveryInfo.name) ? deliveryInfo.name : null,
            customer_phone: (deliveryInfo && deliveryInfo.phone) ? deliveryInfo.phone : null,
            delivery_address: (deliveryInfo && deliveryInfo.address) ? deliveryInfo.address : null,
            instructions: (deliveryInfo && deliveryInfo.instructions) ? deliveryInfo.instructions : null,
            table_no: getTableNo() 
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/menu-cart/place-order', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            hideOrderLoader();
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
        xhr.onerror = function () { hideOrderLoader(); alert('Network error. Please try again.'); };
        xhr.send(JSON.stringify(payload));
    }

    // ===== THANK YOU BOTTOM SHEET =====
    function showThankYou(order) {
        var existing = document.querySelector('.s15-ty-overlay');
        if (existing) existing.remove();
        var existingSheet = document.querySelector('.s15-ty-sheet');
        if (existingSheet) existingSheet.remove();

        var itemCount = order.item_count || 0;
        var orderId = order.order_id || 'ORD-' + Date.now().toString(36).toUpperCase().slice(-6);

        var html =
            '<div class="s15-ty-overlay"></div>' +
            '<div class="s15-ty-sheet">' +
                '<div class="s15-ty-header">' +
                    '<h3 class="s15-ty-header-title">Order Confirmed</h3>' +
                    '<button class="s15-ty-close-btn"><i class="bi bi-x-lg"></i></button>' +
                '</div>' +
                '<div class="s15-ty-body">' +
                    '<div class="s15-ty-checkmark">' +
                        '<svg viewBox="0 0 52 52">' +
                            '<circle cx="26" cy="26" r="25" fill="none"/>' +
                            '<path fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>' +
                        '</svg>' +
                    '</div>' +
                    '<h2 class="s15-ty-title">Thank You!</h2>' +
                    '<p class="s15-ty-subtitle">Your order has been placed successfully</p>' +
                    '<div class="s15-ty-order-info">' +
                        '<div class="s15-ty-row">' +
                            '<span>Order ID</span>' +
                            '<strong>' + orderId + '</strong>' +
                        '</div>' +
                        '<div class="s15-ty-row">' +
                            '<span>Items</span>' +
                            '<strong>' + itemCount + ' item' + (itemCount !== 1 ? 's' : '') + '</strong>' +
                        '</div>' +
                        '<div class="s15-ty-row total">' +
                            '<span>Total</span>' +
                            '<strong>\u20B9' + order.total + '</strong>' +
                        '</div>' +
                    '</div>' +
                    '<p class="s15-ty-message">Your food is being prepared with love. Please sit back and relax!</p>' +
                    (window.trackingPhone ? '<div class="s15-ty-tracking">' +
                        '<p class="s15-ty-tracking-label">For order tracking, call us at</p>' +
                        '<a href="tel:' + window.trackingPhone + '" class="s15-ty-tracking-phone">' +
                            '<i class="bi bi-telephone-fill"></i> ' +
                            window.trackingPhone +
                        '</a>' +
                    '</div>' : '') +
                '</div>' +
                '<div class="s15-ty-footer">' +
                    '<button class="s15-ty-done-btn">' +
                        'Done <i class="bi bi-check2"></i>' +
                    '</button>' +
                '</div>' +
            '</div>';

        document.body.insertAdjacentHTML('beforeend', html);

        var overlay = document.querySelector('.s15-ty-overlay');
        var sheet = document.querySelector('.s15-ty-sheet');
        var closeBtn = sheet.querySelector('.s15-ty-close-btn');
        var doneBtn = sheet.querySelector('.s15-ty-done-btn');

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

    // ===== DINE-IN FORM =====
    function showDineInForm() {
        var overlay = document.createElement('div');
        overlay.className = 's15-delivery-overlay';
        document.body.appendChild(overlay);

        var popup = document.createElement('div');
        popup.className = 's15-delivery-popup';
        popup.innerHTML =
            '<div class="s15-delivery-header">' +
                '<h3><i class="bi bi-shop"></i> Dine-In Details</h3>' +
                '<button class="s15-delivery-close"><i class="bi bi-x-lg"></i></button>' +
            '</div>' +
            '<form class="s15-delivery-form">' +
                '<div class="s15-form-group">' +
                    '<label>Phone Number *</label>' +
                    '<input type="tel" name="phone" placeholder="Enter your mobile number" value="' + getSavedPhone() + '" required>' +
                    '<div class="s15-form-error">Please enter a valid phone number</div>' +
                '</div>' +
                '<button type="submit" class="s15-submit-delivery">' +
                    '<i class="bi bi-check2-circle"></i> Place Order' +
                '</button>' +
            '</form>';
        document.body.appendChild(popup);

        requestAnimationFrame(function () {
            overlay.classList.add('active');
            popup.classList.add('active');
        });

        function closeForm() {
            overlay.classList.remove('active');
            popup.classList.remove('active');
            setTimeout(function () { overlay.remove(); popup.remove(); }, 350);
        }

        overlay.addEventListener('click', closeForm);
        popup.querySelector('.s15-delivery-close').addEventListener('click', closeForm);

        popup.querySelector('.s15-delivery-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var phone = e.target.phone.value.trim();
            if (!phone || phone.length < 10) {
                e.target.querySelector('[name="phone"]').classList.add('error');
                return;
            }
            e.target.querySelector('[name="phone"]').classList.remove('error');
            savePhone(phone);
            closeForm();
            setTimeout(function () { placeOrder('dine-in', { phone: phone }); }, 400);
        });
    }

    // ===== DELIVERY FORM =====
    function showDeliveryForm() {
        var overlay = document.createElement('div');
        overlay.className = 's15-delivery-overlay';
        document.body.appendChild(overlay);

        var popup = document.createElement('div');
        popup.className = 's15-delivery-popup';
        popup.innerHTML =
            '<div class="s15-delivery-header">' +
                '<h3><i class="bi bi-truck"></i> Delivery Details</h3>' +
                '<button class="s15-delivery-close"><i class="bi bi-x-lg"></i></button>' +
            '</div>' +
            '<form class="s15-delivery-form">' +
                '<div class="s15-form-group">' +
                    '<label>Full Name *</label>' +
                    '<input type="text" name="name" placeholder="Enter your name" required>' +
                    '<div class="s15-form-error">Please enter your name</div>' +
                '</div>' +
                '<div class="s15-form-group">' +
                    '<label>Phone Number *</label>' +
                    '<input type="tel" name="phone" placeholder="Enter phone number" value="' + getSavedPhone() + '" required>' +
                    '<div class="s15-form-error">Please enter a valid phone number</div>' +
                '</div>' +
                '<div class="s15-form-group">' +
                    '<label>Delivery Address *</label>' +
                    '<textarea name="address" placeholder="Enter full delivery address" required></textarea>' +
                    '<div class="s15-form-error">Please enter your address</div>' +
                '</div>' +
                '<div class="s15-form-group">' +
                    '<label>Special Instructions</label>' +
                    '<input type="text" name="instructions" placeholder="Any special instructions (optional)">' +
                '</div>' +
                '<button type="submit" class="s15-submit-delivery">' +
                    '<i class="bi bi-check2-circle"></i> Place Order' +
                '</button>' +
            '</form>';
        document.body.appendChild(popup);

        requestAnimationFrame(function () {
            overlay.classList.add('active');
            popup.classList.add('active');
        });

        function closeForm() {
            overlay.classList.remove('active');
            popup.classList.remove('active');
            setTimeout(function () { overlay.remove(); popup.remove(); }, 350);
        }

        overlay.addEventListener('click', closeForm);
        popup.querySelector('.s15-delivery-close').addEventListener('click', closeForm);

        popup.querySelector('.s15-delivery-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var form = e.target;
            var name = form.querySelector('[name="name"]').value.trim();
            var phone = form.phone.value.trim();
            var address = form.address.value.trim();
            var instructions = form.instructions.value.trim();
            var valid = true;

            if (!name) { form.querySelector('[name="name"]').classList.add('error'); valid = false; }
            else { form.querySelector('[name="name"]').classList.remove('error'); }
            if (!phone || phone.length < 10) { form.querySelector('[name="phone"]').classList.add('error'); valid = false; }
            else { form.querySelector('[name="phone"]').classList.remove('error'); }
            if (!address) { form.querySelector('[name="address"]').classList.add('error'); valid = false; }
            else { form.querySelector('[name="address"]').classList.remove('error'); }

            if (!valid) return;

            savePhone(phone);
            closeForm();
            setTimeout(function () {
                placeOrder('delivery', { name: name, phone: phone, address: address, instructions: instructions });
            }, 400);
        });
    }

    // ===== SEARCH =====
    var searchToggle = document.getElementById('s15-search-toggle');
    var searchBar = document.getElementById('s15-search-bar');
    var searchInput = document.querySelector('.s15-search-input');
    var searchCloseBtn = document.querySelector('.s15-search-close');
    var filterPills = document.querySelectorAll('.s15-fpill');
    var currentFilter = 'all';
    var searchQuery = '';

    if (searchToggle) {
        searchToggle.addEventListener('click', function () {
            var visible = searchBar.style.display !== 'none';
            searchBar.style.display = visible ? 'none' : 'block';
            searchToggle.classList.toggle('active', !visible);
            if (!visible) searchInput.focus();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            searchQuery = searchInput.value.trim().toLowerCase();
            applyFilters();
        });
    }

    if (searchCloseBtn) {
        searchCloseBtn.addEventListener('click', function () {
            searchInput.value = '';
            searchQuery = '';
            searchBar.style.display = 'none';
            searchToggle.classList.remove('active');
            applyFilters();
        });
    }

    filterPills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            filterPills.forEach(function (p) { p.classList.remove('active'); });
            pill.classList.add('active');
            currentFilter = pill.dataset.filter;
            applyFilters();
        });
    });

    // ===== CATEGORY TABS =====
    var catTabs = document.querySelectorAll('.s15-cat-tab');
    var catImgItems = document.querySelectorAll('.cat-img-item');
    var catPopupItemsS15 = document.querySelectorAll('.cat-popup-item');
    var menuSections = document.querySelectorAll('.s15-menu-section');
    var activeCategory = 'all';

    function selectCategory(cat) {
        activeCategory = cat;

        // Update s15-cat-tab active (if old tabs exist)
        catTabs.forEach(function (tab) {
            tab.classList.toggle('active', tab.dataset.category === cat);
        });

        // Update cat-img-item active (style-8 image nav)
        catImgItems.forEach(function (item) {
            item.classList.toggle('active', item.dataset.category === cat);
        });

        // Update popup items active
        catPopupItemsS15.forEach(function (item) {
            item.classList.toggle('active', item.dataset.category === cat);
        });

        if (cat === 'all') {
            menuSections.forEach(function (s) { s.style.display = ''; });
        } else {
            menuSections.forEach(function (s) {
                s.style.display = s.dataset.category === cat ? '' : 'none';
            });
        }

        // Scroll active nav into view
        var activeImgItem = document.querySelector('.cat-img-item.active');
        if (activeImgItem) {
            activeImgItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }

        if (searchQuery || currentFilter !== 'all') {
            applyFilters();
        }
    }

    catTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            selectCategory(tab.dataset.category);
        });
    });

    // Category image nav clicks
    catImgItems.forEach(function (item) {
        item.addEventListener('click', function () {
            selectCategory(item.dataset.category);
        });
    });

    // Category popup item clicks (scroll to section)
    catPopupItemsS15.forEach(function (item) {
        item.addEventListener('click', function () {
            selectCategory(item.dataset.category);
        });
    });

    // ===== APPLY FILTERS =====
    function applyFilters() {
        var items = document.querySelectorAll('.s15-item-row');
        var totalVisible = 0;

        items.forEach(function (row) {
            var idx = parseInt(row.dataset.index);
            var item = menuItems[idx];
            var show = true;

            // Search filter (name, description, tags)
            if (searchQuery) {
                var nameMatch = item.name.toLowerCase().includes(searchQuery);
                var descMatch = item.desc && item.desc.toLowerCase().includes(searchQuery);
                var tagMatch = item.tags && item.tags.toLowerCase().includes(searchQuery);
                if (!nameMatch && !descMatch && !tagMatch) {
                    show = false;
                }
            } else {
                // Category filter (only when not searching)
                if (activeCategory !== 'all' && item.category !== activeCategory) {
                    show = false;
                }
            }

            // Veg/Non-veg filter
            if (currentFilter === 'veg' && !item.isVeg) show = false;
            if (currentFilter === 'non-veg' && item.isVeg) show = false;

            row.style.display = show ? '' : 'none';
            if (show) totalVisible++;
        });

        // Hide empty sections
        menuSections.forEach(function (section) {
            var visibleItems = section.querySelectorAll('.s15-item-row:not([style*="display: none"])');
            section.style.display = visibleItems.length > 0 ? '' : 'none';
        });

        // No results message
        var noResults = document.querySelector('.s15-no-results');
        if (totalVisible === 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 's15-no-results';
                noResults.innerHTML = '<i class="bi bi-search"></i><p>No items found</p>';
                document.querySelector('.s15-menu-main').appendChild(noResults);
            }
            noResults.style.display = '';
        } else if (noResults) {
            noResults.style.display = 'none';
        }
    }

    // ===== SIZE PICKER POPUP =====
    var sizePickerOverlay = document.querySelector('.s15-sizepicker-overlay');
    var sizePickerPopup = document.querySelector('.s15-sizepicker-popup');
    var sizePickerTitle = document.querySelector('.s15-sizepicker-title');
    var sizePickerClose = document.querySelector('.s15-sizepicker-close');
    var sizePickerMinus = document.querySelector('.s15-sizepicker-minus');
    var sizePickerPlus = document.querySelector('.s15-sizepicker-plus');
    var sizePickerQtyVal = document.querySelector('.s15-sizepicker-qty-val');
    var sizePickerTotal = document.querySelector('.s15-sizepicker-total');
    var sizePickerAddBtn = document.querySelector('.s15-sizepicker-add');
    var sizePickerIndex = -1;
    var sizePickerSourceBtn = null;
    var sizePickerSize = 'default';
    var sizePickerQty = 1;
    var sizePickerVariationData = null;

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
        sizePickerQtyVal.textContent = sizePickerQty;

        var optionsContainer = document.querySelector('.s15-sizepicker-options');
        optionsContainer.innerHTML = '';

        sizePickerTitle.innerHTML = '<i class="bi bi-sliders"></i> ' + (variation.name ? ('Select ' + variation.name) : 'Select Size');

        var hasDiscount = item.discount && item.discount > 0;

        // Default option
        var defaultMrp = item.mrp || item.price;
        var defaultDiscounted = calcSizePickerDiscount(defaultMrp);
        var defaultBtn = document.createElement('button');
        defaultBtn.className = 's15-sizepicker-btn active';
        defaultBtn.dataset.size = 'default';
        var defaultPriceHTML = '';
        if (hasDiscount && defaultDiscounted < defaultMrp) {
            defaultPriceHTML = '<s style="color:#999;font-weight:400;font-size:0.8rem;margin-right:4px">\u20B9' + defaultMrp + '</s> \u20B9' + defaultDiscounted;
        } else {
            defaultPriceHTML = '\u20B9' + defaultMrp;
        }
        defaultBtn.innerHTML =
            '<span class="s15-sizepicker-radio"></span>' +
            '<span class="s15-sizepicker-name">Default</span>' +
            '<span class="s15-sizepicker-price">' + defaultPriceHTML + '</span>';
        defaultBtn.addEventListener('click', function () {
            optionsContainer.querySelectorAll('.s15-sizepicker-btn').forEach(function (b) { b.classList.remove('active'); });
            defaultBtn.classList.add('active');
            sizePickerSize = 'default';
            updateSizePickerTotal();
        });
        optionsContainer.appendChild(defaultBtn);

        variation.values.forEach(function (val) {
            var optPrice = parseFloat(val.optionPrice);
            var discountedPrice = calcSizePickerDiscount(optPrice);
            var btn = document.createElement('button');
            btn.className = 's15-sizepicker-btn';
            btn.dataset.size = val.label;
            var priceHTML = '';
            if (hasDiscount && discountedPrice < optPrice) {
                priceHTML = '<s style="color:#999;font-weight:400;font-size:0.8rem;margin-right:4px">\u20B9' + optPrice + '</s> \u20B9' + discountedPrice;
            } else {
                priceHTML = '\u20B9' + optPrice;
            }
            btn.innerHTML =
                '<span class="s15-sizepicker-radio"></span>' +
                '<span class="s15-sizepicker-name">' + val.label + '</span>' +
                '<span class="s15-sizepicker-price">' + priceHTML + '</span>';

            btn.addEventListener('click', function () {
                optionsContainer.querySelectorAll('.s15-sizepicker-btn').forEach(function (b) { b.classList.remove('active'); });
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
        document.body.style.overflow = 'hidden';
    }

    function closeSizePicker() {
        sizePickerOverlay.classList.remove('active');
        sizePickerPopup.classList.remove('active');
        document.body.style.overflow = '';
    }

    sizePickerClose.addEventListener('click', closeSizePicker);
    sizePickerOverlay.addEventListener('click', closeSizePicker);

    sizePickerMinus.addEventListener('click', function () {
        if (sizePickerQty > 1) {
            sizePickerQty--;
            sizePickerQtyVal.textContent = sizePickerQty;
            updateSizePickerTotal();
        }
    });

    sizePickerPlus.addEventListener('click', function () {
        if (sizePickerQty < 20) {
            sizePickerQty++;
            sizePickerQtyVal.textContent = sizePickerQty;
            updateSizePickerTotal();
        }
    });

    sizePickerAddBtn.addEventListener('click', function () {
        var mrp = getSelectedVariationMrp();
        var price = calcSizePickerDiscount(mrp);
        addToCart(sizePickerIndex, sizePickerQty, sizePickerSize, price, mrp, function () {
            closeSizePicker();
            if (sizePickerSourceBtn) {
                var spOriginalIcon = sizePickerSourceBtn.innerHTML;
                sizePickerSourceBtn.classList.add('added');
                sizePickerSourceBtn.innerHTML = '<i class="bi bi-check"></i>';
                createFloatAnimation(sizePickerSourceBtn);
                setTimeout(function () {
                    sizePickerSourceBtn.classList.remove('added');
                    sizePickerSourceBtn.innerHTML = spOriginalIcon;
                }, 800);
            }
        });
    });

    // ===== FLOAT ANIMATION =====
    function createFloatAnimation(sourceEl) {
        var rect = sourceEl.getBoundingClientRect();
        var cartNav = document.querySelector('.s15-nav-item[data-tab="cart"]');
        var cartRect = cartNav.getBoundingClientRect();

        var floater = document.createElement('div');
        floater.className = 's15-float-item';
        floater.style.left = rect.left + 'px';
        floater.style.top = rect.top + 'px';
        document.body.appendChild(floater);

        requestAnimationFrame(function () {
            floater.classList.add('fly');
            floater.style.left = (cartRect.left + cartRect.width / 2 - 20) + 'px';
            floater.style.top = (cartRect.top + cartRect.height / 2 - 20) + 'px';
        });

        setTimeout(function () { floater.remove(); }, 700);
    }

    // ===== ADD BUTTON FEEDBACK =====
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

    // ===== ADD TO CART BUTTONS (on item rows) =====
    document.querySelectorAll('.s15-add-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var row = btn.closest('.s15-item-row') || btn.closest('.s15-special-card');
            var index = row ? parseInt(row.dataset.index || row.dataset.special) : -1;

            // For special items, find by special index
            if (row && row.dataset.special !== undefined) {
                addSpecialToCart(parseInt(row.dataset.special), btn);
                return;
            }

            var item = menuItems[index];
            if (!item) return;

            var hasVariations = item.foodVariations && item.foodVariations.length > 0 &&
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

    // ===== ITEM DETAIL POPUP =====
    var detailOverlay = document.querySelector('.s15-detail-overlay');
    var detailPopup = document.querySelector('.s15-detail-popup');
    var detailBadge = document.querySelector('.s15-detail-badge');
    var detailName = document.querySelector('.s15-detail-name');
    var detailDesc = document.querySelector('.s15-detail-desc');
    var detailPrice = document.querySelector('.s15-detail-price');
    var detailMrp = document.querySelector('.s15-detail-mrp');
    var detailDiscount = document.querySelector('.s15-detail-discount');
    var detailQtyVal = document.querySelector('.s15-detail-qty-val');
    var detailTotal = document.querySelector('.s15-detail-total');
    var detailClose = document.querySelector('.s15-detail-close');
    var detailMinus = document.querySelector('.s15-detail-minus');
    var detailPlus = document.querySelector('.s15-detail-plus');
    var detailAddBtn = document.querySelector('.s15-detail-add');
    var detailSliderTrack = document.querySelector('.s15-detail-slider-track');
    var detailSliderDots = document.querySelector('.s15-detail-slider-dots');
    var detailSliderPrev = document.querySelector('.s15-detail-slider-nav.prev');
    var detailSliderNext = document.querySelector('.s15-detail-slider-nav.next');
    var detailIndex = -1;
    var detailItemId = -1;
    var detailQty = 1;
    var detailSize = 'default';
    var detailVariationData = null;
    var detailItemPrice = 0;
    var detailItemDiscount = 0;
    var detailItemDiscountType = 'percent';
    var detailServerData = null;
    var detailSliderCurrent = 0;
    var detailSliderCount = 0;
    var detailSizeContainer = document.querySelector('.s15-size-options');

    // ===== IMAGE SLIDER =====
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
        dots.forEach(function (d, i) { d.classList.toggle('active', i === detailSliderCurrent); });
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

    function calculateDiscountedPrice(price) {
        if (detailItemDiscount <= 0) return price;
        if (detailItemDiscountType === 'percent') {
            return Math.round(price - (price * detailItemDiscount / 100));
        }
        return Math.max(0, price - detailItemDiscount);
    }

    function updateDetailTotal() {
        var basePrice = getDetailVariationPrice();
        var discounted = calculateDiscountedPrice(basePrice);
        detailTotal.textContent = '\u20B9' + (discounted * detailQty);
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
            var sizeLabel = detailSizeContainer.querySelector('.s15-size-label');
            if (sizeLabel) sizeLabel.innerHTML = '<i class="bi bi-sliders" style="color:var(--s15-accent)"></i> ' + (variation.name ? ('Choose ' + variation.name) : 'Select Size');
            var sizeGroup = detailSizeContainer.querySelector('.s15-size-group');
            sizeGroup.innerHTML = '';

            var defaultBtn = document.createElement('button');
            defaultBtn.className = 's15-size-btn active';
            defaultBtn.dataset.size = 'default';
            defaultBtn.innerHTML =
                '<span class="s15-size-name">Default</span>' +
                '<span class="s15-size-price">\u20B9' + basePrice + '</span>';
            defaultBtn.addEventListener('click', function () {
                sizeGroup.querySelectorAll('.s15-size-btn').forEach(function (b) { b.classList.remove('active'); });
                defaultBtn.classList.add('active');
                detailSize = 'default';
                updateDetailPriceForSize();
            });
            sizeGroup.appendChild(defaultBtn);

            variation.values.forEach(function (val) {
                var optPrice = parseFloat(val.optionPrice);
                var btn = document.createElement('button');
                btn.className = 's15-size-btn';
                btn.dataset.size = val.label;
                btn.innerHTML =
                    '<span class="s15-size-name">' + val.label + '</span>' +
                    '<span class="s15-size-price">\u20B9' + optPrice + '</span>';

                btn.addEventListener('click', function () {
                    sizeGroup.querySelectorAll('.s15-size-btn').forEach(function (b) { b.classList.remove('active'); });
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

    // Fullscreen loader
    var detailLoader = document.createElement('div');
    detailLoader.className = 's15-detail-loader';
    detailLoader.innerHTML = '<div class="s15-detail-loader-spinner"><div></div><div></div><div></div></div>';
    document.body.appendChild(detailLoader);

    function showDetailLoader() { detailLoader.classList.add('active'); }
    function hideDetailLoader() { detailLoader.classList.remove('active'); }

    function openDetail(index, itemId) {
        detailIndex = index;
        detailItemId = itemId;
        detailQty = 1;

        detailName.textContent = '';
        detailDesc.textContent = '';
        renderDetailImages([]);
        detailPrice.textContent = '';
        detailMrp.innerHTML = '';
        detailDiscount.textContent = '';
        detailTotal.textContent = '';
        detailQtyVal.textContent = '1';
        detailSizeContainer.style.display = 'none';

        showDetailLoader();

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

                var allImages = [];
                if (data.image) allImages.push(data.image);
                if (data.images && data.images.length > 0) {
                    data.images.forEach(function (img) {
                        if (allImages.indexOf(img) === -1) allImages.push(img);
                    });
                }
                renderDetailImages(allImages);

                detailBadge.className = 's15-detail-badge ' + (data.veg == 1 ? 'veg' : 'non-veg');
                detailName.textContent = data.name;
                detailDesc.textContent = data.description;
                detailQtyVal.textContent = detailQty;

                renderDetailVariations(data.food_variations || [], data.price);
                updateDetailPriceForSize();

                detailOverlay.classList.add('active');
                detailPopup.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        };
        xhr.onerror = function () { hideDetailLoader(); };
        xhr.send();
    }

    function closeDetail() {
        detailOverlay.classList.remove('active');
        detailPopup.classList.remove('active');
        document.body.style.overflow = '';
    }

    detailClose.addEventListener('click', closeDetail);
    detailOverlay.addEventListener('click', closeDetail);

    detailMinus.addEventListener('click', function () {
        if (detailQty > 1) {
            detailQty--;
            detailQtyVal.textContent = detailQty;
            updateDetailTotal();
        }
    });

    detailPlus.addEventListener('click', function () {
        if (detailQty < 20) {
            detailQty++;
            detailQtyVal.textContent = detailQty;
            updateDetailTotal();
        }
    });

    detailAddBtn.addEventListener('click', function () {
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
            var cartNav = document.querySelector('.s15-nav-item[data-tab="cart"]');
            cartNav.style.transform = 'scale(1.15)';
            setTimeout(function () { cartNav.style.transform = ''; }, 300);
        });
    });

    // Item row click opens detail
    document.querySelectorAll('.s15-item-row').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('.s15-add-btn')) return;
            var index = parseInt(row.dataset.index);
            var itemId = parseInt(row.dataset.itemId);
            openDetail(index, itemId);
        });
    });

    // ===== TODAY SPECIAL ITEMS =====
    var specialItems = window.s15SpecialItems || [];

    function findMenuIndex(itemId) {
        for (var i = 0; i < menuItems.length; i++) {
            if (menuItems[i].id === itemId) return i;
        }
        return -1;
    }

    function addSpecialToCart(specialIndex, btn) {
        var item = specialItems[specialIndex];
        if (!item) return;

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
            btn.classList.add('added');
            btn.innerHTML = '<i class="bi bi-check"></i> Added';
            createFloatAnimation(btn);
            setTimeout(function () {
                btn.classList.remove('added');
                btn.innerHTML = '<i class="bi bi-plus"></i> Add';
            }, 900);
        });
    }

    // Special card clicks
    document.querySelectorAll('.s15-special-card').forEach(function (card) {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function (e) {
            if (e.target.closest('.s15-add-btn')) return;
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

    // ===== BANNER CAROUSEL =====
    var bannerTrack = document.querySelector('.s15-banner-track');
    var bannerSlides = document.querySelectorAll('.s15-banner-slide');
    var bannerDots = document.querySelectorAll('.s15-dot');
    var prevBtn = document.querySelector('.s15-banner-nav.prev');
    var nextBtn = document.querySelector('.s15-banner-nav.next');
    var currentSlide = 0;
    var autoSlideInterval;

    function goToSlide(index) {
        if (!bannerTrack || bannerSlides.length === 0) return;
        if (index < 0) index = bannerSlides.length - 1;
        if (index >= bannerSlides.length) index = 0;
        currentSlide = index;
        bannerTrack.style.transform = 'translateX(-' + (currentSlide * 100) + '%)';
        bannerDots.forEach(function (dot, i) { dot.classList.toggle('active', i === currentSlide); });
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(function () { goToSlide(currentSlide + 1); }, 4000);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function () { goToSlide(currentSlide - 1); resetAutoSlide(); });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function () { goToSlide(currentSlide + 1); resetAutoSlide(); });
    }
    bannerDots.forEach(function (dot, i) {
        dot.addEventListener('click', function () { goToSlide(i); resetAutoSlide(); });
    });

    // Touch swipe for banner
    var touchStartX = 0;
    var bannerEl = document.querySelector('.s15-banner');
    if (bannerEl) {
        bannerEl.addEventListener('touchstart', function (e) { touchStartX = e.touches[0].clientX; }, { passive: true });
        bannerEl.addEventListener('touchend', function (e) {
            var diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) goToSlide(currentSlide + 1);
                else goToSlide(currentSlide - 1);
                resetAutoSlide();
            }
        }, { passive: true });
    }

    if (bannerSlides.length > 0) startAutoSlide();

    // ===== BOTTOM NAV HIDE/SHOW ON SCROLL =====
    var bottomNav = document.querySelector('.s15-bottom-nav');
    var lastScrollY = window.scrollY;
    var scrollTicking = false;

    window.addEventListener('scroll', function () {
        if (!scrollTicking) {
            requestAnimationFrame(function () {
                var currentScrollY = window.scrollY;
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

    // ===== POWERED BY MINI HEADER =====
    function initPoweredByHeader(options) {
        options = options || {};
        var brandName = window.poweredBy || 'Qrscop';
        var icon = options.icon || 'bi-lightning-charge-fill';
        var link = options.link || null;

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

        document.body.insertAdjacentHTML('afterbegin', headerHTML);
    }

    initPoweredByHeader();

    // ===== BANNER POPUP =====
    function showBannerPopup() {
        if (sessionStorage.getItem('s15-banner-shown')) return;

        var overlay = document.getElementById('s15-welcome-overlay') || document.getElementById('s15-imgbanner-overlay');
        if (!overlay) return;

        sessionStorage.setItem('s15-banner-shown', '1');

        requestAnimationFrame(function () {
            overlay.classList.add('active');
        });

        function closeBanner() {
            overlay.classList.remove('active');
        }

        overlay.querySelector('.s15-welcome-close').addEventListener('click', closeBanner);
        var extraClose = overlay.querySelector('.s15-welcome-btn') || overlay.querySelector('.s15-imgbanner-img');
        if (extraClose) extraClose.addEventListener('click', closeBanner);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeBanner();
        });
    }

    setTimeout(showBannerPopup, 800);

});
