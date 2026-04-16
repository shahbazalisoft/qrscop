// Menu Style 9 - Large Card Menu JavaScript

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

    // Menu items data for search and add-to-cart
    const menuItemsData = [
        // Recommended
        { name: 'Grilled Salmon', price: 599, mrp: 749, isVeg: false, category: 'Recommended', image: 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=800&h=600&fit=crop' },
        { name: 'Margherita Pizza', price: 349, mrp: 449, isVeg: true, category: 'Recommended', image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=800&h=600&fit=crop' },
        { name: 'Butter Chicken', price: 399, mrp: 499, isVeg: false, category: 'Recommended', image: 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=800&h=600&fit=crop' },
        // Starters
        { name: 'Paneer Tikka', price: 299, mrp: 379, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=800&h=600&fit=crop' },
        { name: 'Crispy Samosa', price: 99, mrp: 129, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800&h=600&fit=crop' },
        { name: 'Buffalo Wings', price: 349, mrp: 449, isVeg: false, category: 'Starters', image: 'https://images.unsplash.com/photo-1562967914-608f82629710?w=800&h=600&fit=crop' },
        { name: 'Veg Spring Rolls', price: 179, mrp: 229, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1541014741259-de529411b96a?w=800&h=600&fit=crop' },
        // Main Course
        { name: 'Hyderabadi Biryani', price: 449, mrp: 549, isVeg: false, category: 'Main Course', image: 'https://images.unsplash.com/photo-1574484284002-952d92456975?w=800&h=600&fit=crop' },
        { name: 'Dal Makhani', price: 249, mrp: 299, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=800&h=600&fit=crop' },
        { name: 'Palak Paneer', price: 279, mrp: 349, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=800&h=600&fit=crop' },
        { name: 'Garlic Naan', price: 69, mrp: 89, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=800&h=600&fit=crop' },
        { name: 'Tandoori Roti', price: 39, mrp: 49, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=800&h=600&fit=crop' },
        // Desserts
        { name: 'Fluffy Pancakes', price: 199, mrp: 249, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=800&h=600&fit=crop' },
        { name: 'Blueberry Cheesecake', price: 249, mrp: 299, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=800&h=600&fit=crop' },
        { name: 'Gulab Jamun', price: 129, mrp: 159, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=800&h=600&fit=crop' },
        // Beverages
        { name: 'Masala Chai', price: 49, mrp: 59, isVeg: true, category: 'Beverages', image: 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=800&h=600&fit=crop' },
        { name: 'Cold Coffee', price: 149, mrp: 179, isVeg: true, category: 'Beverages', image: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=800&h=600&fit=crop' },
        { name: 'Mango Lassi', price: 99, mrp: 129, isVeg: true, category: 'Beverages', image: 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=800&h=600&fit=crop' },
        { name: 'Fresh Orange Juice', price: 89, mrp: 109, isVeg: true, category: 'Beverages', image: 'https://images.unsplash.com/photo-1534353473418-4cfa6c56fd38?w=800&h=600&fit=crop' }
    ];

    // Initialize Item Detail Popup
    if (typeof initItemDetailPopup === 'function') {
        initItemDetailPopup(menuItemsData);
    }

    // Menu card click for popup
    const menuCards = document.querySelectorAll('.menu-card');
    menuCards.forEach((card, index) => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function(e) {
            if (e.target.closest('.card-add-btn')) return;
            const itemData = menuItemsData[index];
            if (itemData && typeof openItemDetail === 'function') {
                const img = card.querySelector('.card-image img');
                const mainImage = img ? img.src : (itemData.image || '');
                openItemDetail(itemData, mainImage);
            }
        });
    });

    // Category Navigation
    const catBtns = document.querySelectorAll('.cat-btn');
    const menuSections = document.querySelectorAll('.menu-section');

    const categoryMap = {
        'all': null,
        'recommended': 'recommended',
        'starters': 'starters',
        'main-course': 'main-course',
        'desserts': 'desserts',
        'beverages': 'beverages'
    };

    catBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active state
            catBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Filter sections
            menuSections.forEach(section => {
                if (category === 'all') {
                    section.style.display = 'block';
                } else {
                    const sectionId = section.getAttribute('id');
                    section.style.display = sectionId === categoryMap[category] ? 'block' : 'none';
                }
            });

            // Scroll to top of content
            document.querySelector('.main-content').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Add to cart functionality for card-add-btn (custom for this template)
    const cardAddBtns = document.querySelectorAll('.card-add-btn');

    cardAddBtns.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            const itemData = menuItemsData[index];
            const isAdded = this.classList.contains('added');

            if (!isAdded) {
                // Use global menuCart from cart-common.js
                if (window.menuCart) {
                    window.menuCart.addItem(itemData.name, itemData.price, 1);
                }

                // Button animation - change to added state
                this.classList.add('added');
                this.innerHTML = '<i class="bi bi-check-lg"></i>';

                // Create floating animation
                createFloatAnimation(this, '+1');
            }
        });
    });

    function createFloatAnimation(element, text) {
        const floater = document.createElement('span');
        floater.textContent = text;
        floater.style.cssText = `
            position: fixed;
            color: var(--accent-gold, #c9a962);
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

    // Search functionality
    const searchOverlay = document.querySelector('.search-overlay');
    const searchInput = document.querySelector('.search-input');
    const searchCloseBtn = document.querySelector('.search-close-btn');
    const searchClearBtn = document.querySelector('.search-clear-btn');
    const searchResults = document.querySelector('.search-results');
    const searchBtn = document.querySelector('.search-btn');

    function openSearch() {
        searchOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => searchInput.focus(), 100);
    }

    function closeSearch() {
        searchOverlay.classList.remove('active');
        document.body.style.overflow = '';
        searchInput.value = '';
        searchClearBtn.style.display = 'none';
        renderSearchPlaceholder();
    }

    function renderSearchPlaceholder() {
        searchResults.innerHTML = `
            <div class="search-placeholder">
                <i class="bi bi-search"></i>
                <p>Search for your favorite dishes</p>
            </div>
        `;
    }

    function renderSearchResults(query) {
        if (!query.trim()) {
            renderSearchPlaceholder();
            return;
        }

        const filteredItems = menuItemsData.filter(item =>
            item.name.toLowerCase().includes(query.toLowerCase())
        );

        if (filteredItems.length === 0) {
            searchResults.innerHTML = `
                <div class="search-no-results">
                    <i class="bi bi-emoji-frown"></i>
                    <p>No dishes found for "${query}"</p>
                </div>
            `;
            return;
        }

        // Group by category
        const grouped = filteredItems.reduce((acc, item) => {
            if (!acc[item.category]) acc[item.category] = [];
            acc[item.category].push(item);
            return acc;
        }, {});

        let html = '';
        for (const category in grouped) {
            html += `<div class="search-category-label">${category}</div>`;
            grouped[category].forEach(item => {
                const highlightedName = item.name.replace(
                    new RegExp(`(${query})`, 'gi'),
                    '<mark>$1</mark>'
                );
                html += `
                    <div class="search-result-item" data-name="${item.name}">
                        <div class="search-result-info">
                            <span class="search-result-badge ${item.isVeg ? 'veg' : 'non-veg'}"></span>
                            <span class="search-result-name">${highlightedName}</span>
                        </div>
                        <span class="search-result-price">₹${item.price}</span>
                    </div>
                `;
            });
        }
        searchResults.innerHTML = html;

        // Add click listeners
        document.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', function() {
                const name = this.dataset.name;
                closeSearch();

                // Find and scroll to the card
                const cards = document.querySelectorAll('.menu-card');
                cards.forEach((card, index) => {
                    if (menuItemsData[index].name === name) {
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        card.style.boxShadow = '0 0 30px rgba(201, 169, 98, 0.5)';
                        setTimeout(() => {
                            card.style.boxShadow = '';
                        }, 2000);
                    }
                });
            });
        });
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', openSearch);
    }
    if (searchCloseBtn) {
        searchCloseBtn.addEventListener('click', closeSearch);
    }

    if (searchClearBtn) {
        searchClearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchClearBtn.style.display = 'none';
            searchInput.focus();
            renderSearchPlaceholder();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value;
            searchClearBtn.style.display = query ? 'flex' : 'none';
            renderSearchResults(query);
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSearch();
        });
    }

    // Scroll to top button
    const scrollTopBtn = document.querySelector('.scroll-top-btn');

    window.addEventListener('scroll', function() {
        if (scrollTopBtn) {
            if (window.scrollY > 300) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        }
    });

    if (scrollTopBtn) {
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Header buttons animation
    const menuBtn = document.querySelector('.menu-btn');

    if (menuBtn) {
        menuBtn.addEventListener('click', function() {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }
});
