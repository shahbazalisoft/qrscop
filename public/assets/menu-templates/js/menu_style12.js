// Initialize Style 20 functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Menu items data for search/filter
            const menuItemsData = window.menuItemsData || [];

            // ========== CATEGORY NAV & POPUP ==========
            initCategoryNav();

            // Search Bar Initialization
            initSearchBar();

            function initCategoryNav() {
                const viewAllBtn = document.getElementById('s20-viewall-btn');
                const catOverlay = document.getElementById('s20-cat-popup-overlay');
                const catPopup = document.getElementById('s20-cat-popup');
                const catCloseBtn = document.getElementById('s20-cat-popup-close');
                const catPopupItems = document.querySelectorAll('.s20-cat-popup-item');
                const catNavItems = document.querySelectorAll('.s20-cat-item');
                const menuSections = document.querySelectorAll('.s20-menu-section');

                function openPopup() {
                    if (catOverlay) catOverlay.classList.add('active');
                    if (catPopup) catPopup.classList.add('active');
                }

                function closePopup() {
                    if (catOverlay) catOverlay.classList.remove('active');
                    if (catPopup) catPopup.classList.remove('active');
                }

                function selectCategory(category) {
                    // Update nav active state
                    catNavItems.forEach(c => c.classList.remove('active'));
                    const activeNav = document.querySelector('.s20-cat-item[data-category="' + category + '"]');
                    if (activeNav) activeNav.classList.add('active');

                    // Update popup active state
                    catPopupItems.forEach(item => {
                        item.classList.toggle('active', item.dataset.category === category);
                    });

                    // Filter sections
                    menuSections.forEach(section => {
                        if (category === 'all') {
                            section.style.display = '';
                            section.querySelectorAll('.s20-menu-card').forEach((card, i) => {
                                card.style.animation = 'none';
                                card.offsetHeight;
                                card.style.animation = 'fadeInUp 0.4s ease forwards ' + ((i + 1) * 0.05) + 's';
                            });
                        } else {
                            const sectionId = section.getAttribute('id');
                            if (sectionId === category) {
                                section.style.display = '';
                                section.querySelectorAll('.s20-menu-card').forEach((card, i) => {
                                    card.style.animation = 'none';
                                    card.offsetHeight;
                                    card.style.animation = 'fadeInUp 0.4s ease forwards ' + ((i + 1) * 0.05) + 's';
                                });
                            } else {
                                section.style.display = 'none';
                            }
                        }
                    });
                }

                // Category nav click handlers
                catNavItems.forEach(item => {
                    item.addEventListener('click', function() {
                        selectCategory(this.dataset.category);
                    });
                });

                // Banner carousel
                initBannerCarousel();

                // View All popup
                if (viewAllBtn) viewAllBtn.addEventListener('click', openPopup);
                if (catOverlay) catOverlay.addEventListener('click', closePopup);
                if (catCloseBtn) catCloseBtn.addEventListener('click', closePopup);

                catPopupItems.forEach(item => {
                    item.addEventListener('click', function() {
                        selectCategory(this.dataset.category);
                        closePopup();
                    });
                });
            }

            function initBannerCarousel() {
                const bannerTrack = document.querySelector('.s20-banner-track');
                const bannerSlides = document.querySelectorAll('.s20-banner-slide');
                const bannerDots = document.querySelectorAll('.s20-banner-dots .dot');
                let currentSlide = 0;
                let autoSlideInterval;

                if (!bannerTrack || bannerSlides.length <= 1) return;

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
                var bannerEl = document.querySelector('.s20-banner-carousel');
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

            function initSearchBar() {
                const searchBtn = document.querySelector('.s20-search-btn');
                const searchBarSection = document.querySelector('.search-bar-section');
                const searchField = document.querySelector('.search-field');
                const clearSearchBtn = document.querySelector('.clear-search-btn');
                const filterDropdown = document.querySelector('.filter-dropdown');
                const filterBtn = document.querySelector('.filter-btn');
                const filterOptions = document.querySelectorAll('.filter-option');
                const filterText = document.querySelector('.filter-text');
                const filterBtnIcon = filterBtn ? filterBtn.querySelector('.filter-icon') : null;
                const menuSections = document.querySelectorAll('.s20-menu-section');
                const menuCards = document.querySelectorAll('.s20-menu-card');

                let currentFilter = 'all';
                let searchBarVisible = false;

                // Hide old search overlay
                const searchOverlay = document.querySelector('.s20-search-overlay');
                if (searchOverlay) {
                    searchOverlay.style.display = 'none';
                }

                // Toggle search bar visibility
                if (searchBtn && searchBarSection) {
                    searchBtn.addEventListener('click', (e) => {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        searchBarVisible = !searchBarVisible;
                        searchBarSection.style.display = searchBarVisible ? 'block' : 'none';
                        if (searchBarVisible && searchField) {
                            setTimeout(() => searchField.focus(), 100);
                        } else {
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

                    menuCards.forEach((card, index) => {
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
                        card.style.display = visible ? '' : 'none';

                        if (visible) hasVisibleItems = true;
                    });

                    // Show/hide sections based on visible items
                    menuSections.forEach(section => {
                        const hasVisible = Array.from(section.querySelectorAll('.s20-menu-card')).some(card => card.style.display !== 'none');
                        section.style.display = hasVisible ? '' : 'none';
                    });

                    // Show no results message if needed
                    const existingNoResults = document.querySelector('.no-results-message');
                    if (existingNoResults) existingNoResults.remove();

                    if (!hasVisibleItems && (searchQuery || currentFilter !== 'all')) {
                        const mainContent = document.querySelector('.s20-main-content');
                        if (mainContent) {
                            const noResults = document.createElement('div');
                            noResults.className = 'no-results-message';
                            noResults.innerHTML = '<i class="bi bi-emoji-frown"></i><p>No dishes found</p>';
                            mainContent.appendChild(noResults);
                        }
                    }
                }
            }

            // ========== ORDER AGAIN ==========
            var storeId = window.storeId || '';
            var deviceId = localStorage.getItem('menu_device_id') || '';
            var menuItems = window.menuItemsData || [];

            function getSavedPhone() {
                return localStorage.getItem('menu_phone_' + storeId) || '';
            }

            function loadOrderAgainItems() {
                var section = document.getElementById('s20-order-again');
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
                        var viewAllBtn = document.getElementById('s20-oa-viewall');
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
                        ? '<span class="s20-oa-offer">' + item.discount + '% OFF</span>'
                        : '<span class="s20-oa-offer">\u20B9' + item.discount + ' OFF</span>';
                }
                return '<div class="s20-oa-card" data-item-id="' + item.id + '">' +
                        '<div class="s20-oa-img-wrap">' +
                            '<img src="' + imgSrc + '" alt="' + item.name + '">' +
                            '<span class="s20-oa-veg-badge ' + (item.veg == 1 ? 'veg' : 'nonveg') + '"></span>' +
                            discountTag +
                        '</div>' +
                        '<div class="s20-oa-body">' +
                            '<h4 class="s20-oa-name">' + item.name + '</h4>' +
                            '<div class="s20-oa-price-row">' +
                                '<span class="s20-oa-price">\u20B9' + item.price + '</span>' +
                                (hasDiscount ? '<span class="s20-oa-mrp"><s>\u20B9' + item.mrp + '</s></span>' : '') +
                                '<button class="s20-oa-add-btn" data-oa-id="' + item.id + '" data-oa-name="' + item.name.replace(/"/g, '&quot;') + '" data-oa-price="' + item.price + '" data-oa-mrp="' + item.mrp + '" data-oa-img="' + imgSrc + '" data-oa-veg="' + (item.veg == 1 ? 'true' : 'false') + '"><i class="bi bi-plus-lg"></i></button>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
            }

            function attachOaAddHandlers(container) {
                container.querySelectorAll('.s20-oa-add-btn').forEach(function (btn) {
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
                var scrollContainer = document.querySelector('.s20-order-again-scroll');
                var gridContainer = document.getElementById('s20-oa-grid');
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
            var oaViewAllBtn = document.getElementById('s20-oa-viewall');
            var oaExpanded = false;
            if (oaViewAllBtn) {
                oaViewAllBtn.addEventListener('click', function () {
                    var scrollContainer = document.querySelector('.s20-order-again-scroll');
                    var gridContainer = document.getElementById('s20-oa-grid');
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
        });