// Menu Style 18 - White Theme with Left Sidebar and Search Box

document.addEventListener('DOMContentLoaded', function() {
    // ========== BANNER CAROUSEL ==========
    const bannerCarousel = document.querySelector('.banner-carousel');
    const bannerTrack = document.querySelector('.banner-track');
    const bannerSlides = document.querySelectorAll('.banner-slide');
    const bannerDots = document.querySelectorAll('.banner-dots .dot');
    const prevBtn = document.querySelector('.banner-nav.prev');
    const nextBtn = document.querySelector('.banner-nav.next');

    let currentSlide = 0;
    let autoSlideInterval;
    const slideCount = bannerSlides.length;
    const autoSlideDelay = 4000;

    function goToSlide(index) {
        if (index < 0) index = slideCount - 1;
        if (index >= slideCount) index = 0;

        currentSlide = index;
        if (bannerTrack) {
            bannerTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        bannerDots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentSlide);
        });
    }

    function nextSlide() {
        goToSlide(currentSlide + 1);
    }

    function prevSlide() {
        goToSlide(currentSlide - 1);
    }

    function startAutoSlide() {
        stopAutoSlide();
        autoSlideInterval = setInterval(nextSlide, autoSlideDelay);
    }

    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            startAutoSlide();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            startAutoSlide();
        });
    }

    bannerDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
            startAutoSlide();
        });
    });

    // Touch/Swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    if (bannerCarousel) {
        bannerCarousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoSlide();
        }, { passive: true });

        bannerCarousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
            startAutoSlide();
        }, { passive: true });

        bannerCarousel.addEventListener('mouseenter', stopAutoSlide);
        bannerCarousel.addEventListener('mouseleave', startAutoSlide);
    }

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
    }

    if (slideCount > 1) {
        startAutoSlide();
    }

    // ========== SEARCH & CART TOGGLE ==========
    const searchToggleBtn = document.getElementById('search-toggle-btn');
    const cartToggleBtn = document.getElementById('cart-toggle-btn');
    const searchSection = document.getElementById('search-section');
    const cartOverlay = document.querySelector('.cart-overlay');
    const cartSection = document.querySelector('.cart-section');
    const headerCartBadge = document.getElementById('header-cart-badge');

    // Search toggle
    if (searchToggleBtn && searchSection) {
        searchToggleBtn.addEventListener('click', function() {
            searchSection.classList.toggle('active');
            if (searchSection.classList.contains('active')) {
                const searchInput = document.getElementById('search-input');
                if (searchInput) searchInput.focus();
            }
        });
    }

    // Cart toggle
    if (cartToggleBtn) {
        cartToggleBtn.addEventListener('click', function() {
            if (cartOverlay) cartOverlay.classList.add('active');
            if (cartSection) cartSection.classList.add('active');
        });
    }

    // Close cart when clicking overlay
    if (cartOverlay) {
        cartOverlay.addEventListener('click', function() {
            cartOverlay.classList.remove('active');
            if (cartSection) cartSection.classList.remove('active');
        });
    }

    // Close cart button
    const cartCloseBtn = document.querySelector('.cart-close-btn');
    if (cartCloseBtn) {
        cartCloseBtn.addEventListener('click', function() {
            if (cartOverlay) cartOverlay.classList.remove('active');
            if (cartSection) cartSection.classList.remove('active');
        });
    }

    // Update header cart badge
    function updateHeaderCartBadge() {
        if (headerCartBadge && window.menuCart) {
            const cartData = window.menuCart.getCartData();
            const count = cartData.reduce((sum, item) => sum + item.qty, 0);
            headerCartBadge.textContent = count;
            headerCartBadge.setAttribute('data-count', count);
        }
    }

    // ========== SEARCH FUNCTIONALITY ==========
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    const menuCards = document.querySelectorAll('.menu-card');
    const menuSections = document.querySelectorAll('.menu-section');

    // Toggle clear button visibility
    function updateClearButton() {
        if (clearSearchBtn) {
            if (searchInput.value.trim() !== '') {
                clearSearchBtn.classList.add('visible');
            } else {
                clearSearchBtn.classList.remove('visible');
            }
        }
    }

    // Clear search functionality
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            updateClearButton();
            // Reset all sections and cards
            menuSections.forEach(section => {
                section.style.display = 'block';
            });
            menuCards.forEach(card => {
                card.style.display = 'block';
            });
            searchInput.focus();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', debounce(function(e) {
            const query = e.target.value.toLowerCase().trim();
            updateClearButton();

            if (query === '') {
                // Show all sections and cards
                menuSections.forEach(section => {
                    section.style.display = 'block';
                });
                menuCards.forEach(card => {
                    card.style.display = 'block';
                });
                return;
            }

            // Filter cards
            menuSections.forEach(section => {
                const cards = section.querySelectorAll('.menu-card');
                let hasVisibleCard = false;

                cards.forEach(card => {
                    const title = card.querySelector('.card-title').textContent.toLowerCase();
                    if (title.includes(query)) {
                        card.style.display = 'block';
                        hasVisibleCard = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Hide section if no visible cards
                section.style.display = hasVisibleCard ? 'block' : 'none';
            });
        }, 300));
    }

    // ========== SIDEBAR CATEGORY NAVIGATION ==========
    const categoryItems = document.querySelectorAll('.category-item');

    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active state
            categoryItems.forEach(cat => cat.classList.remove('active'));
            this.classList.add('active');

            // Clear search
            if (searchInput) {
                searchInput.value = '';
            }

            // Filter content
            if (category === 'all') {
                menuSections.forEach(section => {
                    section.style.display = 'block';
                    section.querySelectorAll('.menu-card').forEach((card, i) => {
                        card.style.display = 'block';
                        card.style.animation = 'none';
                        card.offsetHeight;
                        card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                    });
                });
            } else {
                menuSections.forEach(section => {
                    const sectionCategory = section.dataset.category || section.getAttribute('id');
                    if (sectionCategory === category) {
                        section.style.display = 'block';
                        section.querySelectorAll('.menu-card').forEach((card, i) => {
                            card.style.display = 'block';
                            card.style.animation = 'none';
                            card.offsetHeight;
                            card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                        });
                    } else {
                        section.style.display = 'none';
                    }
                });
            }

            // Scroll to top of products area
            const productsArea = document.querySelector('.products-area');
            if (productsArea) {
                productsArea.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    // ========== MENU DATA ==========
    const menuItemsData = [
        // Recommended
        { name: 'Butter Chicken', price: 349, mrp: 449, isVeg: false, category: 'recommended' },
        { name: 'Margherita Pizza', price: 299, mrp: 399, isVeg: true, category: 'recommended' },
        { name: 'Hyderabadi Biryani', price: 399, mrp: 499, isVeg: false, category: 'recommended' },
        { name: 'Grilled Salmon', price: 599, mrp: 749, isVeg: false, category: 'recommended' },
        // Starters
        { name: 'Paneer Tikka', price: 249, mrp: 299, isVeg: true, category: 'starters' },
        { name: 'Crispy Samosa', price: 79, mrp: 99, isVeg: true, category: 'starters' },
        { name: 'Buffalo Wings', price: 299, mrp: 399, isVeg: false, category: 'starters' },
        { name: 'Spring Rolls', price: 149, mrp: 179, isVeg: true, category: 'starters' },
        // Main Course
        { name: 'Dal Makhani', price: 229, mrp: 279, isVeg: true, category: 'mains' },
        { name: 'Palak Paneer', price: 269, mrp: 329, isVeg: true, category: 'mains' },
        { name: 'Garlic Naan', price: 69, mrp: 89, isVeg: true, category: 'mains' },
        { name: 'Tandoori Roti', price: 39, mrp: 49, isVeg: true, category: 'mains' },
        // Desserts
        { name: 'Fluffy Pancakes', price: 179, mrp: 229, isVeg: true, category: 'desserts' },
        { name: 'Blueberry Cheesecake', price: 199, mrp: 249, isVeg: true, category: 'desserts' },
        { name: 'Gulab Jamun', price: 99, mrp: 129, isVeg: true, category: 'desserts' },
        { name: 'Chocolate Ice Cream', price: 119, mrp: 149, isVeg: true, category: 'desserts' },
        // Drinks
        { name: 'Masala Chai', price: 49, mrp: 59, isVeg: true, category: 'drinks' },
        { name: 'Cold Coffee', price: 129, mrp: 159, isVeg: true, category: 'drinks' },
        { name: 'Mango Lassi', price: 99, mrp: 129, isVeg: true, category: 'drinks' },
        { name: 'Fresh Orange Juice', price: 89, mrp: 109, isVeg: true, category: 'drinks' }
    ];

    // ========== ADD TO CART ==========
    const addCartBtns = document.querySelectorAll('.add-cart-btn');

    // Function to sync add buttons with cart state
    function syncAddButtonsWithCart() {
        if (!window.menuCart) return;

        const cartData = window.menuCart.getCartData();

        addCartBtns.forEach((btn, index) => {
            const itemData = menuItemsData[index];
            const icon = btn.querySelector('i');

            // Check if this item (or any variation of it) is in cart
            const inCart = cartData.some(cartItem =>
                cartItem.name.startsWith(itemData.name)
            );

            if (inCart) {
                btn.classList.add('added');
                icon.classList.remove('bi-plus');
                icon.classList.add('bi-check');
            } else {
                btn.classList.remove('added');
                icon.classList.remove('bi-check');
                icon.classList.add('bi-plus');
            }
        });

        updateHeaderCartBadge();
    }

    // Override cart's renderCart to sync buttons after cart changes
    if (window.menuCart) {
        const originalRenderCart = window.menuCart.renderCart.bind(window.menuCart);
        window.menuCart.renderCart = function() {
            originalRenderCart();
            syncAddButtonsWithCart();
        };

        // Initial sync on page load
        syncAddButtonsWithCart();
    }

    addCartBtns.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            const itemData = menuItemsData[index];
            const icon = this.querySelector('i');
            const isAdded = this.classList.contains('added');

            if (!isAdded && itemData) {
                // Add to cart
                if (window.menuCart) {
                    window.menuCart.addItem(itemData.name, itemData.price, 1, itemData.isVeg);
                    syncAddButtonsWithCart();
                }

                this.classList.add('added');
                icon.classList.remove('bi-plus');
                icon.classList.add('bi-check');

                // Float animation
                createFloatAnimation(this, '+1');
            }
        });
    });

    function createFloatAnimation(element, text) {
        const floater = document.createElement('span');
        floater.textContent = text;
        floater.style.cssText = `
            position: fixed;
            color: #10847e;
            font-size: 1.2rem;
            font-weight: bold;
            pointer-events: none;
            z-index: 9999;
            animation: floatUp 0.7s ease-out forwards;
        `;

        const rect = element.getBoundingClientRect();
        floater.style.left = rect.left + rect.width / 2 - 10 + 'px';
        floater.style.top = rect.top + 'px';

        document.body.appendChild(floater);
        setTimeout(() => floater.remove(), 700);
    }

    // Add float animation keyframes
    if (!document.querySelector('#style18-float-styles')) {
        const style = document.createElement('style');
        style.id = 'style18-float-styles';
        style.textContent = `
            @keyframes floatUp {
                0% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-40px); }
            }
        `;
        document.head.appendChild(style);
    }

    // ========== ITEM DETAIL POPUP ==========
    // Initialize common item detail popup
    if (typeof initItemDetailPopup === 'function') {
        initItemDetailPopup(menuItemsData);
    }

    // Open popup on card click (image or title)
    menuCards.forEach((card, index) => {
        const cardImage = card.querySelector('.card-image');
        const cardTitle = card.querySelector('.card-title');

        const openPopup = function(e) {
            e.stopPropagation();
            const itemData = menuItemsData[index];
            if (itemData) {
                const img = card.querySelector('.card-image img');
                const mainImage = img ? img.src : '';
                openItemDetail(itemData, mainImage);
            }
        };

        if (cardImage) cardImage.addEventListener('click', openPopup);
        if (cardTitle) cardTitle.addEventListener('click', openPopup);
    });

    // ========== UTILITY FUNCTIONS ==========
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
