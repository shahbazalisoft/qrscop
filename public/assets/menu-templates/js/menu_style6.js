// Menu Style 12 - Flavour House with Item Images JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // ========== BANNER CAROUSEL ==========
    const bannerCarousel = document.querySelector('.banner-carousel');
    if (bannerCarousel) {
        const track = bannerCarousel.querySelector('.banner-track');
        const slides = bannerCarousel.querySelectorAll('.banner-slide');
        const dots = bannerCarousel.querySelectorAll('.dot');
        const prevBtn = bannerCarousel.querySelector('.banner-nav.prev');
        const nextBtn = bannerCarousel.querySelector('.banner-nav.next');

        let currentSlide = 0;
        let autoScrollInterval;
        const autoScrollDelay = 4000; // 4 seconds

        function goToSlide(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;

            currentSlide = index;
            track.style.transform = `translateX(-${currentSlide * 100}%)`;

            // Update dots
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        function startAutoScroll() {
            autoScrollInterval = setInterval(nextSlide, autoScrollDelay);
        }

        function stopAutoScroll() {
            clearInterval(autoScrollInterval);
        }

        // Navigation buttons
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoScroll();
                nextSlide();
                startAutoScroll();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoScroll();
                prevSlide();
                startAutoScroll();
            });
        }

        // Dots navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoScroll();
                goToSlide(index);
                startAutoScroll();
            });
        });

        // Pause on hover
        bannerCarousel.addEventListener('mouseenter', stopAutoScroll);
        bannerCarousel.addEventListener('mouseleave', startAutoScroll);

        // Touch support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        bannerCarousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoScroll();
        }, { passive: true });

        bannerCarousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
            }
            startAutoScroll();
        }, { passive: true });

        // Start auto-scroll
        startAutoScroll();
    }

    // Use dynamic menu items data from blade template
    const menuItemsData = window.menuItemsData || [];
    const menuItemElements = document.querySelectorAll('.menu-item');

    // ===== ORDER AGAIN SECTION =====
    var oaStoreId = window.storeId || '';
    var oaDeviceId = localStorage.getItem('menu_device_id') || '';

    function getOaSavedPhone() {
        return localStorage.getItem('menu_phone_' + oaStoreId) || '';
    }

    function loadOrderAgainItems() {
        var section = document.getElementById('s6-order-again');
        if (!section) return;
        var phone = getOaSavedPhone();
        var url = '/menu-cart/ordered-items?store_id=' + oaStoreId + '&device_id=' + oaDeviceId + (phone ? '&phone=' + encodeURIComponent(phone) : '');
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
                var viewAllBtn = document.getElementById('s6-oa-viewall');
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
                ? '<span class="s6-oa-offer">' + item.discount + '% OFF</span>'
                : '<span class="s6-oa-offer">\u20B9' + item.discount + ' OFF</span>';
        }
        return '<div class="s6-oa-card" data-item-id="' + item.id + '">' +
                '<div class="s6-oa-img-wrap">' +
                    '<img src="' + imgSrc + '" alt="' + item.name + '">' +
                    '<span class="s6-oa-veg-badge ' + (item.veg == 1 ? 'veg' : 'nonveg') + '"></span>' +
                    discountTag +
                '</div>' +
                '<div class="s6-oa-body">' +
                    '<h4 class="s6-oa-name">' + item.name + '</h4>' +
                    '<div class="s6-oa-price-row">' +
                        '<span class="s6-oa-price">\u20B9' + item.price + '</span>' +
                        (hasDiscount ? '<span class="s6-oa-mrp"><s>\u20B9' + item.mrp + '</s></span>' : '') +
                        '<button class="s6-oa-add-btn" data-oa-id="' + item.id + '" data-oa-name="' + item.name.replace(/"/g, '&quot;') + '" data-oa-price="' + item.price + '" data-oa-mrp="' + item.mrp + '" data-oa-img="' + imgSrc + '" data-oa-veg="' + (item.veg == 1 ? 'true' : 'false') + '"><i class="bi bi-plus-lg"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function attachOaAddHandlers(container) {
        container.querySelectorAll('.s6-oa-add-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var itemId = parseInt(btn.dataset.oaId);
                var menuItem = menuItemsData.find(function (m) { return m.id === itemId; });
                if (menuItem && menuItem.foodVariations && menuItem.foodVariations.length > 0 &&
                    menuItem.foodVariations.some(function (v) { return v.values && v.values.length > 0; })) {
                    var idx = menuItemsData.indexOf(menuItem);
                    if (typeof openSizePicker === 'function') {
                        openSizePicker(idx, btn);
                    }
                    return;
                }
                if (window.menuCart) {
                    var origHTML = btn.innerHTML;
                    var domItem = document.querySelector('[data-index][data-item-id="' + itemId + '"]');
                    var dataIndex = domItem ? parseInt(domItem.dataset.index) : menuItemsData.findIndex(function (m) { return m.id === itemId; });
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
        var scrollContainer = document.querySelector('.s6-order-again-scroll');
        var gridContainer = document.getElementById('s6-oa-grid');
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
    var oaViewAllBtn = document.getElementById('s6-oa-viewall');
    var oaExpanded = false;
    if (oaViewAllBtn) {
        oaViewAllBtn.addEventListener('click', function () {
            var scrollContainer = document.querySelector('.s6-order-again-scroll');
            var gridContainer = document.getElementById('s6-oa-grid');
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

    // Category Image Navigation
    const catItems = document.querySelectorAll('.cat-img-item');
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    const sections = document.querySelectorAll('.menu-section');
    const moreBtn = document.querySelector('.more-btn');
    const moreDropdown = document.querySelector('.more-dropdown');

    // Category image items click handler - scroll to section
    catItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.dataset.category;
            if (category === 'view-all') return;

            // Update active state
            catItems.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            if (category === 'all') {
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            // Scroll to section
            const targetSection = document.getElementById(category);
            if (targetSection) {
                const headerHeight = document.querySelector('.header') ? document.querySelector('.header').offsetHeight : 0;
                const wrapperHeight = document.querySelector('.category-sticky-wrapper') ? document.querySelector('.category-sticky-wrapper').offsetHeight : 0;
                const targetPosition = targetSection.offsetTop - headerHeight - wrapperHeight - 10;
                window.scrollTo({ top: Math.max(0, targetPosition), behavior: 'smooth' });
            }

            this.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
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
    const menuItems = document.querySelectorAll('.menu-item');

    let currentFilter = 'all';
    let currentSearchQuery = '';

    // Toggle search bar visibility
    if (searchBtn && searchBarSection) {
        searchBtn.addEventListener('click', function() {
            const isVisible = searchBarSection.style.display !== 'none';
            searchBarSection.style.display = isVisible ? 'none' : 'block';
            if (!isVisible && searchField) {
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
        menuItems.forEach((item, index) => {
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

            item.style.display = showItem ? 'flex' : 'none';
        });

        // Update section visibility based on visible items
        sections.forEach(section => {
            const visibleItems = section.querySelectorAll('.menu-item[style="display: flex;"], .menu-item:not([style*="display"])');
            let hasVisible = false;
            visibleItems.forEach(item => {
                if (item.style.display !== 'none') hasVisible = true;
            });

            // Check if any items in this section are visible
            const sectionItems = section.querySelectorAll('.menu-item');
            let sectionHasVisible = false;
            sectionItems.forEach(item => {
                if (item.style.display !== 'none') sectionHasVisible = true;
            });

            section.style.display = sectionHasVisible ? 'block' : 'none';
        });
    }
});
