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

    // ========== SIDEBAR SCROLL UP/DOWN BUTTONS ==========
    const categorySidebar = document.querySelector('.category-sidebar');
    const scrollUpBtn = document.querySelector('.sidebar-scroll-btn.scroll-up');
    const scrollDownBtn = document.querySelector('.sidebar-scroll-btn.scroll-down');
    const categoryList = document.querySelector('.category-list');

    function updateScrollButtons() {
        if (!categorySidebar || !scrollUpBtn || !scrollDownBtn) return;
        const scrollTop = categorySidebar.scrollTop;
        const scrollHeight = categorySidebar.scrollHeight;
        const clientHeight = categorySidebar.clientHeight;

        scrollUpBtn.classList.toggle('hidden', scrollTop <= 5);
        scrollDownBtn.classList.toggle('hidden', scrollTop + clientHeight >= scrollHeight - 5);
    }

    if (categorySidebar) {
        categorySidebar.addEventListener('scroll', updateScrollButtons);
        // Initial check after layout
        setTimeout(updateScrollButtons, 100);
        window.addEventListener('resize', updateScrollButtons);
    }

    if (scrollUpBtn) {
        scrollUpBtn.addEventListener('click', function() {
            categorySidebar.scrollBy({ top: -160, behavior: 'smooth' });
        });
    }

    if (scrollDownBtn) {
        scrollDownBtn.addEventListener('click', function() {
            categorySidebar.scrollBy({ top: 160, behavior: 'smooth' });
        });
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

    // ========== MENU DATA (from blade template) ==========
    const menuItemsData = window.menuItemsData || [];

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

    // ========== SEARCH BAR & FILTER ==========
    const searchBtn = document.querySelector('.search-btn');
    const searchBarSection = document.querySelector('.search-bar-section');
    const searchField = document.querySelector('.search-field');
    const clearSearchBtn = document.querySelector('.clear-search-btn');
    const filterDropdown = document.querySelector('.filter-dropdown');
    const filterBtn = document.querySelector('.filter-btn');
    const filterOptions = document.querySelectorAll('.filter-option');

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
            applySearchFilter();
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
            applySearchFilter();
        });
    }

    // Clear search
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchField.value = '';
            currentSearchQuery = '';
            clearSearchBtn.style.display = 'none';
            searchField.focus();
            applySearchFilter();
        });
    }

    // Apply search and filter
    function applySearchFilter() {
        menuCards.forEach((card, index) => {
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

            card.style.display = showItem ? '' : 'none';
        });

        // Update section visibility
        menuSections.forEach(section => {
            const sectionCards = section.querySelectorAll('.menu-card');
            let sectionHasVisible = false;
            sectionCards.forEach(card => {
                if (card.style.display !== 'none') sectionHasVisible = true;
            });
            section.style.display = sectionHasVisible ? 'block' : 'none';
        });
    }

    // ===== ORDER AGAIN =====
    var oaStoreId = window.storeId || '';
    var oaDeviceId = localStorage.getItem('menu_device_id') || '';
    var oaCatBtn = document.getElementById('oa-cat-btn');
    var oaSection = document.getElementById('oa-section');
    var oaGrid = document.getElementById('oa-grid');
    var oaLoaded = false;
    var oaItems = [];

    function getOaPhone() {
        return localStorage.getItem('menu_phone_' + oaStoreId) || '';
    }

    function loadOrderAgain() {
        if (oaLoaded) return;
        oaLoaded = true;
        var phone = getOaPhone();
        var url = '/menu-cart/ordered-items?store_id=' + oaStoreId + '&device_id=' + oaDeviceId + (phone ? '&phone=' + encodeURIComponent(phone) : '');
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                oaItems = res.items || [];
                if (oaItems.length > 0 && oaCatBtn) {
                    oaCatBtn.style.display = '';
                    renderOaGrid();
                }
            }
        };
        xhr.send();
    }

    function buildOaCard(item) {
        var hasDiscount = item.price < item.mrp;
        var discountTag = '';
        if (hasDiscount) {
            discountTag = item.discount_type === 'percent'
                ? '<span class="oa-card-offer">' + item.discount + '% OFF</span>'
                : '<span class="oa-card-offer">\u20B9' + item.discount + ' OFF</span>';
        }
        return '<div class="oa-card" data-item-id="' + item.id + '">' +
                '<div class="oa-card-img">' +
                    '<img src="' + item.image + '" alt="' + item.name + '">' +
                    '<span class="oa-card-veg ' + (item.veg == 1 ? 'veg' : 'nonveg') + '"></span>' +
                    discountTag +
                '</div>' +
                '<div class="oa-card-body">' +
                    '<h4 class="oa-card-name">' + item.name + '</h4>' +
                    '<div class="oa-card-price-row">' +
                        '<span class="oa-card-price">\u20B9' + item.price + '</span>' +
                        (hasDiscount ? '<span class="oa-card-mrp"><s>\u20B9' + item.mrp + '</s></span>' : '') +
                        '<button class="oa-card-add" data-oa-id="' + item.id + '" data-oa-name="' + item.name.replace(/"/g, '&quot;') + '" data-oa-price="' + item.price + '" data-oa-mrp="' + item.mrp + '" data-oa-img="' + item.image + '" data-oa-veg="' + (item.veg == 1 ? 'true' : 'false') + '"><i class="bi bi-plus-lg"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function renderOaGrid() {
        if (!oaGrid) return;
        var html = '';
        oaItems.forEach(function(item) { html += buildOaCard(item); });
        oaGrid.innerHTML = html;
        oaGrid.querySelectorAll('.oa-card-add').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                var itemId = parseInt(btn.dataset.oaId);
                var menuItem = menuItemsData.find(function(m) { return m.id === itemId; });
                if (menuItem && menuItem.foodVariations && menuItem.foodVariations.length > 0 &&
                    menuItem.foodVariations.some(function(v) { return v.values && v.values.length > 0; })) {
                    var idx = menuItemsData.indexOf(menuItem);
                    if (typeof openSizePicker === 'function') openSizePicker(idx, btn);
                    return;
                }
                if (window.menuCart) {
                    var origHTML = btn.innerHTML;
                    var domItem = document.querySelector('[data-index][data-item-id="' + itemId + '"]');
                    var dataIndex = domItem ? parseInt(domItem.dataset.index) : menuItemsData.findIndex(function(m) { return m.id === itemId; });
                    if (dataIndex === -1) dataIndex = 0;
                    window.menuCart.addToCart({
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
                    }, function() {
                        btn.innerHTML = '<i class="bi bi-check"></i>';
                        setTimeout(function() { btn.innerHTML = origHTML; }, 500);
                    });
                }
            });
        });
    }

    // Handle Order Again sidebar button click
    if (oaCatBtn) {
        oaCatBtn.addEventListener('click', function() {
            categoryItems.forEach(function(c) { c.classList.remove('active'); });
            oaCatBtn.classList.add('active');
            // Hide all menu sections, show OA section
            menuSections.forEach(function(s) { s.style.display = 'none'; });
            if (oaSection) oaSection.style.display = '';
            // Hide category title bar
            var titleBar = document.querySelector('.category-title-bar');
            if (titleBar) titleBar.style.display = 'none';
        });
    }

    // When any other category is clicked, hide OA section and restore title bar
    categoryItems.forEach(function(item) {
        if (item === oaCatBtn) return;
        item.addEventListener('click', function() {
            if (oaCatBtn) oaCatBtn.classList.remove('active');
            if (oaSection) oaSection.style.display = 'none';
            var titleBar = document.querySelector('.category-title-bar');
            if (titleBar) titleBar.style.display = '';
        });
    });

    // Load on init
    loadOrderAgain();
});
