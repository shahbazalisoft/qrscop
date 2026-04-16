// ===== COMMON CART FUNCTIONALITY (AJAX-based) =====

class MenuCart {
    constructor() {
        this.storeId = window.storeId || 0;
        this.csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
        this.cartData = [];
        this.deliveryFee = window.storeDeliveryCharge || 0;
        this.orderType = window.storeOrderType || 0;
        this.activeOrderType = 'dine-in';
        this.currency = '\u20B9';

        // DOM Elements
        this.cartSection = document.querySelector('.cart-section');
        this.cartOverlay = document.querySelector('.cart-overlay');
        this.cartItemsList = document.querySelector('.cart-items-list');
        this.cartBar = document.querySelector('.cart-bar');
        this.cartItemsCount = document.querySelector('.cart-items-count');
        this.cartTotalText = document.querySelector('.cart-total');
        this.subtotalAmount = document.querySelector('.subtotal-amount');
        this.totalAmount = document.querySelector('.total-amount');
        this.viewCartBtn = document.querySelector('.view-cart-btn');
        this.cartCloseBtn = document.querySelector('.cart-close-btn');
        this.cartFullscreenBtn = document.querySelector('.cart-fullscreen-btn');
        this.checkoutBtn = document.querySelector('.checkout-btn');
        this.deliveryBtn = document.querySelector('.delivery-btn');

        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCart();
    }

