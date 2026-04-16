// Menu Style 19 - Dark Theme with Left Sidebar Category Layout (Based on Style 12)

document.addEventListener('DOMContentLoaded', function() {
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

    // Get all add buttons
    const addBtns = document.querySelectorAll('.s19-add-btn');

    // ========== SYNC CART WITH ADD BUTTONS ==========
    function syncAddButtonsWithCart() {
        if (!window.menuCart) return;

        const cartData = window.menuCart.getCartData();

        addBtns.forEach((btn, index) => {
            const itemData = menuItemsData[index];
            if (!itemData) return;

            const icon = btn.querySelector('i');

            // Check if this item (or any variation of it) is in cart
            const inCart = cartData.some(cartItem =>
                cartItem.name.startsWith(itemData.name)
            );

            if (inCart) {
                btn.classList.add('added');
                if (icon) {
                    icon.classList.remove('bi-plus');
                    icon.classList.add('bi-check');
                }
            } else {
                btn.classList.remove('added');
                if (icon) {
                    icon.classList.remove('bi-check');
                    icon.classList.add('bi-plus');
                }
            }
        });
    }

    // Override cart's renderCart to sync buttons after cart changes
    function setupCartSync() {
        if (window.menuCart) {
            const originalRenderCart = window.menuCart.renderCart.bind(window.menuCart);
            window.menuCart.renderCart = function() {
                originalRenderCart();
                syncAddButtonsWithCart();
            };

            // Initial sync
            syncAddButtonsWithCart();
        }
    }

    // Setup cart sync after a small delay to ensure cart is initialized
    setTimeout(setupCartSync, 100);

    // ========== INITIALIZE STYLE 19 SIDEBAR ==========
    if (typeof Style19CategorySidebar !== 'undefined') {
        const sidebar = new Style19CategorySidebar();
        sidebar.setMenuData(menuItemsData);
    } else {
        // Fallback initialization if class not available
        initFallbackSidebar();
        initFallbackSearch();
    }

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

        // Add to cart button events
        addBtns.forEach((btn, index) => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();

                const itemData = menuItemsData[index];
                const icon = this.querySelector('i');
                const isAdded = this.classList.contains('added');

                if (!isAdded && itemData) {
                    // Add to cart
                    if (window.menuCart) {
                        window.menuCart.addItem(itemData.name, itemData.price, 1, itemData.isVeg);
                        syncAddButtonsWithCart();
                    }

                    this.classList.add('added');
                    icon.classList.remove('bi-plus');
                    icon.classList.add('bi-check');

                    // Float animation
                    createFloatAnimation(this, '+1');
                }
            });
        });
    }

    // ========== FALLBACK SEARCH INITIALIZATION ==========
    function initFallbackSearch() {
        const searchBtn = document.querySelector('.s19-search-btn');
        const searchOverlay = document.querySelector('.s19-search-overlay');
        const searchInput = document.querySelector('.s19-search-input');
        const searchCloseBtn = document.querySelector('.s19-search-close-btn');
        const searchClearBtn = document.querySelector('.s19-search-clear-btn');
        const searchResults = document.querySelector('.s19-search-results');

        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                searchOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                setTimeout(() => searchInput.focus(), 100);
            });
        }

        if (searchCloseBtn) {
            searchCloseBtn.addEventListener('click', () => {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = '';
                searchInput.value = '';
                searchClearBtn.style.display = 'none';
                renderSearchPlaceholder();
            });
        }

        if (searchClearBtn) {
            searchClearBtn.addEventListener('click', () => {
                searchInput.value = '';
                searchClearBtn.style.display = 'none';
                searchInput.focus();
                renderSearchPlaceholder();
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', debounce(function(e) {
                const query = e.target.value;
                searchClearBtn.style.display = query ? 'flex' : 'none';
                search(query);
            }, 200));

            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    searchOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }

        function renderSearchPlaceholder() {
            searchResults.innerHTML = `
                <div class="s19-search-placeholder">
                    <i class="bi bi-search"></i>
                    <p>Search for your favorite dishes</p>
                </div>
            `;
        }

        function search(query) {
            if (!query.trim()) {
                renderSearchPlaceholder();
                return;
            }

            const filtered = menuItemsData.filter(item =>
                item.name.toLowerCase().includes(query.toLowerCase()) ||
                item.category.toLowerCase().includes(query.toLowerCase())
            );

            if (filtered.length === 0) {
                searchResults.innerHTML = `
                    <div class="s19-search-no-results">
                        <i class="bi bi-emoji-frown"></i>
                        <p>No dishes found for "${query}"</p>
                    </div>
                `;
            } else {
                renderResults(filtered, query);
            }
        }

        function renderResults(items, query) {
            const grouped = items.reduce((acc, item) => {
                const cat = item.category || 'Other';
                if (!acc[cat]) acc[cat] = [];
                acc[cat].push(item);
                return acc;
            }, {});

            let html = '';
            for (const category in grouped) {
                html += `<div class="s19-search-category-label">${category}</div>`;
                grouped[category].forEach(item => {
                    const highlightedName = item.name.replace(
                        new RegExp(`(${query})`, 'gi'),
                        '<mark>$1</mark>'
                    );
                    html += `
                        <div class="s19-search-result-item" data-name="${item.name}">
                            <div class="s19-search-result-info">
                                <span class="s19-search-result-badge ${item.isVeg ? 'veg' : 'non-veg'}"></span>
                                <span class="s19-search-result-name">${highlightedName}</span>
                            </div>
                            <span class="s19-search-result-price">₹${item.price}</span>
                        </div>
                    `;
                });
            }

            searchResults.innerHTML = html;

            // Bind result click events
            searchResults.querySelectorAll('.s19-search-result-item').forEach(resultItem => {
                resultItem.addEventListener('click', () => {
                    const name = resultItem.dataset.name;
                    searchOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                    searchInput.value = '';
                    searchClearBtn.style.display = 'none';

                    // Scroll to item
                    scrollToMenuItem(name);
                });
            });
        }

        function scrollToMenuItem(name) {
            const menuItems = document.querySelectorAll('.s19-menu-item');
            menuItems.forEach(menuItem => {
                const itemName = menuItem.querySelector('.s19-item-name');
                if (itemName && itemName.textContent.trim() === name) {
                    // Show all sections first
                    const allCatItem = document.querySelector('.s19-cat-item[data-category="all"]');
                    if (allCatItem) allCatItem.click();

                    setTimeout(() => {
                        menuItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        menuItem.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.5)';
                        setTimeout(() => {
                            menuItem.style.boxShadow = '';
                        }, 2000);
                    }, 100);
                }
            });
        }
    }

    // ========== UTILITY FUNCTIONS ==========
    function createFloatAnimation(element, text) {
        const floater = document.createElement('span');
        floater.textContent = text;
        floater.style.cssText = `
            position: fixed;
            color: #ff6b35;
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

    // Add float animation keyframes
    if (!document.querySelector('#style19-float-styles')) {
        const style = document.createElement('style');
        style.id = 'style19-float-styles';
        style.textContent = `
            @keyframes floatUp {
                0% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-40px); }
            }
        `;
        document.head.appendChild(style);
    }

    // ========== INITIALIZE ITEM DETAIL POPUP ==========
    if (typeof initItemDetailPopup === 'function') {
        initItemDetailPopup(menuItemsData);
    }

    // ========== MENU ITEM CLICK FOR POPUP ==========
    const menuItems = document.querySelectorAll('.s19-menu-item');
    menuItems.forEach((item, index) => {
        item.addEventListener('click', function(e) {
            if (e.target.closest('.s19-add-btn')) return;

            const itemData = menuItemsData[index];
            if (itemData) {
                const img = item.querySelector('img');
                const mainImage = img ? img.src : '';
                openItemDetail(itemData, mainImage);
            }
        });

        item.style.cursor = 'pointer';
    });
});
