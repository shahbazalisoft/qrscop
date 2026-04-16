// ===== COMMON CART FUNCTIONALITY =====
// This file contains shared cart logic for all menu templates

class MenuCart {
    constructor(options = {}) {
        // Default options
        this.options = {
            deliveryFee: 40,
            currency: '₹',
            ...options
        };

        // Cart data
        this.cartData = [];

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

        // Initialize
        this.init();
    }

    init() {
        this.bindEvents();
        this.initScrollBehavior();
        this.updateCartUI();
    }

    bindEvents() {
        // Open cart
        if (this.viewCartBtn) {
            this.viewCartBtn.addEventListener('click', () => this.openCart());
        }

        // Close cart
        if (this.cartCloseBtn) {
            this.cartCloseBtn.addEventListener('click', () => this.closeCart());
        }

        if (this.cartOverlay) {
            this.cartOverlay.addEventListener('click', () => this.closeCart());
        }

        // Fullscreen toggle
        if (this.cartFullscreenBtn) {
            this.cartFullscreenBtn.addEventListener('click', () => this.toggleFullscreen());
        }

        // Checkout button
        if (this.checkoutBtn) {
            this.checkoutBtn.addEventListener('click', () => this.handleCheckout());
        }

        // Add to cart buttons
        this.bindAddButtons();
    }