    // ===== AJAX HELPER =====
    cartAjax(url, data, callback) {
        var xhr = new XMLHttpRequest();
        var self = this;
        xhr.open(data ? 'POST' : 'GET', url + (data ? '' : '?store_id=' + this.storeId), true);
        xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                self.cartData = res.cart || [];
                if (callback) callback(res);
            }
        };
        xhr.send(data ? JSON.stringify(data) : null);
    }

    // ===== DEVICE ID =====
    getDeviceId() {
        var id = localStorage.getItem('menu_device_id');
        if (!id) {
            id = 'dev-' + Date.now().toString(36) + '-' + Math.random().toString(36).substr(2, 8);
            localStorage.setItem('menu_device_id', id);
        }
        return id;
    }

    // ===== PHONE =====
    getSavedPhone() {
        return localStorage.getItem('menu_phone_' + this.storeId) || '';
    }

    savePhone(phone) {
        if (phone) localStorage.setItem('menu_phone_' + this.storeId, phone);
    }

    // ===== CART COUNTS =====
    getCartCount() {
        return this.cartData.reduce(function (sum, item) { return sum + item.qty; }, 0);
    }

    getSubtotal() {
        return this.cartData.reduce(function (sum, item) { return sum + item.price * item.qty; }, 0);
    }

    getMrpTotal() {
        return this.cartData.reduce(function (sum, item) { return sum + (item.mrp || item.price) * item.qty; }, 0);
    }

    // ===== LOAD CART =====
    loadCart(callback) {
        var self = this;
        this.cartAjax('/menu-cart/get', null, function (res) {
            self.updateCartBar();
            if (callback) callback(res);
        });
    }

    // ===== ADD TO CART =====
    addToCart(data, callback) {
        var self = this;
        this.cartAjax('/menu-cart/add', {
            store_id: this.storeId,
            cart_key: data.cartKey,
            index: data.index || 0,
            item_id: data.itemId || 0,
            name: data.name,
            price: data.price,
            mrp: data.mrp || data.price,
            img: data.img || '',
            is_veg: data.isVeg || false,
            size: data.size || 'default',
            qty: data.qty || 1
        }, function (res) {
            self.updateCartBar();
            if (callback) callback(res);
        });
    }

    // Convenience method (used by item-detail-common.js)
    addItem(name, price, qty, isVeg, extraData) {
        var data = {
            cartKey: (extraData && extraData.cartKey) || name.replace(/[^a-z0-9]/gi, '-').toLowerCase(),
            index: (extraData && extraData.index) || 0,
            itemId: (extraData && extraData.itemId) || 0,
            name: name,
            price: price,
            img: (extraData && extraData.img) || '',
            isVeg: isVeg || false,
            size: (extraData && extraData.size) || 'default',
            qty: qty || 1
        };
        this.addToCart(data);
    }

    // ===== UPDATE QTY =====
    updateCartQty(cartKey, delta, callback) {
        var self = this;
        this.cartAjax('/menu-cart/update-qty', {
            store_id: this.storeId,
            cart_key: cartKey,
            delta: delta
        }, function (res) {
            self.updateCartBar();
            if (callback) callback(res);
        });
    }

    // ===== REMOVE =====
    removeFromCart(cartKey, callback) {
        var self = this;
        this.cartAjax('/menu-cart/remove', {
            store_id: this.storeId,
            cart_key: cartKey
        }, function (res) {
            self.updateCartBar();
            if (callback) callback(res);
        });
    }

    // ===== CLEAR =====
    clearCart(callback) {
        var self = this;
        this.cartAjax('/menu-cart/clear', {
            store_id: this.storeId
        }, function (res) {
            self.updateCartBar();
            if (callback) callback(res);
        });
    }

    // ===== BIND EVENTS =====
    bindEvents() {
        var self = this;

        if (this.viewCartBtn) {
            this.viewCartBtn.addEventListener('click', function () { self.openCart(); });
        }
        if (this.cartCloseBtn) {
            this.cartCloseBtn.addEventListener('click', function () { self.closeCart(); });
        }
        if (this.cartOverlay) {
            this.cartOverlay.addEventListener('click', function () { self.closeCart(); });
        }
        if (this.cartFullscreenBtn) {
            this.cartFullscreenBtn.addEventListener('click', function () { self.toggleFullscreen(); });
        }
        if (this.checkoutBtn) {
            this.checkoutBtn.addEventListener('click', function () {
                if (self.cartData.length === 0) return;
                if (self.orderType == 3) {
                    // Blurred → just activate, don't open form
                    if (self.activeOrderType !== 'dine-in') {
                        self.setActiveOrderType('dine-in');
                        return;
                    }
                }
                self.closeCart();
                self.showDineInForm();
            });
        }
        if (this.deliveryBtn) {
            this.deliveryBtn.addEventListener('click', function () {
                if (self.cartData.length === 0) return;
                if (self.orderType == 3) {
                    // Blurred → just activate, don't open form
                    if (self.activeOrderType !== 'delivery') {
                        self.setActiveOrderType('delivery');
                        return;
                    }
                }
                self.closeCart();
                self.showDeliveryForm();
            });
        }

        // Scroll hide/show cart bar
        var lastScrollY = window.scrollY;
        window.addEventListener('scroll', function () {
            if (self.cartBar) {
                if (window.scrollY > lastScrollY && window.scrollY > 100) {
                    self.cartBar.classList.add('hidden');
                } else {
                    self.cartBar.classList.remove('hidden');
                }
            }
            lastScrollY = window.scrollY;
        });
    }

    // ===== CART BAR UPDATE =====
    updateCartBar() {
        var count = this.getCartCount();
        var subtotal = this.getSubtotal();

        if (this.cartItemsCount) {
            this.cartItemsCount.textContent = count + ' Item' + (count !== 1 ? 's' : '');
        }
        if (this.cartTotalText) {
            this.cartTotalText.textContent = this.currency + subtotal;
        }
        if (this.cartBar) {
            this.cartBar.style.display = count > 0 ? 'flex' : 'none';
        }

        // Update bottom nav cart badge
        var badge = document.querySelector('.bottom-nav-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    // ===== OPEN/CLOSE CART =====
    openCart() {
        var self = this;
        this.cartAjax('/menu-cart/get', null, function () {
            self.renderCart();
            self.updateCartBar();
        });
        if (this.cartSection) this.cartSection.classList.add('active');
        if (this.cartOverlay) this.cartOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeCart() {
        if (this.cartSection) {
            this.cartSection.classList.remove('active');
            this.cartSection.classList.remove('fullscreen');
        }
        if (this.cartOverlay) this.cartOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    toggleFullscreen() {
        if (this.cartSection) this.cartSection.classList.toggle('fullscreen');
    }

    // ===== ORDER TYPE TOGGLE (order_type == 3) =====
    setActiveOrderType(type) {
        this.activeOrderType = type;
        var btns = document.querySelectorAll('.checkout-buttons .order-type-btn');
        btns.forEach(function (b) {
            if (b.dataset.type === type) {
                b.classList.add('active');
            } else {
                b.classList.remove('active');
            }
        });
        this.renderCart();
    }

    // ===== RENDER CART =====
    renderCart() {
        if (!this.cartItemsList) return;

        if (this.cartData.length === 0) {
            this.cartItemsList.innerHTML =
                '<div class="cart-empty">' +
                    '<i class="bi bi-cart-x"></i>' +
                    '<p>Your cart is empty</p>' +
                '</div>';
            if (this.subtotalAmount) this.subtotalAmount.textContent = this.currency + '0';
            if (this.totalAmount) this.totalAmount.textContent = this.currency + '0';
            return;
        }

        var self = this;
        var html = '';
        this.cartData.forEach(function (item) {
            var imgHtml = item.img
                ? '<img class="cart-item-img" src="' + item.img + '" alt="' + item.name + '">'
                : '';
            var sizeTag = item.size && item.size !== 'default'
                ? ' <span class="cart-item-size">(' + item.size + ')</span>'
                : '';
            var unitPrice = item.price;
            var unitMrp = item.mrp || item.price;
            var hasDiscount = unitMrp > unitPrice;
            var totalPrice = unitPrice * item.qty;
            var mrpHtml = hasDiscount
                ? '<s class="cart-item-mrp">' + self.currency + (unitMrp * item.qty) + '</s> '
                : '';
            var unitHtml = item.qty > 1
                ? '<span class="cart-item-unit">' + self.currency + unitPrice + ' x ' + item.qty + '</span>'
                : '';
            html +=
                '<div class="cart-item" data-cartkey="' + item.cartKey + '">' +
                    imgHtml +
                    '<div class="cart-item-info">' +
                        '<h4 class="cart-item-name">' + item.name + sizeTag + '</h4>' +
                        '<div class="cart-item-bottom-row">' +
                            '<div class="cart-item-price-info">' +
                                mrpHtml +
                                '<span class="cart-item-price">' + self.currency + totalPrice + '</span>' +
                                unitHtml +
                            '</div>' +
                            '<div class="cart-item-controls">' +
                                '<button class="qty-btn minus ' + (item.qty === 1 ? 'delete' : '') + '" data-cartkey="' + item.cartKey + '" data-action="minus">' +
                                    '<i class="bi ' + (item.qty === 1 ? 'bi-trash' : 'bi-dash') + '"></i>' +
                                '</button>' +
                                '<span class="cart-item-qty">' + item.qty + '</span>' +
                                '<button class="qty-btn plus" data-cartkey="' + item.cartKey + '" data-action="plus">' +
                                    '<i class="bi bi-plus"></i>' +
                                '</button>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
        });

        this.cartItemsList.innerHTML = html;

        // Bind qty buttons
        this.cartItemsList.querySelectorAll('.qty-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var cartKey = btn.dataset.cartkey;
                var action = btn.dataset.action;
                self.updateCartQty(cartKey, action === 'plus' ? 1 : -1, function () {
                    self.renderCart();
                });
            });
        });

        // Summary
        var subtotal = this.getSubtotal();
        var mrpTotal = this.getMrpTotal();
        var discountAmount = mrpTotal - subtotal;

        // Only include delivery fee when delivery is active (or when store is delivery-only)
        var showDeliveryFee = this.deliveryFee > 0 &&
            (this.orderType == 2 || (this.orderType == 3 && this.activeOrderType === 'delivery'));
        var effectiveDeliveryFee = showDeliveryFee ? this.deliveryFee : 0;
        var total = subtotal + effectiveDeliveryFee;

        if (this.subtotalAmount) this.subtotalAmount.textContent = this.currency + subtotal;
        if (this.totalAmount) this.totalAmount.textContent = this.currency + total;

        // Show/hide discount row
        var discountRow = document.querySelector('.cart-discount-row');
        if (discountRow) {
            if (discountAmount > 0) {
                discountRow.style.display = '';
                var discountAmountEl = discountRow.querySelector('.discount-amount');
                if (discountAmountEl) discountAmountEl.textContent = '-' + this.currency + discountAmount;
            } else {
                discountRow.style.display = 'none';
            }
        }

        // Show/hide delivery fee row
        var deliveryRow = document.querySelector('.delivery-fee-row');
        if (deliveryRow) {
            deliveryRow.style.display = showDeliveryFee ? '' : 'none';
        }
    }

    // ===== PLACE ORDER =====
    placeOrder(type, deliveryInfo) {
        var self = this;
        var payload = {
            store_id: this.storeId,
            device_id: this.getDeviceId(),
            order_type: type,
            customer_name: (deliveryInfo && deliveryInfo.name) ? deliveryInfo.name : null,
            customer_phone: (deliveryInfo && deliveryInfo.phone) ? deliveryInfo.phone : null,
            delivery_address: (deliveryInfo && deliveryInfo.address) ? deliveryInfo.address : null,
            instructions: (deliveryInfo && deliveryInfo.instructions) ? deliveryInfo.instructions : null
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/menu-cart/place-order', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                self.cartData = [];
                self.updateCartBar();
                // Show orders FAB after placing order
                var fabBtn = document.querySelector('.orders-fab-btn');
                if (fabBtn) fabBtn.style.display = '';
                self.showThankYou({
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

    // ===== DINE-IN FORM =====
    showDineInForm() {
        var self = this;
        var existing = document.querySelector('.delivery-form-overlay');
        if (existing) existing.remove();
        var existingSheet = document.querySelector('.delivery-form-section');
        if (existingSheet) existingSheet.remove();

        var html =
            '<div class="delivery-form-overlay"></div>' +
            '<div class="delivery-form-section">' +
                '<div class="delivery-form-header">' +
                    '<h3 class="delivery-form-header-title"><i class="bi bi-shop"></i> Dine-In Details</h3>' +
                    '<button class="delivery-form-close-btn"><i class="bi bi-x-lg"></i></button>' +
                '</div>' +
                '<div class="delivery-form-body">' +
                    '<div class="delivery-form-group">' +
                        '<label class="delivery-form-label"><i class="bi bi-phone"></i> Phone Number *</label>' +
                        '<div class="delivery-input-wrap">' +
                            '<span class="delivery-input-prefix">+91</span>' +
                            '<input type="tel" class="delivery-mobile-input" placeholder="Enter 10-digit number" maxlength="10" inputmode="numeric" value="' + self.getSavedPhone() + '">' +
                        '</div>' +
                        '<p class="delivery-field-error mobile-error" style="display:none;">Please enter a valid 10-digit mobile number</p>' +
                    '</div>' +
                '</div>' +
                '<div class="delivery-form-footer">' +
                    '<button class="delivery-checkout-btn"><i class="bi bi-check2-circle"></i> Place Order</button>' +
                '</div>' +
            '</div>';

        document.body.insertAdjacentHTML('beforeend', html);

        var overlay = document.querySelector('.delivery-form-overlay');
        var sheet = document.querySelector('.delivery-form-section');
        var closeBtn = sheet.querySelector('.delivery-form-close-btn');
        var checkoutBtn = sheet.querySelector('.delivery-checkout-btn');
        var mobileInput = sheet.querySelector('.delivery-mobile-input');
        var mobileError = sheet.querySelector('.mobile-error');

        mobileInput.addEventListener('input', function () {
            mobileInput.value = mobileInput.value.replace(/\D/g, '');
            mobileError.style.display = 'none';
        });

        requestAnimationFrame(function () {
            overlay.classList.add('active');
            sheet.classList.add('active');
        });
        document.body.style.overflow = 'hidden';

        function closeForm() {
            overlay.classList.remove('active');
            sheet.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(function () { overlay.remove(); sheet.remove(); }, 400);
        }

        closeBtn.addEventListener('click', closeForm);
        overlay.addEventListener('click', closeForm);

        checkoutBtn.addEventListener('click', function () {
            var phone = mobileInput.value.trim();
            if (!phone || phone.length < 10) {
                mobileError.style.display = 'block';
                return;
            }
            self.savePhone(phone);
            closeForm();
            setTimeout(function () {
                self.placeOrder('dine-in', { phone: phone });
            }, 400);
        });
    }

    // ===== DELIVERY FORM =====
    showDeliveryForm() {
        var self = this;
        var existing = document.querySelector('.delivery-form-overlay');
        if (existing) existing.remove();
        var existingSheet = document.querySelector('.delivery-form-section');
        if (existingSheet) existingSheet.remove();

        var itemCount = this.getCartCount();
        var orderTotal = this.getSubtotal() + this.deliveryFee;

        var html =
            '<div class="delivery-form-overlay"></div>' +
            '<div class="delivery-form-section">' +
                '<div class="delivery-form-header">' +
                    '<h3 class="delivery-form-header-title"><i class="bi bi-truck"></i> Delivery Details</h3>' +
                    '<button class="delivery-form-close-btn"><i class="bi bi-x-lg"></i></button>' +
                '</div>' +
                '<div class="delivery-form-body">' +
                    '<div class="delivery-form-summary">' +
                        '<span>' + itemCount + ' item' + (itemCount !== 1 ? 's' : '') + '</span>' +
                        '<strong>' + self.currency + orderTotal + '</strong>' +
                    '</div>' +
                    '<div class="delivery-form-group">' +
                        '<label class="delivery-form-label"><i class="bi bi-person"></i> Full Name *</label>' +
                        '<input type="text" class="delivery-name-input" placeholder="Enter your name">' +
                        '<p class="delivery-field-error name-error" style="display:none;">Please enter your name</p>' +
                    '</div>' +
                    '<div class="delivery-form-group">' +
                        '<label class="delivery-form-label"><i class="bi bi-phone"></i> Phone Number *</label>' +
                        '<div class="delivery-input-wrap">' +
                            '<span class="delivery-input-prefix">+91</span>' +
                            '<input type="tel" class="delivery-mobile-input" placeholder="Enter 10-digit number" maxlength="10" inputmode="numeric" value="' + self.getSavedPhone() + '">' +
                        '</div>' +
                        '<p class="delivery-field-error mobile-error" style="display:none;">Please enter a valid 10-digit mobile number</p>' +
                    '</div>' +
                    '<div class="delivery-form-group">' +
                        '<label class="delivery-form-label"><i class="bi bi-geo-alt"></i> Delivery Address *</label>' +
                        '<textarea class="delivery-address-input" placeholder="Enter full delivery address..." rows="3"></textarea>' +
                        '<p class="delivery-field-error address-error" style="display:none;">Please enter your delivery address</p>' +
                    '</div>' +
                    '<div class="delivery-form-group">' +
                        '<label class="delivery-form-label"><i class="bi bi-chat-left-text"></i> Special Instructions <span class="delivery-optional">(Optional)</span></label>' +
                        '<textarea class="delivery-notes-input" placeholder="Any special requests..." rows="2"></textarea>' +
                    '</div>' +
                '</div>' +
                '<div class="delivery-form-footer">' +
                    '<button class="delivery-checkout-btn"><i class="bi bi-check2-circle"></i> Place Order</button>' +
                '</div>' +
            '</div>';

        document.body.insertAdjacentHTML('beforeend', html);

        var overlay = document.querySelector('.delivery-form-overlay');
        var sheet = document.querySelector('.delivery-form-section');
        var closeBtn = sheet.querySelector('.delivery-form-close-btn');
        var checkoutBtn = sheet.querySelector('.delivery-checkout-btn');
        var nameInput = sheet.querySelector('.delivery-name-input');
        var mobileInput = sheet.querySelector('.delivery-mobile-input');
        var addressInput = sheet.querySelector('.delivery-address-input');
        var nameError = sheet.querySelector('.name-error');
        var mobileError = sheet.querySelector('.mobile-error');
        var addressError = sheet.querySelector('.address-error');

        mobileInput.addEventListener('input', function () {
            mobileInput.value = mobileInput.value.replace(/\D/g, '');
            mobileError.style.display = 'none';
        });
        nameInput.addEventListener('input', function () { nameError.style.display = 'none'; });
        addressInput.addEventListener('input', function () { addressError.style.display = 'none'; });

        requestAnimationFrame(function () {
            overlay.classList.add('active');
            sheet.classList.add('active');
        });
        document.body.style.overflow = 'hidden';

        function closeForm() {
            overlay.classList.remove('active');
            sheet.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(function () { overlay.remove(); sheet.remove(); }, 400);
        }

        closeBtn.addEventListener('click', closeForm);
        overlay.addEventListener('click', closeForm);

        checkoutBtn.addEventListener('click', function () {
            var name = nameInput.value.trim();
            var phone = mobileInput.value.trim();
            var address = addressInput.value.trim();
            var instructions = sheet.querySelector('.delivery-notes-input').value.trim();
            var valid = true;

            if (!name) { nameError.style.display = 'block'; valid = false; }
            if (!phone || phone.length < 10) { mobileError.style.display = 'block'; valid = false; }
            if (!address) { addressError.style.display = 'block'; valid = false; }
            if (!valid) return;

            self.savePhone(phone);
            closeForm();
            setTimeout(function () {
                self.placeOrder('delivery', { name: name, phone: phone, address: address, instructions: instructions });
            }, 400);
        });
    }

    // ===== THANK YOU =====
    showThankYou(order) {
        var existing = document.querySelector('.thankyou-overlay');
        if (existing) existing.remove();
        var existingSheet = document.querySelector('.thankyou-section');
        if (existingSheet) existingSheet.remove();

        var itemCount = order.item_count || 0;
        var orderId = order.order_id || 'ORD-' + Date.now().toString(36).toUpperCase().slice(-6);

        var html =
            '<div class="thankyou-overlay"></div>' +
            '<div class="thankyou-section">' +
                '<div class="thankyou-header">' +
                    '<h3 class="thankyou-header-title">Order Confirmed</h3>' +
                    '<button class="thankyou-close-btn"><i class="bi bi-x-lg"></i></button>' +
                '</div>' +
                '<div class="thankyou-body">' +
                    '<div class="thankyou-checkmark">' +
                        '<svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="25" fill="none"/><path fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>' +
                    '</div>' +
                    '<h2 class="thankyou-title">Thank You!</h2>' +
                    '<p class="thankyou-subtitle">Your order has been placed successfully</p>' +
                    '<div class="thankyou-order-info">' +
                        '<div class="thankyou-order-row"><span>Order ID</span><strong>' + orderId + '</strong></div>' +
                        '<div class="thankyou-order-row"><span>Items</span><strong>' + itemCount + ' item' + (itemCount !== 1 ? 's' : '') + '</strong></div>' +
                        '<div class="thankyou-order-row total"><span>Total</span><strong>' + this.currency + order.total + '</strong></div>' +
                    '</div>' +
                    '<p class="thankyou-message">Your food is being prepared with love. Please sit back and relax!</p>' +
                    (window.trackingPhone ? (
                        '<div class="thankyou-tracking">' +
                            '<p class="thankyou-tracking-label">For order tracking, call us at</p>' +
                            '<a href="tel:' + window.trackingPhone + '" class="thankyou-tracking-phone">' +
                                '<i class="bi bi-telephone-fill"></i> ' + window.trackingPhone +
                            '</a>' +
                        '</div>'
                    ) : '') +
                '</div>' +
                '<div class="thankyou-footer">' +
                    '<button class="thankyou-done-btn">Done <i class="bi bi-check2"></i></button>' +
                '</div>' +
            '</div>';

        document.body.insertAdjacentHTML('beforeend', html);

        var overlay = document.querySelector('.thankyou-overlay');
        var sheet = document.querySelector('.thankyou-section');
        var closeBtn = sheet.querySelector('.thankyou-close-btn');
        var doneBtn = sheet.querySelector('.thankyou-done-btn');

        requestAnimationFrame(function () {
            overlay.classList.add('active');
            sheet.classList.add('active');
        });
        document.body.style.overflow = 'hidden';

        function closePopup() {
            overlay.classList.remove('active');
            sheet.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(function () { overlay.remove(); sheet.remove(); }, 400);
        }

        closeBtn.addEventListener('click', closePopup);
        doneBtn.addEventListener('click', closePopup);
        overlay.addEventListener('click', closePopup);
    }
}

// ===== ORDERS VIEW (AJAX-based) =====
class OrdersView {
    constructor() {
        this.storeId = window.storeId || 0;
        this.overlay = document.querySelector('.orders-overlay');
        this.section = document.querySelector('.orders-section');
        this.closeBtn = document.querySelector('.orders-close-btn');
        this.body = document.querySelector('.orders-body');
        this.fabBtn = document.querySelector('.orders-fab-btn');
        this.currency = '\u20B9';

        if (!this.section) return;
        this.bindEvents();
        this.checkOrders();
    }

    getDeviceId() {
        return localStorage.getItem('menu_device_id') || '';
    }

    getSavedPhone() {
        return localStorage.getItem('menu_phone_' + this.storeId) || '';
    }

    bindEvents() {
        var self = this;
        if (this.fabBtn) this.fabBtn.addEventListener('click', function () { self.open(); });
        if (this.closeBtn) this.closeBtn.addEventListener('click', function () { self.close(); });
        if (this.overlay) this.overlay.addEventListener('click', function () { self.close(); });
    }

    checkOrders() {
        var self = this;
        var phone = this.getSavedPhone();
        var deviceId = this.getDeviceId();
        if (!deviceId && !phone) return;

        var url = '/menu-cart/orders?store_id=' + this.storeId + '&device_id=' + deviceId;
        if (phone) url += '&phone=' + encodeURIComponent(phone);

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                var orders = res.orders || [];
                if (self.fabBtn) {
                    self.fabBtn.style.display = orders.length > 0 ? '' : 'none';
                }
            }
        };
        xhr.send();
    }

    open() {
        this.loadAndRender();
        if (this.overlay) this.overlay.classList.add('active');
        if (this.section) this.section.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    close() {
        if (this.overlay) this.overlay.classList.remove('active');
        if (this.section) this.section.classList.remove('active');
        document.body.style.overflow = '';
    }

    loadAndRender() {
        if (!this.body) return;
        var self = this;

        this.body.innerHTML =
            '<div class="orders-empty">' +
                '<i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite"></i>' +
                '<p>Loading orders...</p>' +
            '</div>';

        var phone = this.getSavedPhone();
        var deviceId = this.getDeviceId();
        var url = '/menu-cart/orders?store_id=' + this.storeId + '&device_id=' + deviceId;
        if (phone) url += '&phone=' + encodeURIComponent(phone);

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                self.renderOrders(res.orders || []);
            } else {
                self.renderOrders([]);
            }
        };
        xhr.onerror = function () { self.renderOrders([]); };
        xhr.send();
    }

    renderOrders(orders) {
        if (!this.body) return;
        var self = this;

        if (orders.length === 0) {
            this.body.innerHTML =
                '<div class="orders-empty">' +
                    '<i class="bi bi-bag-x"></i>' +
                    '<p>No orders yet</p>' +
                    '<span>Your placed orders will appear here</span>' +
                '</div>';
            return;
        }

        var html = '';
        orders.forEach(function (order) {
            var statusClass = 'status-' + order.status;
            var statusLabel = order.status.charAt(0).toUpperCase() + order.status.slice(1);
            var itemCount = 0;

            var itemsHtml = '';
            order.items.forEach(function (item) {
                var sizeTag = item.size && item.size !== 'default'
                    ? ' <span class="order-item-size">(' + item.size + ')</span>'
                    : '';
                itemsHtml +=
                    '<div class="order-item-row">' +
                        '<span class="order-item-name">' + item.name + sizeTag + ' x' + item.qty + '</span>' +
                        '<span class="order-item-price">' + self.currency + (item.price * item.qty) + '</span>' +
                    '</div>';
                itemCount += item.qty;
            });

            var summaryHtml = '';
            summaryHtml += '<div class="order-summary-row"><span>Subtotal</span><span>' + self.currency + order.subtotal + '</span></div>';
            if (order.discount > 0) {
                summaryHtml += '<div class="order-summary-row order-summary-discount"><span>Discount</span><span>-' + self.currency + order.discount + '</span></div>';
            }
            if (order.delivery_fee > 0) {
                summaryHtml += '<div class="order-summary-row"><span>Delivery Fee</span><span>' + self.currency + order.delivery_fee + '</span></div>';
            }

            html +=
                '<div class="order-card">' +
                    '<div class="order-card-header">' +
                        '<span class="order-card-id">' + order.order_id + '</span>' +
                        '<span class="order-card-status ' + statusClass + '">' + statusLabel + '</span>' +
                    '</div>' +
                    '<div class="order-card-date"><i class="bi bi-clock"></i> ' + order.date + '</div>' +
                    '<div class="order-card-items-list">' + itemsHtml + '</div>' +
                    '<div class="order-card-summary">' + summaryHtml + '</div>' +
                    '<div class="order-card-footer">' +
                        '<span class="order-card-type">' +
                            (order.order_type === 'delivery' ? '<i class="bi bi-truck"></i> Delivery' : '<i class="bi bi-shop"></i> Dine-In') +
                        '</span>' +
                        '<span class="order-card-total">' + self.currency + order.total + '</span>' +
                    '</div>' +
                '</div>';
        });

        this.body.innerHTML = html;
    }
}

// ===== POWERED BY MINI HEADER =====
function initPoweredByHeader(options) {
    options = options || {};
    var brandName = window.poweredBy || 'Qrscop';
    var icon = options.icon || 'bi-lightning-charge-fill';
    var baseUrl = (function() {
        var scripts = document.querySelectorAll('script[src*="menu-templates/js/"]');
        if (scripts.length > 0) {
            var src = scripts[0].src;
            return src.substring(0, src.indexOf('/public/assets/menu-templates/'));
        }
        return '';
    })();
    var logo = options.logo || (baseUrl + '/public/assets/landing/img/logo.svg');
    var link = options.link || '/';
    var position = options.position || 'top';

    if (document.querySelector('.powered-by-header')) return;

    var logoHTML = logo
        ? '<img class="powered-logo" src="' + logo + '" alt="' + brandName + '">'
        : '<span class="powered-icon"><i class="bi ' + icon + '"></i></span>';

    // var innerHTML = logoHTML + '<span>Powered by</span><strong>' + brandName + '</strong>';
    var innerHTML ='<span>Powered by</span><strong>' + brandName + '</strong>';


    var headerHTML = link
        ? '<div class="powered-by-header"><a href="' + link + '" target="_blank">' + innerHTML + '</a></div>'
        : '<div class="powered-by-header">' + innerHTML + '</div>';

    if (position === 'top') {
        document.body.insertAdjacentHTML('afterbegin', headerHTML);
    } else {
        document.body.insertAdjacentHTML('beforeend', headerHTML);
    }
}

// ===== BANNER POPUP =====
function getTabId() {
    var tabId = window.name;
    if (!tabId || !tabId.startsWith('tab_')) {
        tabId = 'tab_' + Date.now() + '_' + Math.random().toString(36).slice(2);
        window.name = tabId;
    }
    return tabId;
}

function initBannerPopup() {
    var overlay = document.querySelector('.banner-popup-overlay');
    if (!overlay) return;

    var tabId = getTabId();
    if (localStorage.getItem('bannerClosed_' + tabId)) return;

    var closeBtn = overlay.querySelector('.banner-popup-close');

    setTimeout(function () {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }, 500);

    function closeBanner() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        localStorage.setItem('bannerClosed_' + tabId, '1');
    }

    closeBtn.addEventListener('click', closeBanner);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeBanner();
    });
}

