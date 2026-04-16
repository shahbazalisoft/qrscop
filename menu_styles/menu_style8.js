// Menu Style 16 - Two Column Grid Layout JavaScript

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
    const autoSlideDelay = 4000; // 4 seconds

    function goToSlide(index) {
        if (index < 0) index = slideCount - 1;
        if (index >= slideCount) index = 0;

        currentSlide = index;
        bannerTrack.style.transform = `translateX(-${currentSlide * 100}%)`;

        // Update dots
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

    // Navigation buttons
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

    // Dot navigation
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

    // Pause on hover
    if (bannerCarousel) {
        bannerCarousel.addEventListener('mouseenter', stopAutoSlide);
        bannerCarousel.addEventListener('mouseleave', startAutoSlide);
    }

    // Start auto-slide
    if (slideCount > 1) {
        startAutoSlide();
    }

    // ========== MENU DATA ==========
    // Menu items data
    const menuItemsData = [
        // Recommended
        { name: 'Butter Chicken', price: 349, mrp: 449, isVeg: false, category: 'Recommended', image: 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&h=300&fit=crop', rating: 4.5, reviews: 574 },
        { name: 'Margherita Pizza', price: 299, mrp: 399, isVeg: true, category: 'Recommended', image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=300&h=300&fit=crop', rating: 4, reviews: 378 },
        { name: 'Hyderabadi Biryani', price: 399, mrp: 499, isVeg: false, category: 'Recommended', image: 'https://images.unsplash.com/photo-1574484284002-952d92456975?w=300&h=300&fit=crop', rating: 5, reviews: 892 },
        { name: 'Grilled Salmon', price: 599, mrp: 749, isVeg: false, category: 'Recommended', image: 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=300&h=300&fit=crop', rating: 4.5, reviews: 245 },
        // Starters
        { name: 'Paneer Tikka', price: 249, mrp: 299, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&h=300&fit=crop', rating: 4, reviews: 456 },
        { name: 'Crispy Samosa', price: 79, mrp: 99, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=300&h=300&fit=crop', rating: 4.5, reviews: 623 },
        { name: 'Buffalo Wings', price: 299, mrp: 399, isVeg: false, category: 'Starters', image: 'https://images.unsplash.com/photo-1562967914-608f82629710?w=300&h=300&fit=crop', rating: 4, reviews: 312 },
        { name: 'Spring Rolls', price: 149, mrp: 179, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1541014741259-de529411b96a?w=300&h=300&fit=crop', rating: 3.5, reviews: 189 },
        // Main Course
        { name: 'Dal Makhani', price: 229, mrp: 279, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=300&h=300&fit=crop', rating: 5, reviews: 734 },
        { name: 'Palak Paneer', price: 269, mrp: 329, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=300&h=300&fit=crop', rating: 4.5, reviews: 421 },
        { name: 'Garlic Naan', price: 69, mrp: 89, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=300&h=300&fit=crop', rating: 4, reviews: 567 },
        { name: 'Tandoori Roti', price: 39, mrp: 49, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300&h=300&fit=crop', rating: 4.5, reviews: 345 },
        // Desserts
        { name: 'Fluffy Pancakes', price: 179, mrp: 229, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=300&h=300&fit=crop', rating: 4, reviews: 298 },
        { name: 'Blueberry Cheesecake', price: 199, mrp: 249, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=300&h=300&fit=crop', rating: 4.5, reviews: 512 },
        { name: 'Gulab Jamun', price: 99, mrp: 129, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=300&h=300&fit=crop', rating: 5, reviews: 876 },
        // Drinks
        { name: 'Masala Chai', price: 49, mrp: 59, isVeg: true, category: 'Drinks', image: 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=300&h=300&fit=crop', rating: 4.5, reviews: 1200 },
        { name: 'Cold Coffee', price: 129, mrp: 159, isVeg: true, category: 'Drinks', image: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=300&h=300&fit=crop', rating: 4, reviews: 678 },
        { name: 'Mango Lassi', price: 99, mrp: 129, isVeg: true, category: 'Drinks', image: 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=300&h=300&fit=crop', rating: 4.5, reviews: 534 },
        { name: 'Fresh Orange Juice', price: 89, mrp: 109, isVeg: true, category: 'Drinks', image: 'https://images.unsplash.com/photo-1534353473418-4cfa6c56fd38?w=300&h=300&fit=crop', rating: 4, reviews: 287 }
    ];

    // Category Pills Navigation
    const pills = document.querySelectorAll('.pill');
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    const sections = document.querySelectorAll('.menu-section');
    const moreBtn = document.querySelector('.more-btn');
    const moreDropdown = document.querySelector('.more-dropdown');

    // Category to section ID mapping
    const categoryMap = {
        'all': null,
        'recommended': 'recommended',
        'starters': 'starters',
        'mains': 'mains',
        'desserts': 'desserts',
        'drinks': 'drinks'
    };

    // More button dropdown toggle
    if (moreBtn) {
        moreBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            moreDropdown.classList.toggle('active');
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.more-menu')) {
            if (moreDropdown) moreDropdown.classList.remove('active');
        }
    });

    // Function to update active states
    function updateActiveCategory(category) {
        pills.forEach(p => p.classList.remove('active'));
        dropdownItems.forEach(d => d.classList.remove('active'));

        const activePill = document.querySelector(`.pill[data-category="${category}"]`);
        const activeDropdown = document.querySelector(`.dropdown-item[data-category="${category}"]`);

        if (activePill) activePill.classList.add('active');
        if (activeDropdown) activeDropdown.classList.add('active');
    }

    // Function to filter sections
    function filterSections(category) {
        sections.forEach(section => {
            if (category === 'all') {
                section.style.display = 'block';
                // Re-trigger animation
                section.querySelectorAll('.menu-card').forEach((card, i) => {
                    card.style.animation = 'none';
                    card.offsetHeight;
                    card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                });
            } else {
                const sectionId = section.getAttribute('id');
                if (sectionId === categoryMap[category]) {
                    section.style.display = 'block';
                    section.querySelectorAll('.menu-card').forEach((card, i) => {
                        card.style.animation = 'none';
                        card.offsetHeight;
                        card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                    });
                } else {
                    section.style.display = 'none';
                }
            }
        });
    }

    // Pills click handler
    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            const category = this.dataset.category;
            updateActiveCategory(category);
            filterSections(category);
        });
    });

    // Dropdown items click handler
    dropdownItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.dataset.category;
            updateActiveCategory(category);
            filterSections(category);
            if (moreDropdown) moreDropdown.classList.remove('active');
        });
    });

    // ========== SIZE POPUP ==========
    const sizePopup = document.getElementById('sizePopup');
    const sizePopupOverlay = document.getElementById('sizePopupOverlay');
    const popupItemName = document.getElementById('popupItemName');
    const popupClose = document.getElementById('popupClose');
    const quarterPrice = document.getElementById('quarterPrice');
    const halfPrice = document.getElementById('halfPrice');
    const fullPrice = document.getElementById('fullPrice');

    let currentSizeItem = null;

    function openSizePopup(card) {
        currentSizeItem = {
            name: card.dataset.item,
            quarter: parseInt(card.dataset.quarter),
            half: parseInt(card.dataset.half),
            full: parseInt(card.dataset.full)
        };

        popupItemName.textContent = currentSizeItem.name;
        quarterPrice.textContent = '₹' + currentSizeItem.quarter;
        halfPrice.textContent = '₹' + currentSizeItem.half;
        fullPrice.textContent = '₹' + currentSizeItem.full;

        sizePopup.classList.add('show');
        sizePopupOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSizePopup() {
        sizePopup.classList.remove('show');
        sizePopupOverlay.classList.remove('show');
        document.body.style.overflow = '';
        currentSizeItem = null;
    }

    if (popupClose) {
        popupClose.addEventListener('click', closeSizePopup);
    }
    if (sizePopupOverlay) {
        sizePopupOverlay.addEventListener('click', closeSizePopup);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sizePopup && sizePopup.classList.contains('show')) {
            closeSizePopup();
        }
    });

    // Size add buttons
    const sizeAddBtns = document.querySelectorAll('.size-add-btn');
    sizeAddBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (!currentSizeItem) return;

            const sizeOption = this.closest('.size-option');
            const size = sizeOption.dataset.size;
            let price, sizeName;

            switch(size) {
                case 'quarter':
                    price = currentSizeItem.quarter;
                    sizeName = 'Quarter';
                    break;
                case 'half':
                    price = currentSizeItem.half;
                    sizeName = 'Half';
                    break;
                case 'full':
                    price = currentSizeItem.full;
                    sizeName = 'Full';
                    break;
            }

            if (window.menuCart) {
                window.menuCart.addItem(currentSizeItem.name + ' (' + sizeName + ')', price, 1);
            }

            this.classList.add('added');
            this.innerHTML = '<i class="bi bi-check"></i>';

            setTimeout(() => {
                this.classList.remove('added');
                this.innerHTML = '<i class="bi bi-plus"></i>';
                closeSizePopup();
            }, 600);
        });
    });

    // Add to cart functionality
    const addCartBtns = document.querySelectorAll('.add-cart-btn');

    addCartBtns.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            // Check if this is a size item
            const card = this.closest('.menu-card');
            if (card && card.classList.contains('has-sizes')) {
                openSizePopup(card);
                return;
            }

            const itemData = menuItemsData[index];
            const icon = this.querySelector('i');
            const isAdded = this.classList.contains('added');

            // Only allow adding, not removing from menu item button
            if (!isAdded) {
                // Add to cart
                if (window.menuCart) {
                    window.menuCart.addItem(itemData.name, itemData.price, 1);
                }

                this.classList.add('added');
                icon.classList.remove('bi-plus');
                icon.classList.add('bi-check');

                // Create floating animation
                createFloatAnimation(this, '+1');
            }
            // If already added, do nothing - icon stays as check
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
    const style = document.createElement('style');
    style.textContent = `
        @keyframes floatUp {
            0% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-40px); }
        }
    `;
    document.head.appendChild(style);

    // ========== PRODUCT DETAIL POPUP ==========
    // Initialize Item Detail Popup
    if (typeof initItemDetailPopup === 'function') {
        initItemDetailPopup(menuItemsData);
    }

    const menuCards = document.querySelectorAll('.menu-card');

    menuCards.forEach((card, index) => {
        card.addEventListener('click', function(e) {
            // Don't open popup if clicking on add-to-cart button
            if (e.target.closest('.add-cart-btn')) return;

            const itemData = menuItemsData[index];
            if (itemData && typeof openItemDetail === 'function') {
                const img = card.querySelector('.card-image img');
                const mainImage = img ? img.src : (itemData.image || '');
                openItemDetail(itemData, mainImage);
            }
        });

        // Add cursor pointer style
        card.style.cursor = 'pointer';
    });

    // ========== GLOBAL SEARCH ==========
    if (typeof initGlobalSearch === 'function') {
        initGlobalSearch(menuItemsData);
    }

});