    bindAddButtons() {
        const addBtns = document.querySelectorAll('.add-btn');
        addBtns.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleAddToCart(e, btn));
        });
    }

    initScrollBehavior() {
        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            if (this.cartBar) {
                if (window.scrollY > lastScrollY && window.scrollY > 100) {
                    // Scrolling down - hide cart bar
                    this.cartBar.classList.add('hidden');
                } else {
                    // Scrolling up - show cart bar
                    this.cartBar.classList.remove('hidden');
                }
            }
            lastScrollY = window.scrollY;
        });
    }

    openCart() {
        if (this.cartSection) {
            this.cartSection.classList.add('active');
        }
        if (this.cartOverlay) {
            this.cartOverlay.classList.add('active');
        }
        document.body.style.overflow = 'hidden';
        this.renderCart();
    }

    closeCart() {
        if (this.cartSection) {
            this.cartSection.classList.remove('active');
            this.cartSection.classList.remove('fullscreen');
        }
        if (this.cartOverlay) {
            this.cartOverlay.classList.remove('active');
        }
        document.body.style.overflow = '';
    }

    toggleFullscreen() {
        if (this.cartSection) {
            this.cartSection.classList.toggle('fullscreen');
        }
    }

    handleAddToCart(e, btn) {
        e.stopPropagation();

        const menuItem = btn.closest('.menu-item');
        if (!menuItem) return;

        const nameEl = menuItem.querySelector('.item-name, .menu-item-name, h3, h4');
        const priceEl = menuItem.querySelector('.price, .item-price, .menu-item-price');

        if (!nameEl || !priceEl) return;

        const name = nameEl.textContent.trim();
        const priceText = priceEl.textContent;
        const price = parseInt(priceText.replace(/[^\d]/g, ''));

        // Check if item already in cart
        const existingItem = this.cartData.find(item => item.name === name);
        if (existingItem) {
            existingItem.qty++;
        } else {
            const newId = this.cartData.length > 0 ? Math.max(...this.cartData.map(i => i.id)) + 1 : 1;
            this.cartData.push({
                id: newId,
                name: name,
                price: price,
                qty: 1
            });
        }

        this.updateCartUI();
        this.animateAddButton(btn);
        this.createFloatAnimation(btn, '+1');
    }

    animateAddButton(btn) {
        const originalHTML = btn.innerHTML;
        const originalBg = btn.style.background;

        btn.innerHTML = '<i class="bi bi-check"></i>';
        btn.style.background = '#00a651';
        btn.style.transform = 'scale(1.2)';

        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = originalBg;
            btn.style.transform = '';
        }, 500);
    }

    createFloatAnimation(element, text) {
        const floater = document.createElement('span');
        floater.textContent = text;
        floater.style.cssText = `
            position: fixed;
            color: var(--cart-accent, #c9a962);
            font-size: 1rem;
            font-weight: bold;
            pointer-events: none;
            z-index: 9999;
            animation: cartFloatUp 0.7s ease-out forwards;
        `;

        const rect = element.getBoundingClientRect();
        floater.style.left = rect.left + rect.width / 2 - 10 + 'px';
        floater.style.top = rect.top + 'px';

        document.body.appendChild(floater);
        setTimeout(() => floater.remove(), 700);
    }

    renderCart() {
        if (!this.cartItemsList) return;

        if (this.cartData.length === 0) {
            this.cartItemsList.innerHTML = `
                <div class="cart-empty">
                    <i class="bi bi-cart-x"></i>
                    <p>Your cart is empty</p>
                </div>
            `;
        } else {
            this.cartItemsList.innerHTML = this.cartData.map(item => `
                <div class="cart-item" data-id="${item.id}">
                    <div class="cart-item-type ${item.isVeg ? 'veg' : 'non-veg'}"></div>
                    <div class="cart-item-details">
                        <h4 class="cart-item-name">${item.name}</h4>
                        <span class="cart-item-price">${this.options.currency}${item.price * item.qty}</span>
                    </div>
                    <div class="cart-item-controls">
                        <button class="qty-btn minus ${item.qty === 1 ? 'delete' : ''}" data-id="${item.id}">
                            <i class="bi ${item.qty === 1 ? 'bi-trash' : 'bi-dash'}"></i>
                        </button>
                        <span class="cart-item-qty">${item.qty}</span>
                        <button class="qty-btn plus" data-id="${item.id}">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
            `).join('');

            // Bind quantity button events
            this.cartItemsList.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = parseInt(btn.dataset.id);
                    if (btn.classList.contains('plus')) {
                        this.updateQty(id, 1);
                    } else {
                        this.updateQty(id, -1);
                    }
                });
            });
        }

        this.updateCartSummary();
    }

    updateQty(id, change) {
        const item = this.cartData.find(i => i.id === id);
        if (item) {
            item.qty += change;
            if (item.qty <= 0) {
                this.cartData = this.cartData.filter(i => i.id !== id);
            }
            this.renderCart();
            this.updateCartUI();
        }
    }

    updateCartSummary() {
        const subtotal = this.cartData.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const delivery = this.cartData.length > 0 ? this.options.deliveryFee : 0;
        const total = subtotal + delivery;

        if (this.subtotalAmount) {
            this.subtotalAmount.textContent = this.options.currency + subtotal;
        }
        if (this.totalAmount) {
            this.totalAmount.textContent = this.options.currency + total;
        }
    }

    updateCartUI() {
        const totalItems = this.cartData.reduce((sum, item) => sum + item.qty, 0);
        const totalPrice = this.cartData.reduce((sum, item) => sum + (item.price * item.qty), 0);

        if (this.cartItemsCount) {
            this.cartItemsCount.textContent = totalItems + ' Item' + (totalItems !== 1 ? 's' : '');
        }
        if (this.cartTotalText) {
            this.cartTotalText.textContent = this.options.currency + totalPrice;
        }

        // Show/hide cart bar based on items
        if (this.cartBar) {
            if (totalItems > 0) {
                this.cartBar.style.display = 'flex';
            } else {
                this.cartBar.style.display = 'none';
            }
        }
    }

    handleCheckout() {
        if (this.cartData.length === 0) return;

        if (this.checkoutBtn) {
            const originalHTML = this.checkoutBtn.innerHTML;
            this.checkoutBtn.innerHTML = '<i class="bi bi-check-lg"></i> Order Placed!';
            this.checkoutBtn.style.background = '#00a651';

            setTimeout(() => {
                this.checkoutBtn.innerHTML = originalHTML;
                this.checkoutBtn.style.background = '';
            }, 2000);
        }
    }

    // Method to add items programmatically
    addItem(name, price, qty = 1, isVeg = true) {
        const existingItem = this.cartData.find(item => item.name === name);
        if (existingItem) {
            existingItem.qty += qty;
        } else {
            const newId = this.cartData.length > 0 ? Math.max(...this.cartData.map(i => i.id)) + 1 : 1;
            this.cartData.push({ id: newId, name, price, qty, isVeg });
        }
        this.updateCartUI();
    }

    // Method to clear cart
    clearCart() {
        this.cartData = [];
        this.updateCartUI();
        this.renderCart();
    }

    // Method to get cart data
    getCartData() {
        return this.cartData;
    }

    // Method to get cart total
    getTotal() {
        const subtotal = this.cartData.reduce((sum, item) => sum + (item.price * item.qty), 0);
        return subtotal + (this.cartData.length > 0 ? this.options.deliveryFee : 0);
    }
}

