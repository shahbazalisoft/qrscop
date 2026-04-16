// Menu Style 10 - Cafe Royale Light Theme JavaScript
// Uses common utilities from menu-common.js

document.addEventListener('DOMContentLoaded', function() {
    // ========== STICKY HEADER ON SCROLL ==========
    const stickyHeader = document.querySelector('.sticky-header');
    const heroSection = document.querySelector('.hero-section');
    const stickySearchBtn = document.querySelector('.sticky-search-btn');

    if (stickyHeader && heroSection) {
        let heroHeight = heroSection.offsetHeight;

        // Update hero height on resize
        window.addEventListener('resize', () => {
            heroHeight = heroSection.offsetHeight;
        });

        // Handle scroll
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;

            // Show sticky header after scrolling past 60% of hero
            if (scrollY > heroHeight * 0.6) {
                stickyHeader.classList.add('visible');
                document.body.classList.add('sticky-visible');
                heroSection.classList.add('scrolled');
            } else {
                stickyHeader.classList.remove('visible');
                document.body.classList.remove('sticky-visible');
                heroSection.classList.remove('scrolled');
            }
        });
    }

    // ========== HERO IMAGE AUTO-SLIDER ==========
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
        const heroSlides = heroSlider.querySelectorAll('.hero-bg');
        if (heroSlides.length > 1) {
            let currentSlide = 0;
            const slideInterval = 4000; // Change image every 4 seconds

            function nextSlide() {
                heroSlides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % heroSlides.length;
                heroSlides[currentSlide].classList.add('active');
            }

            // Start auto-sliding after a brief delay to ensure images are ready
            setTimeout(function() {
                setInterval(nextSlide, slideInterval);
            }, 1000);
        }
    }

    // ========== SEARCH BAR & FILTER ==========
    const searchBtn = document.querySelector('.search-btn');
    const searchBarSection = document.querySelector('.search-bar-section');
    const searchField = document.querySelector('.search-field');
    const clearSearchBtn = document.querySelector('.clear-search-btn');
    const filterDropdown = document.querySelector('.filter-dropdown');
    const filterBtn = document.querySelector('.filter-btn');
    const filterOptions = document.querySelectorAll('.filter-option');
    const menuSections = document.querySelectorAll('.menu-section');

    let currentFilter = 'all';
    let currentSearchQuery = '';

    // Toggle search bar visibility
    function toggleSearchBar() {
        const isVisible = searchBarSection.style.display !== 'none';
        searchBarSection.style.display = isVisible ? 'none' : 'block';
        if (!isVisible && searchField) {
            setTimeout(() => searchField.focus(), 100);
        }
    }

    if (searchBtn && searchBarSection) {
        searchBtn.addEventListener('click', toggleSearchBar);
    }

    // Sticky header search button
    if (stickySearchBtn && searchBarSection) {
        stickySearchBtn.addEventListener('click', toggleSearchBar);
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
        if (filterDropdown && !e.target.closest('.filter-dropdown')) {
            filterDropdown.classList.remove('active');
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
            if (filterBtn) {
                filterBtn.querySelector('.filter-icon').className = 'filter-icon ' + iconClass;
                filterBtn.querySelector('.filter-text').textContent = text;
            }

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
            if (searchField) {
                searchField.value = '';
                currentSearchQuery = '';
                clearSearchBtn.style.display = 'none';
                searchField.focus();
            }
            applyFilters();
        });
    }

    // Apply search and filter
    function applyFilters() {
        const menuItemElements = document.querySelectorAll('.menu-item');

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

        // Update section visibility based on visible items
        menuSections.forEach(section => {
            const sectionItems = section.querySelectorAll('.menu-item');
            let sectionHasVisible = false;
            sectionItems.forEach(item => {
                if (item.style.display !== 'none') sectionHasVisible = true;
            });

            section.style.display = sectionHasVisible ? '' : 'none';
        });
    }

    // Use dynamic menu items data from blade template
    const menuItemsData = window.menuItemsData || [];

    // ===== ORDER AGAIN SECTION =====
    var storeId = window.storeId || '';
    var deviceId = localStorage.getItem('menu_device_id') || '';
    var menuItems = window.menuItemsData || [];

    function getSavedPhone() {
        return localStorage.getItem('menu_phone_' + storeId) || '';
    }

    function loadOrderAgainItems() {
        var section = document.getElementById('s4-order-again');
        if (!section) return;
        var phone = getSavedPhone();
        var url = '/menu-cart/ordered-items?store_id=' + storeId + '&device_id=' + deviceId + (phone ? '&phone=' + encodeURIComponent(phone) : '');
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                var items = res.items || [];
                if (items.length === 0) {
                    section.style.display = 'none';
                    return;
                }
                renderOrderAgainItems(items);
                section.style.display = '';
                var viewAllBtn = document.getElementById('s4-oa-viewall');
                if (viewAllBtn) viewAllBtn.style.display = '';
            }
        };
        xhr.send();
    }

    function buildOaCardHtml(item) {
        var imgSrc = item.image;
        var hasDiscount = item.price < item.mrp;
        var discountTag = '';
        if (hasDiscount) {
            discountTag = item.discount_type === 'percent'
                ? '<span class="s4-oa-offer">' + item.discount + '% OFF</span>'
                : '<span class="s4-oa-offer">\u20B9' + item.discount + ' OFF</span>';
        }
        return '<div class="s4-oa-card" data-item-id="' + item.id + '">' +
                '<div class="s4-oa-img-wrap">' +
                    '<img src="' + imgSrc + '" alt="' + item.name + '">' +
                    '<span class="s4-oa-veg-badge ' + (item.veg == 1 ? 'veg' : 'nonveg') + '"></span>' +
                    discountTag +
                '</div>' +
                '<div class="s4-oa-body">' +
                    '<h4 class="s4-oa-name">' + item.name + '</h4>' +
                    '<div class="s4-oa-price-row">' +
                        '<span class="s4-oa-price">\u20B9' + item.price + '</span>' +
                        (hasDiscount ? '<span class="s4-oa-mrp"><s>\u20B9' + item.mrp + '</s></span>' : '') +
                        '<button class="s4-oa-add-btn" data-oa-id="' + item.id + '" data-oa-name="' + item.name.replace(/"/g, '&quot;') + '" data-oa-price="' + item.price + '" data-oa-mrp="' + item.mrp + '" data-oa-img="' + imgSrc + '" data-oa-veg="' + (item.veg == 1 ? 'true' : 'false') + '"><i class="bi bi-plus-lg"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function attachOaAddHandlers(container) {
        container.querySelectorAll('.s4-oa-add-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var itemId = parseInt(btn.dataset.oaId);
                var menuItem = menuItems.find(function (m) { return m.id === itemId; });
                if (menuItem && menuItem.foodVariations && menuItem.foodVariations.length > 0 &&
                    menuItem.foodVariations.some(function (v) { return v.values && v.values.length > 0; })) {
                    var idx = menuItems.indexOf(menuItem);
                    if (typeof openSizePicker === 'function') {
                        openSizePicker(idx, btn);
                    }
                    return;
                }
                if (window.menuCart) {
                    var origHTML = btn.innerHTML;
                    var domItem = document.querySelector('[data-index][data-item-id="' + itemId + '"]');
                    var dataIndex = domItem ? parseInt(domItem.dataset.index) : menuItems.findIndex(function (m) { return m.id === itemId; });
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
                    }, function () {
                        btn.innerHTML = '<i class="bi bi-check"></i>';
                        setTimeout(function () { btn.innerHTML = origHTML; }, 500);
                    });
                }
            });
        });
    }

    function renderOrderAgainItems(items) {
        var scrollContainer = document.querySelector('.s4-order-again-scroll');
        var gridContainer = document.getElementById('s4-oa-grid');
        if (!scrollContainer) return;

        var html = '';
        items.forEach(function (item) { html += buildOaCardHtml(item); });
        scrollContainer.innerHTML = html;
        attachOaAddHandlers(scrollContainer);

        if (gridContainer) {
            gridContainer.innerHTML = html;
            attachOaAddHandlers(gridContainer);
        }
    }

    // View All toggle
    var oaViewAllBtn = document.getElementById('s4-oa-viewall');
    var oaExpanded = false;
    if (oaViewAllBtn) {
        oaViewAllBtn.addEventListener('click', function () {
            var scrollContainer = document.querySelector('.s4-order-again-scroll');
            var gridContainer = document.getElementById('s4-oa-grid');
            oaExpanded = !oaExpanded;
            if (oaExpanded) {
                scrollContainer.style.display = 'none';
                gridContainer.style.display = '';
                oaViewAllBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
            } else {
                scrollContainer.style.display = '';
                gridContainer.style.display = 'none';
                oaViewAllBtn.innerHTML = '<i class="bi bi-grid"></i>';
            }
        });
    }

    // Load order again items
    loadOrderAgainItems();

    // Initialize Category Navigation using common utility
    if (typeof CategoryNavigation === 'function') {
        new CategoryNavigation({
            btnSelector: '.tab-btn',
            sectionSelector: '.menu-section',
            activeClass: 'active',
            dataAttribute: 'category',
            defaultCategory: 'recommended',
            scrollOffset: 120
        });
    }

});