// ===== FLOAT ANIMATION KEYFRAMES =====
var cartStyles = document.createElement('style');
cartStyles.textContent = '@keyframes cartFloatUp{0%{opacity:1;transform:translateY(0)}100%{opacity:0;transform:translateY(-30px)}}@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}';
document.head.appendChild(cartStyles);

// ===== TODAY'S SPECIALS POPUP =====
function initSpecialsPopup() {
    var overlay = document.querySelector('.specials-popup-overlay');
    var popup = document.querySelector('.specials-popup');
    var closeBtn = document.querySelector('.specials-popup-close');
    var body = document.querySelector('.specials-popup-body');

    if (!popup || !overlay) return;

    function closeSpecials() {
        overlay.classList.remove('active');
        popup.classList.remove('active');
        document.body.style.overflow = '';
        // Reset home as active in bottom nav
        var navItems = document.querySelectorAll('.bottom-nav-item');
        navItems.forEach(function (n) { n.classList.remove('active'); });
        var homeBtn = document.querySelector('.bottom-nav-item[data-action="home"]');
        if (homeBtn) homeBtn.classList.add('active');
    }

    if (closeBtn) closeBtn.addEventListener('click', closeSpecials);
    overlay.addEventListener('click', closeSpecials);

    // Populate specials from todaySpecialIds (set by backend)
    var items = window.menuItemsData || window.s14MenuItems || [];
    var specialIds = window.todaySpecialIds || [];
    var specials = specialIds.length > 0
        ? items.filter(function (item) { return specialIds.indexOf(item.id) !== -1; })
        : [];

    if (specials.length === 0) return; // Keep empty state

    var currency = '\u20B9';
    var html = '';
    specials.forEach(function (item, i) {
        var off = Math.round(((item.mrp - item.price) / item.mrp) * 100);
        html += '<div class="specials-card" data-item-index="' + items.indexOf(item) + '">' +
            '<div class="specials-card-img">' +
                '<img src="' + item.img + '" alt="' + item.name + '">' +
                '<div class="specials-card-badge"><div class="' + (item.isVeg ? 'veg-dot' : 'non-veg-dot') + '"></div></div>' +
            '</div>' +
            '<div class="specials-card-info">' +
                '<h4 class="specials-card-name">' + item.name + '</h4>' +
                '<p class="specials-card-desc">' + (item.desc || '') + '</p>' +
                '<div class="specials-card-bottom">' +
                    '<div class="specials-card-pricing">' +
                        '<span class="specials-card-price">' + currency + item.price + '</span>' +
                        '<span class="specials-card-mrp"><s>' + currency + item.mrp + '</s></span>' +
                        '<span class="specials-card-off">' + off + '% OFF</span>' +
                    '</div>' +
                    '<button class="specials-card-add" data-item-index="' + items.indexOf(item) + '"><i class="bi bi-plus"></i></button>' +
                '</div>' +
            '</div>' +
        '</div>';
    });

    body.innerHTML = html;

    // Click on card opens item detail
    body.querySelectorAll('.specials-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (e.target.closest('.specials-card-add')) return;
            var idx = parseInt(card.dataset.itemIndex);
            if (!isNaN(idx) && window.openItemDetail) {
                closeSpecials();
                window.openItemDetail(idx);
            }
        });
    });

    // Add button on specials card
    body.querySelectorAll('.specials-card-add').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var idx = parseInt(btn.dataset.itemIndex);
            if (!isNaN(idx) && window.menuCart) {
                var item = items[idx];
                if (item && item.foodVariations && item.foodVariations.length > 0) {
                    if (window.openItemDetail) window.openItemDetail(idx);
                } else if (item) {
                    var origHTML = btn.innerHTML;
                    window.menuCart.addToCart({
                        cartKey: idx + '-default',
                        index: idx,
                        itemId: item.id,
                        name: item.name,
                        price: item.price,
                        mrp: item.mrp,
                        img: item.img,
                        isVeg: item.isVeg,
                        size: 'default',
                        qty: 1
                    }, function () {
                        btn.innerHTML = '<i class="bi bi-check"></i>';
                        btn.style.background = '#16a34a';
                        setTimeout(function () {
                            btn.innerHTML = origHTML;
                            btn.style.background = '';
                        }, 800);
                    });
                }
            }
        });
    });
}