// ===== PRODUCT DETAIL POPUP - MODERN =====
class ProductPopup {
    constructor() {
        this.currentProduct = null;
        this.quantity = 1;
        this.createPopupHTML();
        this.bindEvents();
    }

    createPopupHTML() {
        const popupHTML = `
            <div class="product-popup-overlay"></div>
            <div class="product-popup">
                <div class="popup-drag-handle"></div>
                <button class="popup-close-btn"><i class="bi bi-x-lg"></i></button>

                <div class="popup-image-section">
                    <div class="popup-image-container">
                        <img src="" alt="Product" class="popup-image">
                    </div>
                    <div class="popup-badges"></div>
                </div>

                <div class="popup-content">
                    <div class="popup-header">
                        <div class="popup-type-row">
                            <div class="popup-item-type veg"></div>
                            <span class="popup-type-label">Vegetarian</span>
                        </div>
                        <h2 class="popup-name">Product Name</h2>
                        <div class="popup-rating-row">
                            <div class="popup-rating">
                                <i class="bi bi-star-fill"></i>
                                <span class="popup-rating-value">4.5</span>
                            </div>
                            <span class="popup-reviews">120 ratings</span>
                        </div>
                    </div>

                    <div class="popup-pricing">
                        <span class="popup-price">₹299</span>
                        <span class="popup-mrp">₹399</span>
                        <span class="popup-discount">25% OFF</span>
                    </div>

                    <p class="popup-description">Delicious dish prepared with fresh ingredients.</p>

                    <div class="popup-customization" style="display: none;">
                        <div class="popup-section-title">
                            <i class="bi bi-sliders"></i>
                            Size Options
                        </div>
                        <div class="popup-options">
                            <span class="popup-option selected">Regular</span>
                            <span class="popup-option">Medium +₹30</span>
                            <span class="popup-option">Large +₹60</span>
                        </div>
                    </div>
                </div>

                <div class="popup-footer">
                    <div class="popup-qty-selector">
                        <button class="popup-qty-btn minus"><i class="bi bi-dash"></i></button>
                        <span class="popup-qty-value">1</span>
                        <button class="popup-qty-btn plus"><i class="bi bi-plus"></i></button>
                    </div>
                    <button class="popup-add-btn">
                        <span class="btn-text">
                            <i class="bi bi-bag-plus"></i>
                            Add Item
                        </span>
                        <span class="btn-price">₹299</span>
                    </button>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', popupHTML);

        // Get elements
        this.overlay = document.querySelector('.product-popup-overlay');
        this.popup = document.querySelector('.product-popup');
        this.closeBtn = document.querySelector('.popup-close-btn');
        this.image = document.querySelector('.popup-image');
        this.badges = document.querySelector('.popup-badges');
        this.itemType = document.querySelector('.popup-item-type');
        this.typeLabel = document.querySelector('.popup-type-label');
        this.name = document.querySelector('.popup-name');
        this.ratingRow = document.querySelector('.popup-rating-row');
        this.ratingValue = document.querySelector('.popup-rating-value');
        this.reviews = document.querySelector('.popup-reviews');
        this.description = document.querySelector('.popup-description');
        this.price = document.querySelector('.popup-price');
        this.mrp = document.querySelector('.popup-mrp');
        this.discount = document.querySelector('.popup-discount');
        this.qtyValue = document.querySelector('.popup-qty-value');
        this.qtyMinus = document.querySelector('.popup-qty-btn.minus');
        this.qtyPlus = document.querySelector('.popup-qty-btn.plus');
        this.addBtn = document.querySelector('.popup-add-btn');
        this.btnPrice = document.querySelector('.btn-price');
    }

    bindEvents() {
        // Close popup
        this.closeBtn.addEventListener('click', () => this.close());
        this.overlay.addEventListener('click', () => this.close());

        // Quantity controls
        this.qtyMinus.addEventListener('click', () => this.updateQuantity(-1));
        this.qtyPlus.addEventListener('click', () => this.updateQuantity(1));

        // Add to cart
        this.addBtn.addEventListener('click', () => this.addToCart());

        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.popup.classList.contains('active')) {
                this.close();
            }
        });

        // Swipe down to close
        let startY = 0;
        let isDragging = false;

        this.popup.addEventListener('touchstart', (e) => {
            if (e.target.closest('.popup-content')) return;
            startY = e.touches[0].clientY;
            isDragging = true;
        }, { passive: true });

        this.popup.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const currentY = e.touches[0].clientY;
            const diff = currentY - startY;
            if (diff > 80) {
                this.close();
                isDragging = false;
            }
        }, { passive: true });

        this.popup.addEventListener('touchend', () => {
            isDragging = false;
        }, { passive: true });
    }

    open(productData) {
        this.currentProduct = productData;
        this.quantity = 1;

        // Update image
        this.image.src = productData.image || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop';

        // Badges
        this.badges.innerHTML = '';
        if (productData.category === 'Recommended') {
            this.badges.innerHTML += '<span class="popup-badge recommended">Recommended</span>';
        }
        if (productData.rating >= 4.5) {
            this.badges.innerHTML += '<span class="popup-badge bestseller">Bestseller</span>';
        }

        // Item type
        const isVeg = productData.isVeg !== false;
        this.itemType.className = 'popup-item-type ' + (isVeg ? 'veg' : 'non-veg');
        this.typeLabel.textContent = isVeg ? 'Vegetarian' : 'Non-Vegetarian';

        // Name
        this.name.textContent = productData.name || 'Delicious Item';

        // Rating
        if (productData.rating) {
            this.ratingRow.style.display = 'flex';
            this.ratingValue.textContent = productData.rating;
            this.reviews.textContent = (productData.reviews || 0) + ' ratings';
        } else {
            this.ratingRow.style.display = 'none';
        }

        // Description
        const descriptions = {
            'Butter Chicken': 'Tender chicken pieces in a rich, creamy tomato-based gravy with aromatic spices.',
            'Margherita Pizza': 'Classic Italian pizza with fresh mozzarella, tomatoes, and basil leaves.',
            'Hyderabadi Biryani': 'Aromatic basmati rice layered with spiced meat, saffron, and fried onions.',
            'Paneer Tikka': 'Marinated cottage cheese cubes grilled to perfection with bell peppers.',
            'Dal Makhani': 'Creamy black lentils slow-cooked overnight with butter and cream.',
        };
        this.description.textContent = productData.description || descriptions[productData.name] || 'Made with fresh ingredients and authentic spices for an unforgettable taste.';

        // Pricing
        const price = productData.price || 0;
        const mrp = productData.mrp || price;
        const discountPercent = mrp > price ? Math.round(((mrp - price) / mrp) * 100) : 0;

        this.price.textContent = '₹' + price;

        if (discountPercent > 0) {
            this.mrp.textContent = '₹' + mrp;
            this.mrp.style.display = 'inline';
            this.discount.textContent = discountPercent + '% OFF';
            this.discount.style.display = 'inline-block';
        } else {
            this.mrp.style.display = 'none';
            this.discount.style.display = 'none';
        }

        // Reset quantity
        this.qtyValue.textContent = '1';
        this.qtyMinus.disabled = true;
        this.updateTotalPrice();

        // Show popup with animation
        requestAnimationFrame(() => {
            this.overlay.classList.add('active');
            this.popup.classList.add('active');
        });
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.overlay.classList.remove('active');
        this.popup.classList.remove('active');
        document.body.style.overflow = '';
        this.currentProduct = null;
        this.addBtn.classList.remove('added');
    }

    updateQuantity(change) {
        this.quantity = Math.max(1, this.quantity + change);
        this.qtyValue.textContent = this.quantity;
        this.qtyMinus.disabled = this.quantity <= 1;
        this.updateTotalPrice();
    }

    updateTotalPrice() {
        if (this.currentProduct) {
            const total = this.currentProduct.price * this.quantity;
            this.btnPrice.textContent = '₹' + total;
        }
    }

    addToCart() {
        if (this.currentProduct && window.menuCart) {
            window.menuCart.addItem(
                this.currentProduct.name,
                this.currentProduct.price,
                this.quantity
            );

            // Visual feedback
            this.addBtn.classList.add('added');
            const btnText = this.addBtn.querySelector('.btn-text');
            const originalText = btnText.innerHTML;
            btnText.innerHTML = '<i class="bi bi-check2"></i> Added!';

            setTimeout(() => {
                btnText.innerHTML = originalText;
                this.close();
            }, 600);
        }
    }
}

// Global product popup instance
let productPopup = null;

// Function to open product popup (can be called from anywhere)
function openProductPopup(productData) {
    if (!productPopup) {
        productPopup = new ProductPopup();
    }
    productPopup.open(productData);
}

// ===== GLOBAL SEARCH FEATURE =====
class GlobalSearch {
    constructor(menuData = []) {
        this.menuData = menuData;
        this.isOpen = false;
        this.createSearchHTML();
        this.bindEvents();
    }

    createSearchHTML() {
        // Find category nav to insert after
        const categoryNav = document.querySelector('.category-nav');
        if (!categoryNav) return;

        const searchHTML = `
            <div class="global-search-section">
                <div class="global-search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search for dishes..." class="global-search-input">
                    <button class="global-search-clear"><i class="bi bi-x"></i></button>
                </div>
                <div class="global-search-dropdown">
                    <div class="search-results-header">
                        <span class="search-results-count">Type to search</span>
                        <button class="search-close-btn"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="search-results-list"></div>
                </div>
            </div>
        `;

        categoryNav.insertAdjacentHTML('afterend', searchHTML);

        // Get elements
        this.section = document.querySelector('.global-search-section');
        this.input = document.querySelector('.global-search-input');
        this.clearBtn = document.querySelector('.global-search-clear');
        this.dropdown = document.querySelector('.global-search-dropdown');
        this.resultsCount = document.querySelector('.search-results-count');
        this.resultsList = document.querySelector('.search-results-list');
        this.closeBtn = document.querySelector('.search-close-btn');
    }

    bindEvents() {
        if (!this.input) return;

        // Header search button click
        const headerSearchBtn = document.querySelector('.search-btn');
        if (headerSearchBtn) {
            headerSearchBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
        }

        // Input focus - show dropdown
        this.input.addEventListener('focus', () => {
            this.dropdown.classList.add('active');
        });

        // Input change
        this.input.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            this.clearBtn.classList.toggle('visible', query.length > 0);
            this.dropdown.classList.add('active');

            if (query.length > 0) {
                this.search(query);
            } else {
                this.resultsCount.textContent = 'Type to search';
                this.resultsList.innerHTML = '';
            }
        });

        // Clear button
        this.clearBtn.addEventListener('click', () => {
            this.input.value = '';
            this.clearBtn.classList.remove('visible');
            this.resultsCount.textContent = 'Type to search';
            this.resultsList.innerHTML = '';
            this.input.focus();
        });

        // Close dropdown button
        this.closeBtn.addEventListener('click', () => {
            this.close();
        });

        // Click outside to close dropdown
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.global-search-section') && !e.target.closest('.search-btn')) {
                this.dropdown.classList.remove('active');
            }
        });

        // Escape to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });
    }

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        this.section.classList.add('active');
        this.isOpen = true;
        setTimeout(() => {
            this.input.focus();
        }, 300);
    }

    close() {
        this.section.classList.remove('active');
        this.dropdown.classList.remove('active');
        this.isOpen = false;
        this.input.value = '';
        this.clearBtn.classList.remove('visible');
        this.resultsList.innerHTML = '';
        this.resultsCount.textContent = 'Type to search';
    }

    search(query) {
        const results = this.menuData.filter(item =>
            item.name.toLowerCase().includes(query.toLowerCase()) ||
            (item.category && item.category.toLowerCase().includes(query.toLowerCase()))
        );

        this.resultsCount.innerHTML = `Found <strong>${results.length}</strong> item${results.length !== 1 ? 's' : ''}`;

        if (results.length === 0) {
            this.resultsList.innerHTML = `
                <div class="search-no-results">
                    <i class="bi bi-search"></i>
                    <p>No dishes found for "${query}"</p>
                </div>
            `;
        } else {
            this.resultsList.innerHTML = results.map((item) => `
                <div class="search-result-item" data-index="${this.menuData.indexOf(item)}">
                    <div class="search-result-image">
                        <img src="${item.image || 'https://via.placeholder.com/100'}" alt="${item.name}">
                    </div>
                    <div class="search-result-info">
                        <div class="search-result-name">
                            <span class="item-type-icon ${item.isVeg ? 'veg' : 'non-veg'}"></span>
                            ${this.highlightMatch(item.name, query)}
                        </div>
                        <div class="search-result-category">${item.category || 'Menu Item'}</div>
                    </div>
                    <div class="search-result-price">₹${item.price}</div>
                </div>
            `).join('');

            // Bind click events to results
            this.resultsList.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', () => {
                    const index = parseInt(item.dataset.index);
                    const productData = this.menuData[index];

                    // Close search first
                    this.close();

                    // Open product popup
                    if (typeof openProductPopup === 'function') {
                        openProductPopup(productData);
                    }
                });
            });
        }
    }

    highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="search-highlight">$1</span>');
    }

    // Method to update menu data
    setMenuData(data) {
        this.menuData = data;
    }
}

// Global search instance
let globalSearch = null;

// Function to initialize global search (call from menu style JS)
function initGlobalSearch(menuData) {
    if (!globalSearch) {
        globalSearch = new GlobalSearch(menuData);
    } else {
        globalSearch.setMenuData(menuData);
    }
    return globalSearch;
}

// ===== POWERED BY MINI HEADER =====
function initPoweredByHeader(options = {}) {
    const defaults = {
        brandName: 'MenuApp',
        icon: 'bi-lightning-charge-fill',
        link: null,
        position: 'top' // 'top' or 'bottom'
    };

    const settings = { ...defaults, ...options };

    // Check if already exists
    if (document.querySelector('.powered-by-header')) return;

    const headerHTML = settings.link
        ? `<div class="powered-by-header">
               <a href="${settings.link}" target="_blank">
                   <span class="powered-icon"><i class="bi ${settings.icon}"></i></span>
                   <span>Powered by</span>
                   <strong>${settings.brandName}</strong>
               </a>
           </div>`
        : `<div class="powered-by-header">
               <span class="powered-icon"><i class="bi ${settings.icon}"></i></span>
               <span>Powered by</span>
               <strong>${settings.brandName}</strong>
           </div>`;

    if (settings.position === 'top') {
        document.body.insertAdjacentHTML('afterbegin', headerHTML);
    } else {
        document.body.insertAdjacentHTML('beforeend', headerHTML);
    }
}

// Auto-initialize powered by header
document.addEventListener('DOMContentLoaded', function() {
    initPoweredByHeader();
});

// Add float animation keyframes
const cartStyles = document.createElement('style');
cartStyles.textContent = `
    @keyframes cartFloatUp {
        0% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(-30px); }
    }
`;
document.head.appendChild(cartStyles);

// Auto-initialize if DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if cart elements exist and initialize
    if (document.querySelector('.cart-bar') || document.querySelector('.cart-section')) {
        window.menuCart = new MenuCart();
    }
});
