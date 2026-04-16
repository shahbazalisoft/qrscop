// Menu Style 17 - Left Sidebar Category Layout with Style16 Design JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // ========== BANNER CAROUSEL (Same as menu_style8) ==========
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

    // ========== SIDEBAR CATEGORY NAVIGATION ==========
    const categoryItems = document.querySelectorAll('.category-item');
    const menuSections = document.querySelectorAll('.menu-section');
    const menuCards = document.querySelectorAll('.menu-card');

    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active state
            categoryItems.forEach(cat => cat.classList.remove('active'));
            this.classList.add('active');

            // Filter content
            if (category === 'all') {
                // Show all sections
                menuSections.forEach(section => {
                    section.style.display = 'block';
                    // Re-trigger animation
                    section.querySelectorAll('.menu-card').forEach((card, i) => {
                        card.style.animation = 'none';
                        card.offsetHeight;
                        card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                    });
                });
            } else {
                // Show only matching section
                menuSections.forEach(section => {
                    const sectionCategory = section.dataset.category || section.getAttribute('id');
                    if (sectionCategory === category) {
                        section.style.display = 'block';
                        section.querySelectorAll('.menu-card').forEach((card, i) => {
                            card.style.animation = 'none';
                            card.offsetHeight;
                            card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                        });
                    } else {
                        section.style.display = 'none';
                    }
                });
            }

            // Scroll to products area
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
        // Drinks
        { name: 'Masala Chai', price: 49, mrp: 59, isVeg: true, category: 'drinks' },
        { name: 'Cold Coffee', price: 129, mrp: 159, isVeg: true, category: 'drinks' },
        { name: 'Mango Lassi', price: 99, mrp: 129, isVeg: true, category: 'drinks' },
        { name: 'Fresh Orange Juice', price: 89, mrp: 109, isVeg: true, category: 'drinks' }
    ];

    // ========== ADD TO CART ==========
    const addCartBtns = document.querySelectorAll('.add-cart-btn');

    addCartBtns.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            const itemData = menuItemsData[index];
            const icon = this.querySelector('i');
            const isAdded = this.classList.contains('added');

            if (!isAdded && itemData) {
                // Add to cart
                if (window.menuCart) {
                    window.menuCart.addItem(itemData.name, itemData.price, 1);
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
    if (!document.querySelector('#style17-float-styles')) {
        const style = document.createElement('style');
        style.id = 'style17-float-styles';
        style.textContent = `
            @keyframes floatUp {
                0% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-40px); }
            }
        `;
        document.head.appendChild(style);
    }

    // ========== SCROLL SPY FOR SIDEBAR ==========
    const productsArea = document.querySelector('.products-area');

    if (productsArea) {
        productsArea.addEventListener('scroll', debounce(function() {
            const scrollTop = productsArea.scrollTop;

            menuSections.forEach(section => {
                if (section.style.display !== 'none') {
                    const sectionTop = section.offsetTop - productsArea.offsetTop;
                    const sectionHeight = section.offsetHeight;

                    if (scrollTop >= sectionTop - 100 && scrollTop < sectionTop + sectionHeight - 100) {
                        const categoryId = section.dataset.category || section.getAttribute('id');

                        categoryItems.forEach(item => {
                            if (item.dataset.category === categoryId) {
                                item.classList.add('active');
                            } else if (item.dataset.category !== 'all') {
                                item.classList.remove('active');
                            }
                        });
                    }
                }
            });
        }, 100));
    }

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

    // ========== PRODUCT DETAIL POPUP ==========
    // Initialize Item Detail Popup
    if (typeof initItemDetailPopup === 'function') {
        initItemDetailPopup(menuItemsData);
    }

    menuCards.forEach((card, index) => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.add-cart-btn')) return;

            const itemData = menuItemsData[index];
            if (itemData && typeof openItemDetail === 'function') {
                const img = card.querySelector('.card-image img');
                const mainImage = img ? img.src : '';
                openItemDetail(itemData, mainImage);
            }
        });

        card.style.cursor = 'pointer';
    });
});
