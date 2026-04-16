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
            applyVegFilter(); // Use the combined filter function
            searchInput.focus();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', debounce(function(e) {
            updateClearButton();
            applyVegFilter(); // Use the combined filter function
        }, 300));
    }

    // ========== VEG/NON-VEG FILTER ==========
    const filterDropdown = document.getElementById('filter-dropdown');
    const filterBtn = document.getElementById('filter-btn');
    const filterOptions = document.querySelectorAll('.filter-option');
    const filterIcon = document.getElementById('filter-icon');
    const filterText = document.getElementById('filter-text');
    let currentFilter = 'all';

    // Toggle filter dropdown
    if (filterBtn && filterDropdown) {
        filterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            filterDropdown.classList.toggle('open');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!filterDropdown.contains(e.target)) {
                filterDropdown.classList.remove('open');
            }
        });
    }

    // Filter option selection
    filterOptions.forEach(option => {
        option.addEventListener('click', function() {
            const filter = this.dataset.filter;
            currentFilter = filter;

            // Update selected state
            filterOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');

            // Update button text and icon
            if (filter === 'all') {
                filterIcon.innerHTML = '<i class="bi bi-funnel"></i>';
                filterIcon.className = 'filter-icon';
                filterText.textContent = 'All';
                filterBtn.classList.remove('active');
            } else if (filter === 'veg') {
                filterIcon.innerHTML = '';
                filterIcon.className = 'filter-icon veg';
                filterText.textContent = 'Veg';
                filterBtn.classList.add('active');
            } else if (filter === 'non-veg') {
                filterIcon.innerHTML = '';
                filterIcon.className = 'filter-icon non-veg';
                filterText.textContent = 'Non-Veg';
                filterBtn.classList.add('active');
            }

            // Close dropdown
            filterDropdown.classList.remove('open');

            // Apply filter
            applyVegFilter();
        });
    });

    // Apply veg/non-veg filter
    function applyVegFilter() {
        menuSections.forEach(section => {
            const cards = section.querySelectorAll('.menu-card');
            let hasVisibleCard = false;

            cards.forEach(card => {
                const itemType = card.querySelector('.item-type');
                const isVeg = itemType && itemType.classList.contains('veg');
                const isNonVeg = itemType && itemType.classList.contains('non-veg');

                let shouldShow = true;

                if (currentFilter === 'veg') {
                    shouldShow = isVeg;
                } else if (currentFilter === 'non-veg') {
                    shouldShow = isNonVeg;
                }

                // Also check search query if any
                if (shouldShow && searchInput && searchInput.value.trim() !== '') {
                    const query = searchInput.value.toLowerCase().trim();
                    const title = card.querySelector('.card-title').textContent.toLowerCase();
                    shouldShow = title.includes(query);
                }

                // Also check active category
                const activeCategory = document.querySelector('.category-item.active');
                if (shouldShow && activeCategory) {
                    const category = activeCategory.dataset.category;
                    if (category !== 'all') {
                        const cardSection = card.closest('.menu-section');
                        const sectionCategory = cardSection.dataset.category || cardSection.getAttribute('id');
                        shouldShow = sectionCategory === category;
                    }
                }

                if (shouldShow) {
                    card.style.display = 'block';
                    hasVisibleCard = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Check if section should be visible based on active category
            const activeCategory = document.querySelector('.category-item.active');
            if (activeCategory) {
                const category = activeCategory.dataset.category;
                const sectionCategory = section.dataset.category || section.getAttribute('id');

                if (category !== 'all' && sectionCategory !== category) {
                    section.style.display = 'none';
                } else {
                    section.style.display = hasVisibleCard ? 'block' : 'none';
                }
            } else {
                section.style.display = hasVisibleCard ? 'block' : 'none';
            }
        });
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
                updateClearButton();
            }

            // Apply combined filter (category + veg filter)
            applyVegFilter();

            // Animate visible cards
            menuSections.forEach(section => {
                if (section.style.display !== 'none') {
                    section.querySelectorAll('.menu-card').forEach((card, i) => {
                        if (card.style.display !== 'none') {
                            card.style.animation = 'none';
                            card.offsetHeight;
                            card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                        }
                    });
                }
            });

            // Scroll to top of products area
            const productsArea = document.querySelector('.products-area');
            if (productsArea) {
                productsArea.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    // ========== MENU DATA ==========
    const menuItemsData = window.menuItemsData || [];

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
            menuSections.forEach(function(s) { s.style.display = 'none'; });
            if (oaSection) oaSection.style.display = '';
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

    loadOrderAgain();
});
