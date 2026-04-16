// Menu Style 19 - Dark Theme with Left Sidebar Category Layout (Based on Style 12)

document.addEventListener('DOMContentLoaded', function() {
    // ========== BANNER CAROUSEL ==========
    const bannerTrack = document.querySelector('.s19-banner-track');
    const bannerSlides = document.querySelectorAll('.s19-banner-slide');
    const bannerDots = document.querySelectorAll('.s19-dot');
    let currentSlide = 0;
    let autoSlideInterval;

    if (bannerTrack && bannerSlides.length > 1) {
        function goToSlide(index) {
            if (index < 0) index = bannerSlides.length - 1;
            if (index >= bannerSlides.length) index = 0;
            currentSlide = index;
            bannerTrack.style.transform = 'translateX(-' + (currentSlide * 100) + '%)';
            bannerDots.forEach(function(dot, i) {
                dot.classList.toggle('active', i === currentSlide);
            });
        }

        function startAutoSlide() {
            if (autoSlideInterval) clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(function() { goToSlide(currentSlide + 1); }, 4000);
        }

        bannerDots.forEach(function(dot, i) {
            dot.addEventListener('click', function() { goToSlide(i); startAutoSlide(); });
        });

        // Touch swipe
        var touchStartX = 0;
        var bannerEl = document.querySelector('.s19-banner-carousel');
        if (bannerEl) {
            bannerEl.addEventListener('touchstart', function(e) { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
            bannerEl.addEventListener('touchend', function(e) {
                var diff = touchStartX - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 50) { goToSlide(currentSlide + (diff > 0 ? 1 : -1)); }
                startAutoSlide();
            }, { passive: true });
        }

        startAutoSlide();
    }

    // ========== MENU DATA ==========
    const menuItemsData = window.menuItemsData || [];

    // ========== INITIALIZE STYLE 19 SIDEBAR ==========
    if (typeof Style19CategorySidebar !== 'undefined') {
        const sidebar = new Style19CategorySidebar();
        sidebar.setMenuData(menuItemsData);
    } else {
        // Fallback initialization if class not available
        initFallbackSidebar();
    }

    // Always initialize search bar functionality
    initSearchBar();

    // ========== FALLBACK SIDEBAR INITIALIZATION ==========
    function initFallbackSidebar() {
        const categoryItems = document.querySelectorAll('.s19-cat-item');
        const menuSections = document.querySelectorAll('.s19-menu-section');

        // Category click events
        categoryItems.forEach(item => {
            item.addEventListener('click', function() {
                const category = this.dataset.category;

                // Update active state
                categoryItems.forEach(cat => cat.classList.remove('active'));
                this.classList.add('active');

                // Filter sections
                menuSections.forEach(section => {
                    const sectionCategory = section.dataset.category || section.getAttribute('id');

                    if (category === 'all' || sectionCategory === category) {
                        section.style.display = 'block';
                        // Re-trigger animations
                        section.querySelectorAll('.s19-menu-item').forEach((menuItem, i) => {
                            menuItem.style.animation = 'none';
                            menuItem.offsetHeight;
                            menuItem.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                        });
                    } else {
                        section.style.display = 'none';
                    }
                });
            });
        });
    }

    // ========== SEARCH BAR INITIALIZATION ==========
    function initSearchBar() {
        // New search bar (after banner) functionality
        const searchBtn = document.querySelector('.s19-search-btn');
        const searchBarSection = document.querySelector('.search-bar-section');
        const searchField = document.querySelector('.search-field');
        const clearSearchBtn = document.querySelector('.clear-search-btn');
        const filterDropdown = document.querySelector('.filter-dropdown');
        const filterBtn = document.querySelector('.filter-btn');
        const filterOptions = document.querySelectorAll('.filter-option');
        const filterText = document.querySelector('.filter-text');
        const filterBtnIcon = filterBtn ? filterBtn.querySelector('.filter-icon') : null;
        const menuSections = document.querySelectorAll('.s19-menu-section');
        const menuItems = document.querySelectorAll('.s19-menu-item');

        let currentFilter = 'all';
        let searchBarVisible = false;

        // Hide the old search overlay permanently
        const searchOverlay = document.querySelector('.s19-search-overlay');
        if (searchOverlay) {
            searchOverlay.style.display = 'none';
        }

        // Toggle search bar visibility - use capture to run before menu-common.js handler
        if (searchBtn && searchBarSection) {
            searchBtn.addEventListener('click', (e) => {
                e.stopImmediatePropagation();
                e.preventDefault();
                searchBarVisible = !searchBarVisible;
                searchBarSection.style.display = searchBarVisible ? 'block' : 'none';
                if (searchBarVisible && searchField) {
                    setTimeout(() => searchField.focus(), 100);
                } else {
                    // Reset search and filter when closing
                    if (searchField) searchField.value = '';
                    if (clearSearchBtn) clearSearchBtn.style.display = 'none';
                    currentFilter = 'all';
                    updateFilterUI('all');
                    applyFilters();
                }
            }, true);
        }

        // Search input handler
        if (searchField) {
            searchField.addEventListener('input', function(e) {
                const rawValue = e.target.value;
                if (clearSearchBtn) {
                    clearSearchBtn.style.display = rawValue.length > 0 ? 'flex' : 'none';
                }
                applyFilters();
            });
        }

        // Clear search button
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', () => {
                if (searchField) {
                    searchField.value = '';
                    searchField.focus();
                }
                clearSearchBtn.style.display = 'none';
                applyFilters();
            });
        }

        // Filter dropdown toggle
        if (filterBtn && filterDropdown) {
            filterBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                filterDropdown.classList.toggle('open');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!filterDropdown.contains(e.target)) {
                    filterDropdown.classList.remove('open');
                }
            });
        }

        // Filter option selection
        filterOptions.forEach(option => {
            option.addEventListener('click', () => {
                const filter = option.dataset.filter;
                currentFilter = filter;

                // Update active state
                filterOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');

                updateFilterUI(filter);
                filterDropdown.classList.remove('open');
                applyFilters();
            });
        });

        function updateFilterUI(filter) {
            if (filterText) {
                filterText.textContent = filter === 'all' ? 'All' : filter === 'veg' ? 'Veg' : 'Non-Veg';
            }
            if (filterBtnIcon) {
                filterBtnIcon.className = 'filter-icon ' + (filter === 'all' ? 'all-icon' : filter === 'veg' ? 'veg-icon' : 'non-veg-icon');
            }
        }

        function applyFilters() {
            const searchQuery = searchField ? searchField.value.toLowerCase().trim() : '';
            let hasVisibleItems = false;

            menuItems.forEach((item, index) => {
                const itemData = menuItemsData[index];
                if (!itemData) return;

                const nameMatch = !searchQuery || itemData.name.toLowerCase().includes(searchQuery);
                const categoryMatch = !searchQuery || itemData.category.toLowerCase().includes(searchQuery);
                const searchMatch = nameMatch || categoryMatch;

                let filterMatch = true;
                if (currentFilter === 'veg') {
                    filterMatch = itemData.isVeg === true;
                } else if (currentFilter === 'non-veg') {
                    filterMatch = itemData.isVeg === false;
                }

                const visible = searchMatch && filterMatch;
                item.style.display = visible ? '' : 'none';

                if (visible) hasVisibleItems = true;
            });

            // Show/hide sections based on visible items
            menuSections.forEach(section => {
                const visibleItems = section.querySelectorAll('.s19-menu-item[style=""], .s19-menu-item:not([style*="display: none"])');
                const hasVisible = Array.from(section.querySelectorAll('.s19-menu-item')).some(item => item.style.display !== 'none');
                section.style.display = hasVisible ? '' : 'none';
            });

            // Show no results message if needed
            const existingNoResults = document.querySelector('.no-results-message');
            if (existingNoResults) existingNoResults.remove();

            if (!hasVisibleItems && (searchQuery || currentFilter !== 'all')) {
                const contentArea = document.querySelector('.s19-main-content');
                if (contentArea) {
                    const noResults = document.createElement('div');
                    noResults.className = 'no-results-message';
                    noResults.innerHTML = '<i class="bi bi-emoji-frown"></i><p>No dishes found</p>';
                    contentArea.appendChild(noResults);
                }
            }
        }
    }

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
    var s19OaCatBtn = document.getElementById('s19-oa-cat-btn');
    var s19OaSection = document.getElementById('s19-oa-section');
    var s19OaGrid = document.getElementById('s19-oa-grid');
    var oaLoaded = false;
    var oaItems = [];
    var s19CatItems = document.querySelectorAll('.s19-cat-item');
    var s19MenuSections = document.querySelectorAll('.s19-menu-section');

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
                if (oaItems.length > 0 && s19OaCatBtn) {
                    s19OaCatBtn.style.display = '';
                    renderOaGrid();
                }
            }
        };
        xhr.send();
    }

    function buildS19OaCard(item) {
        var hasDiscount = item.price < item.mrp;
        var discountTag = '';
        if (hasDiscount) {
            discountTag = item.discount_type === 'percent'
                ? '<span class="s19-oa-offer">' + item.discount + '% OFF</span>'
                : '<span class="s19-oa-offer">\u20B9' + item.discount + ' OFF</span>';
        }
        return '<div class="s19-oa-card" data-item-id="' + item.id + '">' +
                '<div class="s19-oa-card-img">' +
                    '<img src="' + item.image + '" alt="' + item.name + '">' +
                    '<span class="s19-oa-veg ' + (item.veg == 1 ? 'veg' : 'nonveg') + '"></span>' +
                    discountTag +
                '</div>' +
                '<div class="s19-oa-body">' +
                    '<h4 class="s19-oa-name">' + item.name + '</h4>' +
                    '<div class="s19-oa-price-row">' +
                        '<span class="s19-oa-price">\u20B9' + item.price + '</span>' +
                        (hasDiscount ? '<span class="s19-oa-mrp"><s>\u20B9' + item.mrp + '</s></span>' : '') +
                        '<button class="s19-oa-add" data-oa-id="' + item.id + '" data-oa-name="' + item.name.replace(/"/g, '&quot;') + '" data-oa-price="' + item.price + '" data-oa-mrp="' + item.mrp + '" data-oa-img="' + item.image + '" data-oa-veg="' + (item.veg == 1 ? 'true' : 'false') + '"><i class="bi bi-plus-lg"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function renderOaGrid() {
        if (!s19OaGrid) return;
        var html = '';
        oaItems.forEach(function(item) { html += buildS19OaCard(item); });
        s19OaGrid.innerHTML = html;
        s19OaGrid.querySelectorAll('.s19-oa-add').forEach(function(btn) {
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
    if (s19OaCatBtn) {
        s19OaCatBtn.addEventListener('click', function() {
            s19CatItems.forEach(function(c) { c.classList.remove('active'); });
            s19OaCatBtn.classList.add('active');
            s19MenuSections.forEach(function(s) { s.style.display = 'none'; });
            if (s19OaSection) s19OaSection.style.display = '';
            var titleBar = document.querySelector('.category-title-bar');
            if (titleBar) titleBar.style.display = 'none';
        });
    }

    // When any other category is clicked, hide OA section and restore title bar
    s19CatItems.forEach(function(item) {
        item.addEventListener('click', function() {
            if (item === s19OaCatBtn) return;
            if (s19OaCatBtn) s19OaCatBtn.classList.remove('active');
            if (s19OaSection) s19OaSection.style.display = 'none';
            var titleBar = document.querySelector('.category-title-bar');
            if (titleBar) titleBar.style.display = '';
        });
    });

    loadOrderAgain();

});