// ===== BOTTOM NAVIGATION =====
function initBottomNav() {
    var bottomNav = document.querySelector('.bottom-nav');
    if (!bottomNav) return;

    var navItems = bottomNav.querySelectorAll('.bottom-nav-item');

    // Scroll hide/show bottom nav
    var lastNavScrollY = window.scrollY;
    window.addEventListener('scroll', function () {
        if (window.scrollY > lastNavScrollY && window.scrollY > 100) {
            bottomNav.classList.add('hidden');
        } else {
            bottomNav.classList.remove('hidden');
        }
        lastNavScrollY = window.scrollY;
    });

    navItems.forEach(function (item) {
        item.addEventListener('click', function () {
            var action = this.dataset.action;

            if (action === 'home') {
                navItems.forEach(function (n) { n.classList.remove('active'); });
                item.classList.add('active');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (action === 'specials') {
                navItems.forEach(function (n) { n.classList.remove('active'); });
                item.classList.add('active');
                // Open specials popup
                var specialsOverlay = document.querySelector('.specials-popup-overlay');
                var specialsPopup = document.querySelector('.specials-popup');
                if (specialsOverlay && specialsPopup) {
                    specialsOverlay.classList.add('active');
                    specialsPopup.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            } else if (action === 'cart') {
                if (window.menuCart) {
                    window.menuCart.openCart();
                }
            } else if (action === 'orders') {
                if (window.ordersView) {
                    window.ordersView.open();
                }
            }
        });
    });
}

// ===== AUTO-INITIALIZE =====
document.addEventListener('DOMContentLoaded', function () {
    initPoweredByHeader();
    initBannerPopup();

    if (document.querySelector('.cart-bar') || document.querySelector('.cart-section')) {
        window.menuCart = new MenuCart();
    }

    if (document.querySelector('.orders-section')) {
        window.ordersView = new OrdersView();
    }

    initBottomNav();
    initSpecialsPopup();
});
