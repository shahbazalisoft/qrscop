// Menu Style 12 - Flavour House with Item Images JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Menu items data
    const menuItemsData = [
        // Recommended
        { name: 'Butter Chicken', price: 349, mrp: 449, isVeg: false, category: 'Recommended', image: 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=300&h=300&fit=crop' },
        { name: 'Margherita Pizza', price: 299, mrp: 399, isVeg: true, category: 'Recommended', image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=300&h=300&fit=crop' },
        { name: 'Hyderabadi Biryani', price: 399, mrp: 499, isVeg: false, category: 'Recommended', image: 'https://images.unsplash.com/photo-1574484284002-952d92456975?w=300&h=300&fit=crop' },
        { name: 'Grilled Salmon', price: 599, mrp: 749, isVeg: false, category: 'Recommended', image: 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=300&h=300&fit=crop' },
        // Starters
        { name: 'Paneer Tikka', price: 249, mrp: 299, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=300&h=300&fit=crop' },
        { name: 'Crispy Samosa', price: 79, mrp: 99, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=300&h=300&fit=crop' },
        { name: 'Buffalo Wings', price: 299, mrp: 399, isVeg: false, category: 'Starters', image: 'https://images.unsplash.com/photo-1562967914-608f82629710?w=300&h=300&fit=crop' },
        { name: 'Spring Rolls', price: 149, mrp: 179, isVeg: true, category: 'Starters', image: 'https://images.unsplash.com/photo-1541014741259-de529411b96a?w=300&h=300&fit=crop' },
        // Main Course
        { name: 'Dal Makhani', price: 229, mrp: 279, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=300&h=300&fit=crop' },
        { name: 'Palak Paneer', price: 269, mrp: 329, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=300&h=300&fit=crop' },
        { name: 'Garlic Naan', price: 69, mrp: 89, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=300&h=300&fit=crop' },
        { name: 'Tandoori Roti', price: 39, mrp: 49, isVeg: true, category: 'Main Course', image: 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300&h=300&fit=crop' },
        // Desserts
        { name: 'Fluffy Pancakes', price: 179, mrp: 229, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=300&h=300&fit=crop' },
        { name: 'Blueberry Cheesecake', price: 199, mrp: 249, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=300&h=300&fit=crop' },
        { name: 'Gulab Jamun', price: 99, mrp: 129, isVeg: true, category: 'Desserts', image: 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=300&h=300&fit=crop' },
        // Drinks
        { name: 'Masala Chai', price: 49, mrp: 59, isVeg: true, category: 'Drinks', image: 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=300&h=300&fit=crop' },
        { name: 'Cold Coffee', price: 129, mrp: 159, isVeg: true, category: 'Drinks', image: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=300&h=300&fit=crop' },
        { name: 'Mango Lassi', price: 99, mrp: 129, isVeg: true, category: 'Drinks', image: 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=300&h=300&fit=crop' },
        { name: 'Fresh Orange Juice', price: 89, mrp: 109, isVeg: true, category: 'Drinks', image: 'https://images.unsplash.com/photo-1534353473418-4cfa6c56fd38?w=300&h=300&fit=crop' }
    ];

    // Initialize Item Detail Popup
    if (typeof initItemDetailPopup === 'function') {
        initItemDetailPopup(menuItemsData);
    }

    // ========== SIZE POPUP ==========
    const sizePopup = document.getElementById('sizePopup');
    const sizePopupOverlay = document.getElementById('sizePopupOverlay');
    const popupItemName = document.getElementById('popupItemName');
    const popupClose = document.getElementById('popupClose');
    const quarterPrice = document.getElementById('quarterPrice');
    const halfPrice = document.getElementById('halfPrice');
    const fullPrice = document.getElementById('fullPrice');

    let currentSizeItem = null;

    function openSizePopup(item) {
        currentSizeItem = {
            name: item.dataset.item,
            quarter: parseInt(item.dataset.quarter),
            half: parseInt(item.dataset.half),
            full: parseInt(item.dataset.full)
        };

        popupItemName.textContent = currentSizeItem.name;
        quarterPrice.textContent = '₹' + currentSizeItem.quarter;
        halfPrice.textContent = '₹' + currentSizeItem.half;
        fullPrice.textContent = '₹' + currentSizeItem.full;

        sizePopup.classList.add('show');
        sizePopupOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSizePopup() {
        sizePopup.classList.remove('show');
        sizePopupOverlay.classList.remove('show');
        document.body.style.overflow = '';
        currentSizeItem = null;
    }

    if (popupClose) {
        popupClose.addEventListener('click', closeSizePopup);
    }
    if (sizePopupOverlay) {
        sizePopupOverlay.addEventListener('click', closeSizePopup);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sizePopup && sizePopup.classList.contains('show')) {
            closeSizePopup();
        }
    });

    // Size add buttons
    const sizeAddBtns = document.querySelectorAll('.size-add-btn');
    sizeAddBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (!currentSizeItem) return;

            const sizeOption = this.closest('.size-option');
            const size = sizeOption.dataset.size;
            let price, sizeName;

            switch(size) {
                case 'quarter':
                    price = currentSizeItem.quarter;
                    sizeName = 'Quarter';
                    break;
                case 'half':
                    price = currentSizeItem.half;
                    sizeName = 'Half';
                    break;
                case 'full':
                    price = currentSizeItem.full;
                    sizeName = 'Full';
                    break;
            }

            if (window.menuCart) {
                window.menuCart.addItem(currentSizeItem.name + ' (' + sizeName + ')', price, 1);
            }

            // Show added animation
            this.classList.add('added');
            this.innerHTML = '<i class="bi bi-check"></i>';

            setTimeout(() => {
                this.classList.remove('added');
                this.innerHTML = '<i class="bi bi-plus"></i>';
                closeSizePopup();
            }, 600);
        });
    });

    // Menu item click for popup
    const menuItemElements = document.querySelectorAll('.menu-item');
    menuItemElements.forEach((item, index) => {
        item.style.cursor = 'pointer';

        // Check if item has sizes
        const hasSizes = item.classList.contains('has-sizes');
        const addBtn = item.querySelector('.add-btn');

        if (hasSizes && addBtn) {
            addBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                openSizePopup(item);
            });
        }

        item.addEventListener('click', function(e) {
            if (e.target.closest('.add-btn')) return;

            // If has sizes, open size popup instead of item detail
            if (hasSizes) {
                openSizePopup(item);
                return;
            }

            const itemData = menuItemsData[index];
            if (itemData && typeof openItemDetail === 'function') {
                const img = item.querySelector('.item-image img');
                const mainImage = img ? img.src : (itemData.image || '');
                openItemDetail(itemData, mainImage);
            }
        });
    });

    // Category Pills Navigation
    const pills = document.querySelectorAll('.pill');
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    const sections = document.querySelectorAll('.menu-section');
    const moreBtn = document.querySelector('.more-btn');
    const moreDropdown = document.querySelector('.more-dropdown');

    // Category to section ID mapping
    const categoryMap = {
        'all': null,
        'recommended': 'recommended',
        'starters': 'starters',
        'mains': 'mains',
        'desserts': 'desserts',
        'drinks': 'drinks'
    };

    // More button dropdown toggle
    if (moreBtn) {
        moreBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            moreDropdown.classList.toggle('active');
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.more-menu')) {
            if (moreDropdown) moreDropdown.classList.remove('active');
        }
    });

    // Function to update active states
    function updateActiveCategory(category) {
        pills.forEach(p => p.classList.remove('active'));
        dropdownItems.forEach(d => d.classList.remove('active'));

        const activePill = document.querySelector(`.pill[data-category="${category}"]`);
        const activeDropdown = document.querySelector(`.dropdown-item[data-category="${category}"]`);

        if (activePill) activePill.classList.add('active');
        if (activeDropdown) activeDropdown.classList.add('active');
    }

    // Function to filter sections
    function filterSections(category) {
        sections.forEach(section => {
            if (category === 'all') {
                section.style.display = 'block';
            } else {
                const sectionId = section.getAttribute('id');
                section.style.display = sectionId === categoryMap[category] ? 'block' : 'none';
            }
        });
    }

    // Pills click handler
    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            const category = this.dataset.category;
            updateActiveCategory(category);
            filterSections(category);
        });
    });

    // Dropdown items click handler
    dropdownItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.dataset.category;
            updateActiveCategory(category);
            filterSections(category);
            if (moreDropdown) moreDropdown.classList.remove('active');
        });
    });

    // Add to cart functionality
    const addBtns = document.querySelectorAll('.add-btn');

    addBtns.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            const itemData = menuItemsData[index];

            if (window.menuCart) {
                window.menuCart.addItem(itemData.name, itemData.price, 1);
            }

            // Button animation
            this.innerHTML = '<i class="bi bi-check"></i>';
            this.style.background = '#22c55e';

            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-plus"></i>';
                this.style.background = '';
            }, 600);

            // Create floating animation
            createFloatAnimation(this, '+1');
        });
    });

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

                // Reset filter to show all
                updateActiveCategory('all');
                filterSections('all');

                // Find and highlight the item
                const menuItems = document.querySelectorAll('.menu-item');
                menuItems.forEach((menuItem, index) => {
                    if (menuItemsData[index].name === name) {
                        menuItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        menuItem.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.6)';
                        setTimeout(() => {
                            menuItem.style.boxShadow = '';
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
});
