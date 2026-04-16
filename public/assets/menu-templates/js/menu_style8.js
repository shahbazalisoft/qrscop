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
    // Use dynamic menu items data from blade template
    const menuItemsData = window.menuItemsData || [];

    // Category Image Navigation
    const catItems = document.querySelectorAll('.cat-img-item');
    const sections = document.querySelectorAll('.menu-section');
    const catPopupItems = document.querySelectorAll('.cat-popup-item');

    // Select a category (shared logic)
    function selectCategory(category) {
        // Update nav active state
        catItems.forEach(c => c.classList.remove('active'));
        const activeNav = document.querySelector('.cat-img-item[data-category="' + category + '"]');
        if (activeNav) activeNav.classList.add('active');

        // Update popup active state
        catPopupItems.forEach(item => {
            item.classList.toggle('active', item.dataset.category === category);
        });

        // Filter sections
        sections.forEach(section => {
            if (category === 'all') {
                section.style.display = 'block';
                section.querySelectorAll('.menu-card').forEach((card, i) => {
                    card.style.animation = 'none';
                    card.offsetHeight;
                    card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                });
            } else {
                const sectionId = section.getAttribute('id');
                if (sectionId === category) {
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

    // Category image items click handler
    catItems.forEach(item => {
        item.addEventListener('click', function() {
            selectCategory(this.dataset.category);
            this.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        });
    });

    // ========== VIEW ALL CATEGORY POPUP ==========
    const viewAllBtn = document.getElementById('viewall-btn') || document.getElementById('cat-viewall-btn');
    const catOverlay = document.getElementById('cat-popup-overlay');
    const catPopup = document.getElementById('cat-popup');
    const catCloseBtn = document.getElementById('cat-popup-close');

    function openCatPopup() {
        catOverlay.classList.add('active');
        catPopup.classList.add('active');
    }

    function closeCatPopup() {
        catOverlay.classList.remove('active');
        catPopup.classList.remove('active');
    }

    if (viewAllBtn) viewAllBtn.addEventListener('click', openCatPopup);
    if (catOverlay) catOverlay.addEventListener('click', closeCatPopup);
    if (catCloseBtn) catCloseBtn.addEventListener('click', closeCatPopup);

    catPopupItems.forEach(item => {
        item.addEventListener('click', function() {
            selectCategory(this.dataset.category);
            closeCatPopup();
        });
    });

    // ========== SEARCH BAR & FILTER ==========
    const searchBtn = document.querySelector('.search-btn');
    const searchBarSection = document.querySelector('.search-bar-section');
    const searchField = document.querySelector('.search-field');
    const clearSearchBtn = document.querySelector('.clear-search-btn');
    const filterDropdown = document.querySelector('.filter-dropdown');
    const filterBtn = document.querySelector('.filter-btn');
    const filterOptions = document.querySelectorAll('.filter-option');
    const menuItemElements = document.querySelectorAll('.menu-card');
    const menuSections = document.querySelectorAll('.menu-section');

    let currentFilter = 'all';
    let currentSearchQuery = '';

    // Toggle search bar visibility
    if (searchBtn && searchBarSection) {
        searchBtn.addEventListener('click', function() {
            const isVisible = searchBarSection.style.display !== 'none';
            searchBarSection.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                setTimeout(() => searchField.focus(), 100);
            }
        });
    }

    // Filter dropdown toggle
    if (filterBtn) {
        filterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            filterDropdown.classList.toggle('active');
        });
    }

    // Close filter dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.filter-dropdown')) {
            if (filterDropdown) filterDropdown.classList.remove('active');
        }
    });

    // Filter option selection
    filterOptions.forEach(option => {
        option.addEventListener('click', function() {
            const filter = this.dataset.filter;
            currentFilter = filter;

            // Update active state
            filterOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');

            // Update button text and icon
            const iconClass = filter === 'all' ? 'all-icon' : filter === 'veg' ? 'veg-icon' : 'non-veg-icon';
            const text = filter === 'all' ? 'All' : filter === 'veg' ? 'Veg' : 'Non-Veg';
            filterBtn.querySelector('.filter-icon').className = 'filter-icon ' + iconClass;
            filterBtn.querySelector('.filter-text').textContent = text;

            // Close dropdown
            filterDropdown.classList.remove('active');

            // Apply filter
            applyFilters();
        });
    });

    // Search input
    if (searchField) {
        searchField.addEventListener('input', function() {
            const rawValue = this.value;
            currentSearchQuery = rawValue.toLowerCase().trim();
            if (clearSearchBtn) {
                clearSearchBtn.style.display = rawValue.length > 0 ? 'flex' : 'none';
            }
            applyFilters();
        });
    }

    // Clear search
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchField.value = '';
            currentSearchQuery = '';
            clearSearchBtn.style.display = 'none';
            searchField.focus();
            applyFilters();
        });
    }

    // Apply search and filter
    function applyFilters() {
        menuItemElements.forEach((item, index) => {
            const itemData = menuItemsData[index];
            if (!itemData) return;

            let showItem = true;

            // Check search query
            if (currentSearchQuery) {
                const itemName = itemData.name.toLowerCase();
                if (!itemName.includes(currentSearchQuery)) {
                    showItem = false;
                }
            }

            // Check veg/non-veg filter
            if (showItem && currentFilter !== 'all') {
                const isVeg = itemData.isVeg;
                if (currentFilter === 'veg' && !isVeg) {
                    showItem = false;
                } else if (currentFilter === 'non-veg' && isVeg) {
                    showItem = false;
                }
            }

            item.style.display = showItem ? '' : 'none';
        });

        // Update section visibility
        menuSections.forEach(section => {
            const sectionItems = section.querySelectorAll('.menu-card');
            let sectionHasVisible = false;
            sectionItems.forEach(item => {
                if (item.style.display !== 'none') sectionHasVisible = true;
            });
            section.style.display = sectionHasVisible ? 'block' : 'none';
        });
    }

});
