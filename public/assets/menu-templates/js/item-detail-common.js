// ===== ITEM DETAIL POPUP COMMON JS =====

class ItemDetailPopup {
    constructor(menuItemsData) {
        this.menuItemsData = menuItemsData;
        this.currentItem = null;
        this.currentQty = 1;
        this.currentSize = 'default';
        this.currentImageIndex = 0;
        this.itemImages = [];
        this.variationData = null;
        this.serverData = null;
        this.itemPrice = 0;
        this.itemDiscount = 0;
        this.itemDiscountType = 'percent';

        this.createPopupHTML();
        this.cacheElements();
        this.bindEvents();
    }

    createPopupHTML() {
        const popupHTML = `
            <div class="item-detail-overlay" id="item-detail-overlay"></div>
            <div class="item-detail-popup" id="item-detail-popup">
                <div class="item-detail-header">
                    <div class="item-image-carousel" id="item-carousel">
                        <div class="item-image-track" id="item-image-track"></div>
                        <div class="item-image-dots" id="item-image-dots"></div>
                        <button class="item-image-nav prev" id="img-prev"><i class="bi bi-chevron-left"></i></button>
                        <button class="item-image-nav next" id="img-next"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="item-detail-badge" id="detail-badge"></div>
                    <button class="item-detail-close" id="detail-close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="item-detail-content">
                    <h2 class="item-detail-name" id="detail-name"></h2>
                    <p class="item-detail-desc" id="detail-desc"></p>
                    <div class="item-detail-price-row">
                        <span class="item-detail-price" id="detail-price"></span>
                        <span class="item-detail-mrp" id="detail-mrp"></span>
                        <span class="item-detail-discount" id="detail-discount"></span>
                    </div>
                    <div class="variations-section" id="variations-section" style="display:none;">
                        <h3 class="variations-title" id="variations-title"><i class="bi bi-sliders"></i> Select Size</h3>
                        <div class="variation-options" id="variation-options"></div>
                    </div>
                </div>
                <div class="item-detail-footer">
                    <div class="qty-selector">
                        <button class="qty-btn-popup" id="qty-decrease"><i class="bi bi-dash"></i></button>
                        <span class="qty-value" id="qty-value">1</span>
                        <button class="qty-btn-popup" id="qty-increase"><i class="bi bi-plus"></i></button>
                    </div>
                    <button class="add-to-cart-btn" id="add-to-cart-popup">
                        <span>Add to Cart</span>
                        <span id="total-price"></span>
                    </button>
                </div>
            </div>
            <div class="item-detail-loader" id="item-detail-loader">
                <div class="item-detail-loader-spinner"><div></div><div></div><div></div></div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', popupHTML);
    }

    cacheElements() {
        this.overlay = document.getElementById('item-detail-overlay');
        this.popup = document.getElementById('item-detail-popup');
        this.closeBtn = document.getElementById('detail-close');
        this.imageTrack = document.getElementById('item-image-track');
        this.imageDots = document.getElementById('item-image-dots');
        this.imgPrevBtn = document.getElementById('img-prev');
        this.imgNextBtn = document.getElementById('img-next');
        this.badge = document.getElementById('detail-badge');
        this.nameEl = document.getElementById('detail-name');
        this.descEl = document.getElementById('detail-desc');
        this.priceEl = document.getElementById('detail-price');
        this.mrpEl = document.getElementById('detail-mrp');
        this.discountEl = document.getElementById('detail-discount');
        this.variationsSection = document.getElementById('variations-section');
        this.variationsTitle = document.getElementById('variations-title');
        this.variationOptionsEl = document.getElementById('variation-options');
        this.qtyValue = document.getElementById('qty-value');
        this.qtyDecrease = document.getElementById('qty-decrease');
        this.qtyIncrease = document.getElementById('qty-increase');
        this.addToCartBtn = document.getElementById('add-to-cart-popup');
        this.totalPriceEl = document.getElementById('total-price');
        this.loader = document.getElementById('item-detail-loader');
    }

    bindEvents() {
        // Close events
        this.closeBtn.addEventListener('click', () => this.close());
        this.overlay.addEventListener('click', () => this.close());

        // Image navigation
        this.imgPrevBtn.addEventListener('click', () => this.goToImage(this.currentImageIndex - 1));
        this.imgNextBtn.addEventListener('click', () => this.goToImage(this.currentImageIndex + 1));

        // Quantity controls
        this.qtyDecrease.addEventListener('click', () => {
            if (this.currentQty > 1) {
                this.currentQty--;
                this.qtyValue.textContent = this.currentQty;
                this.updateTotalPrice();
            }
        });

        this.qtyIncrease.addEventListener('click', () => {
            if (this.currentQty < 20) {
                this.currentQty++;
                this.qtyValue.textContent = this.currentQty;
                this.updateTotalPrice();
            }
        });

        // Add to cart
        this.addToCartBtn.addEventListener('click', () => this.addToCart());
    }

    open(itemData, mainImage) {
        this.currentItem = itemData;
        this.currentQty = 1;
        this.currentSize = 'default';
        this.variationData = null;
        this.serverData = null;

        // Reset popup
        this.nameEl.textContent = '';
        this.descEl.textContent = '';
        this.priceEl.textContent = '';
        this.mrpEl.innerHTML = '';
        this.discountEl.textContent = '';
        this.totalPriceEl.textContent = '';
        this.qtyValue.textContent = '1';
        this.variationsSection.style.display = 'none';
        this.renderImages([]);

        // Show loader
        this.loader.classList.add('active');

        // Fetch item detail from server
        var self = this;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/menu-item/detail?item_id=' + itemData.id, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            self.loader.classList.remove('active');
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                self.serverData = data;
                self.itemPrice = data.price;
                self.itemDiscount = data.discount || 0;
                self.itemDiscountType = data.discount_type || 'percent';

                // Build image list
                var allImages = [];
                if (data.image) allImages.push(data.image);
                if (data.images && data.images.length > 0) {
                    data.images.forEach(function (img) {
                        if (allImages.indexOf(img) === -1) allImages.push(img);
                    });
                }
                self.renderImages(allImages);

                // Set badge
                self.badge.className = 'item-detail-badge ' + (data.veg == 1 ? 'veg' : 'non-veg');

                // Set details
                self.nameEl.textContent = data.name;
                self.descEl.textContent = data.description || '';
                self.qtyValue.textContent = self.currentQty;

                // Render variations
                self.renderVariations(data.food_variations || [], data.price);
                self.updatePriceDisplay();

                // Show popup
                self.overlay.classList.add('active');
                self.popup.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        };
        xhr.onerror = function () {
            self.loader.classList.remove('active');
        };
        xhr.send();
    }

    close() {
        this.overlay.classList.remove('active');
        this.popup.classList.remove('active');
        document.body.style.overflow = '';
    }

    renderImages(images) {
        this.imageTrack.innerHTML = '';
        this.imageDots.innerHTML = '';
        this.currentImageIndex = 0;

        if (!images || images.length === 0) {
            this.imageTrack.innerHTML = '<div class="item-image-slide"><img src="" alt=""></div>';
            this.imgPrevBtn.style.display = 'none';
            this.imgNextBtn.style.display = 'none';
            return;
        }

        this.itemImages = images;

        images.forEach(function (src, i) {
            var slide = document.createElement('div');
            slide.className = 'item-image-slide';
            var img = document.createElement('img');
            img.src = src;
            img.alt = 'Item image ' + (i + 1);
            slide.appendChild(img);
            this.imageTrack.appendChild(slide);

            if (images.length > 1) {
                var dot = document.createElement('div');
                dot.className = 'item-image-dot' + (i === 0 ? ' active' : '');
                dot.dataset.index = i;
                dot.addEventListener('click', () => this.goToImage(i));
                this.imageDots.appendChild(dot);
            }
        }.bind(this));

        if (images.length <= 1) {
            this.imgPrevBtn.style.display = 'none';
            this.imgNextBtn.style.display = 'none';
        } else {
            this.imgPrevBtn.style.display = '';
            this.imgNextBtn.style.display = '';
        }

        this.imageTrack.style.transform = 'translateX(0)';
    }

    goToImage(index) {
        if (index < 0) index = this.itemImages.length - 1;
        if (index >= this.itemImages.length) index = 0;
        this.currentImageIndex = index;
        this.imageTrack.style.transform = 'translateX(-' + (this.currentImageIndex * 100) + '%)';

        // Update dots
        this.imageDots.querySelectorAll('.item-image-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === this.currentImageIndex);
        });
    }

    renderVariations(variations, basePrice) {
        var variation = null;
        for (var i = 0; i < variations.length; i++) {
            if (variations[i].values && variations[i].values.length > 0) {
                variation = variations[i];
                break;
            }
        }
        this.variationData = variation;

        if (variation) {
            this.variationsSection.style.display = '';
            this.variationsTitle.innerHTML = '<i class="bi bi-sliders"></i> ' + (variation.name ? ('Choose ' + variation.name) : 'Select Size');
            this.variationOptionsEl.innerHTML = '';

            var self = this;
            var hasDiscount = this.itemDiscount > 0;

            // Default option
            var defaultDiscounted = this.calculateDiscountedPrice(basePrice);
            var defaultDiv = document.createElement('div');
            defaultDiv.className = 'variation-option active';
            defaultDiv.dataset.variation = 'default';
            var defaultPriceHTML = '';
            if (hasDiscount && defaultDiscounted < basePrice) {
                defaultPriceHTML = '<s class="variation-mrp">\u20B9' + basePrice + '</s> \u20B9' + defaultDiscounted;
            } else {
                defaultPriceHTML = '\u20B9' + basePrice;
            }
            defaultDiv.innerHTML =
                '<div class="variation-left">' +
                    '<div class="variation-radio"></div>' +
                    '<span class="variation-name">Default</span>' +
                '</div>' +
                '<span class="variation-price">' + defaultPriceHTML + '</span>';
            defaultDiv.addEventListener('click', function () {
                self.variationOptionsEl.querySelectorAll('.variation-option').forEach(function (o) { o.classList.remove('active'); });
                defaultDiv.classList.add('active');
                self.currentSize = 'default';
                self.updatePriceDisplay();
            });
            this.variationOptionsEl.appendChild(defaultDiv);

            variation.values.forEach(function (val) {
                var optPrice = parseFloat(val.optionPrice);
                var discountedPrice = self.calculateDiscountedPrice(optPrice);
                var optDiv = document.createElement('div');
                optDiv.className = 'variation-option';
                optDiv.dataset.variation = val.label;
                var priceHTML = '';
                if (hasDiscount && discountedPrice < optPrice) {
                    priceHTML = '<s class="variation-mrp">\u20B9' + optPrice + '</s> \u20B9' + discountedPrice;
                } else {
                    priceHTML = '\u20B9' + optPrice;
                }
                optDiv.innerHTML =
                    '<div class="variation-left">' +
                        '<div class="variation-radio"></div>' +
                        '<span class="variation-name">' + val.label + '</span>' +
                    '</div>' +
                    '<span class="variation-price">' + priceHTML + '</span>';
                optDiv.addEventListener('click', function () {
                    self.variationOptionsEl.querySelectorAll('.variation-option').forEach(function (o) { o.classList.remove('active'); });
                    optDiv.classList.add('active');
                    self.currentSize = val.label;
                    self.updatePriceDisplay();
                });
                self.variationOptionsEl.appendChild(optDiv);
            });

            this.currentSize = 'default';
        } else {
            this.variationsSection.style.display = 'none';
            this.currentSize = 'default';
        }
    }

    getVariationPrice() {
        if (!this.variationData) return this.itemPrice;
        var val = this.variationData.values.find(function (v) { return v.label === this.currentSize; }.bind(this));
        return val ? parseFloat(val.optionPrice) : this.itemPrice;
    }

    calculateDiscountedPrice(price) {
        if (this.itemDiscount <= 0) return price;
        if (this.itemDiscountType === 'percent') {
            return Math.round(price - (price * this.itemDiscount / 100));
        }
        return Math.max(0, price - this.itemDiscount);
    }

    updatePriceDisplay() {
        var basePrice = this.getVariationPrice();
        var discounted = this.calculateDiscountedPrice(basePrice);

        if (this.itemDiscount > 0 && discounted < basePrice) {
            this.mrpEl.innerHTML = '<s>\u20B9' + basePrice + '</s>';
            this.priceEl.textContent = '\u20B9' + discounted;
            if (this.itemDiscountType === 'percent') {
                this.discountEl.textContent = this.itemDiscount + '% OFF';
            } else {
                this.discountEl.textContent = '\u20B9' + this.itemDiscount + ' OFF';
            }
        } else {
            this.priceEl.textContent = '\u20B9' + basePrice;
            this.mrpEl.innerHTML = '';
            this.discountEl.textContent = '';
            discounted = basePrice;
        }

        this.totalPriceEl.textContent = '\u20B9' + (discounted * this.currentQty);
    }

    updateTotalPrice() {
        var basePrice = this.getVariationPrice();
        var discounted = this.calculateDiscountedPrice(basePrice);
        this.totalPriceEl.textContent = '\u20B9' + (discounted * this.currentQty);
    }

    addToCart() {
        if (!this.serverData) return;

        var mrpPrice = this.getVariationPrice();
        var price = this.calculateDiscountedPrice(mrpPrice);
        var self = this;

        if (window.menuCart) {
            var dataIndex = this.currentItem ? (this.currentItem.index || 0) : 0;
            window.menuCart.addToCart({
                cartKey: dataIndex + '-' + this.currentSize,
                index: dataIndex,
                itemId: this.serverData.id || 0,
                name: this.serverData.name,
                price: price,
                mrp: mrpPrice,
                img: this.serverData.image || '',
                isVeg: this.serverData.veg == 1,
                size: this.currentSize,
                qty: this.currentQty
            }, function () {
                self.addToCartBtn.classList.remove('added');
                self.addToCartBtn.querySelector('span:first-child').textContent = 'Add to Cart';
                self.close();
            });
        }

        // Button feedback
        this.addToCartBtn.classList.add('added');
        this.addToCartBtn.querySelector('span:first-child').textContent = 'Added!';
    }
}

// Global instance
let itemDetailPopup = null;

// Function to initialize item detail popup
function initItemDetailPopup(menuItemsData) {
    itemDetailPopup = new ItemDetailPopup(menuItemsData);
    return itemDetailPopup;
}

// Function to open item detail (can be called from anywhere)
function openItemDetail(itemData, mainImage) {
    if (itemDetailPopup) {
        itemDetailPopup.open(itemData, mainImage);
    }
}
