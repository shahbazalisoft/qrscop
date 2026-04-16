// ===== ITEM DETAIL POPUP COMMON JS =====

class ItemDetailPopup {
    constructor(menuItemsData) {
        this.menuItemsData = menuItemsData;
        this.currentItem = null;
        this.currentQty = 1;
        this.currentVariation = 'quarter';
        this.currentPriceFactor = 0.5;
        this.currentImageIndex = 0;
        this.itemImages = [];

        // Sample additional images
        this.additionalImages = [
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop',
            'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&h=400&fit=crop',
            'https://images.unsplash.com/photo-1482049016688-6d6f5f1a0b1f?w=600&h=400&fit=crop'
        ];

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
                    <p class="item-detail-desc" id="detail-desc">Delicious dish prepared with fresh ingredients and authentic spices.</p>
                    <div class="item-detail-price-row">
                        <span class="item-detail-price" id="detail-price"></span>
                        <span class="item-detail-mrp" id="detail-mrp"></span>
                        <span class="item-detail-discount" id="detail-discount"></span>
                    </div>
                    <div class="variations-section">
                        <h3 class="variations-title"><i class="bi bi-sliders"></i> Select Size</h3>
                        <div class="variation-options" id="variation-options">
                            <div class="variation-option active" data-variation="quarter" data-price-factor="0.5">
                                <div class="variation-left">
                                    <div class="variation-radio"></div>
                                    <span class="variation-name">Quarter</span>
                                </div>
                                <span class="variation-price" id="price-quarter"></span>
                            </div>
                            <div class="variation-option" data-variation="half" data-price-factor="0.75">
                                <div class="variation-left">
                                    <div class="variation-radio"></div>
                                    <span class="variation-name">Half</span>
                                </div>
                                <span class="variation-price" id="price-half"></span>
                            </div>
                            <div class="variation-option" data-variation="full" data-price-factor="1">
                                <div class="variation-left">
                                    <div class="variation-radio"></div>
                                    <span class="variation-name">Full</span>
                                </div>
                                <span class="variation-price" id="price-full"></span>
                            </div>
                        </div>
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
        this.variationOptions = document.querySelectorAll('.variation-option');
        this.qtyValue = document.getElementById('qty-value');
        this.qtyDecrease = document.getElementById('qty-decrease');
        this.qtyIncrease = document.getElementById('qty-increase');
        this.addToCartBtn = document.getElementById('add-to-cart-popup');
        this.totalPriceEl = document.getElementById('total-price');
    }

    bindEvents() {
        // Close events
        this.closeBtn.addEventListener('click', () => this.close());
        this.overlay.addEventListener('click', () => this.close());

        // Image navigation
        this.imgPrevBtn.addEventListener('click', () => this.goToImage(this.currentImageIndex - 1));
        this.imgNextBtn.addEventListener('click', () => this.goToImage(this.currentImageIndex + 1));

        // Variation selection
        this.variationOptions.forEach(option => {
            option.addEventListener('click', () => {
                this.variationOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                this.currentVariation = option.dataset.variation;
                this.currentPriceFactor = parseFloat(option.dataset.priceFactor);
                this.updateTotalPrice();
            });
        });

        // Quantity controls
        this.qtyDecrease.addEventListener('click', () => {
            if (this.currentQty > 1) {
                this.currentQty--;
                this.qtyValue.textContent = this.currentQty;
                this.updateTotalPrice();
            }
        });

        this.qtyIncrease.addEventListener('click', () => {
            if (this.currentQty < 10) {
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
        this.currentVariation = 'quarter';
        this.currentPriceFactor = 0.5;

        // Setup image carousel
        this.setupImageCarousel(mainImage);

        // Set badge
        this.badge.className = 'item-detail-badge ' + (itemData.isVeg ? 'veg' : 'non-veg');

        // Set details
        this.nameEl.textContent = itemData.name;
        this.priceEl.textContent = '₹' + itemData.price;
        this.mrpEl.textContent = '₹' + itemData.mrp;

        const discountPercent = Math.round((1 - itemData.price / itemData.mrp) * 100);
        this.discountEl.textContent = discountPercent + '% OFF';

        // Set variation prices
        document.getElementById('price-quarter').textContent = '₹' + Math.round(itemData.price * 0.5);
        document.getElementById('price-half').textContent = '₹' + Math.round(itemData.price * 0.75);
        document.getElementById('price-full').textContent = '₹' + itemData.price;

        // Reset variations
        this.variationOptions.forEach(opt => opt.classList.remove('active'));
        document.querySelector('[data-variation="quarter"]').classList.add('active');

        // Reset quantity
        this.qtyValue.textContent = '1';
        this.updateTotalPrice();

        // Show popup
        this.overlay.classList.add('active');
        this.popup.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.overlay.classList.remove('active');
        this.popup.classList.remove('active');
        document.body.style.overflow = '';
    }

    setupImageCarousel(mainImage) {
        this.itemImages = [mainImage, ...this.additionalImages];
        this.currentImageIndex = 0;

        // Build carousel HTML
        this.imageTrack.innerHTML = this.itemImages.map(img => `
            <div class="item-image-slide">
                <img src="${img}" alt="Item image">
            </div>
        `).join('');

        // Build dots
        this.imageDots.innerHTML = this.itemImages.map((_, i) => `
            <div class="item-image-dot ${i === 0 ? 'active' : ''}" data-index="${i}"></div>
        `).join('');

        // Reset position
        this.imageTrack.style.transform = 'translateX(0)';

        // Add dot click events
        this.imageDots.querySelectorAll('.item-image-dot').forEach(dot => {
            dot.addEventListener('click', () => {
                this.goToImage(parseInt(dot.dataset.index));
            });
        });
    }

    goToImage(index) {
        if (index < 0) index = this.itemImages.length - 1;
        if (index >= this.itemImages.length) index = 0;
        this.currentImageIndex = index;
        this.imageTrack.style.transform = `translateX(-${this.currentImageIndex * 100}%)`;

        // Update dots
        this.imageDots.querySelectorAll('.item-image-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === this.currentImageIndex);
        });
    }

    updateTotalPrice() {
        const basePrice = this.currentItem ? this.currentItem.price : 0;
        const total = Math.round(basePrice * this.currentPriceFactor * this.currentQty);
        this.totalPriceEl.textContent = '₹' + total;
    }

    addToCart() {
        if (this.currentItem && window.menuCart) {
            const basePrice = this.currentItem.price;
            const itemPrice = Math.round(basePrice * this.currentPriceFactor);
            const itemName = this.currentItem.name + ' (' + this.currentVariation.charAt(0).toUpperCase() + this.currentVariation.slice(1) + ')';

            window.menuCart.addItem(itemName, itemPrice, this.currentQty, this.currentItem.isVeg);

            // Sync add buttons if function exists
            if (typeof syncAddButtonsWithCart === 'function') {
                syncAddButtonsWithCart();
            }

            // Button feedback
            this.addToCartBtn.classList.add('added');
            this.addToCartBtn.querySelector('span:first-child').textContent = 'Added!';

            setTimeout(() => {
                this.addToCartBtn.classList.remove('added');
                this.addToCartBtn.querySelector('span:first-child').textContent = 'Add to Cart';
                this.close();
            }, 1000);
        }
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
