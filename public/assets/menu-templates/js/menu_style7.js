// Menu Style 14 - Portion Size Selector JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Popup Elements
    const sizePopup = document.getElementById('sizePopup');
    const sizePopupOverlay = document.getElementById('sizePopupOverlay');
    const popupItemName = document.getElementById('popupItemName');
    const popupClose = document.getElementById('popupClose');
    const quarterPrice = document.getElementById('quarterPrice');
    const halfPrice = document.getElementById('halfPrice');
    const fullPrice = document.getElementById('fullPrice');

    // Current item being added
    let currentItem = null;

    // Add to cart functionality for menu items (opens popup)
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        const addBtn = item.querySelector('.add-btn');
        if (addBtn) {
            addBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                openSizePopup(item);
            });
        }

        // Also allow clicking the entire card to open popup
        item.addEventListener('click', function(e) {
            if (!e.target.closest('.add-btn')) {
                openSizePopup(item);
            }
        });
    });

    // Open size selection popup
    function openSizePopup(item) {
        currentItem = {
            name: item.dataset.item,
            quarter: parseInt(item.dataset.quarter),
            half: parseInt(item.dataset.half),
            full: parseInt(item.dataset.full)
        };

        // Update popup content
        popupItemName.textContent = currentItem.name;
        quarterPrice.textContent = '₹' + currentItem.quarter;
        halfPrice.textContent = '₹' + currentItem.half;
        fullPrice.textContent = '₹' + currentItem.full;

        // Show popup
        sizePopup.classList.add('show');
        sizePopupOverlay.classList.add('show');
        document.body.classList.add('popup-open');
    }

    // Close popup
    function closePopup() {
        sizePopup.classList.remove('show');
        sizePopupOverlay.classList.remove('show');
        document.body.classList.remove('popup-open');
        currentItem = null;
    }

    if (popupClose) {
        popupClose.addEventListener('click', closePopup);
    }
    if (sizePopupOverlay) {
        sizePopupOverlay.addEventListener('click', closePopup);
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sizePopup && sizePopup.classList.contains('show')) {
            closePopup();
        }
    });

    // Add to cart from popup (uses cart-common.js)
    const sizeAddBtns = document.querySelectorAll('.size-add-btn');
    sizeAddBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (!currentItem) return;

            const sizeOption = this.closest('.size-option');
            const size = sizeOption.dataset.size;
            let price;
            let sizeName;

            switch(size) {
                case 'quarter':
                    price = currentItem.quarter;
                    sizeName = 'Quarter';
                    break;
                case 'half':
                    price = currentItem.half;
                    sizeName = 'Half';
                    break;
                case 'full':
                    price = currentItem.full;
                    sizeName = 'Full';
                    break;
            }

            // Add to cart using cart-common.js
            if (window.menuCart) {
                window.menuCart.addItem(currentItem.name + ' (' + sizeName + ')', price, 1);
            }

            showAddedAnimation(this);

            // Close popup after short delay
            setTimeout(() => {
                closePopup();
            }, 500);
        });
    });

    // Add to cart for bread items (direct add, no popup)
    const addButtonsSm = document.querySelectorAll('.add-btn-sm');
    addButtonsSm.forEach(btn => {
        btn.addEventListener('click', function() {
            const itemName = this.dataset.item;
            const price = parseInt(this.dataset.price);

            // Add to cart using cart-common.js
            if (window.menuCart) {
                window.menuCart.addItem(itemName, price, 1);
            }

            showAddedAnimationSmall(this);
        });
    });

    // Show added animation for popup buttons
    function showAddedAnimation(button) {
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.classList.add('added');

        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('added');
        }, 500);
    }

    // Show added animation for small buttons
    function showAddedAnimationSmall(button) {
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.style.background = '#22c55e';
        button.style.borderColor = '#22c55e';
        button.style.color = 'white';

        setTimeout(() => {
            button.innerHTML = originalContent;
            button.style.background = '';
            button.style.borderColor = '';
            button.style.color = '';
        }, 1000);
    }

    // Category navigation
    const categoryTabs = document.querySelectorAll('.category-tab');
    const categoryNav = document.querySelector('.category-nav');
    const restaurantHeader = document.querySelector('.restaurant-header');

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all tabs
            categoryTabs.forEach(t => t.classList.remove('active'));

            // Add active class to clicked tab
            this.classList.add('active');

            // Scroll to section
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                const navHeight = (categoryNav ? categoryNav.offsetHeight : 0) + (restaurantHeader ? restaurantHeader.offsetHeight : 0);
                const targetPosition = targetSection.offsetTop - navHeight - 10;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Update active category on scroll
    const sections = document.querySelectorAll('.menu-category');

    function updateActiveCategory() {
        const scrollPosition = window.scrollY;
        const navHeight = (categoryNav ? categoryNav.offsetHeight : 0) + (restaurantHeader ? restaurantHeader.offsetHeight : 0) + 50;

        sections.forEach(section => {
            const sectionTop = section.offsetTop - navHeight;
            const sectionBottom = sectionTop + section.offsetHeight;

            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                const sectionId = section.getAttribute('id');

                categoryTabs.forEach(tab => {
                    tab.classList.remove('active');
                    if (tab.getAttribute('href') === '#' + sectionId) {
                        tab.classList.add('active');

                        // Scroll tab into view
                        tab.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });
                    }
                });
            }
        });
    }

    // Throttle scroll event
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                updateActiveCategory();
                ticking = false;
            });
            ticking = true;
        }
    });

    // ========== SEARCH BAR & FILTER ==========
    const searchBtn = document.querySelector('.search-btn');
    const searchBarSection = document.querySelector('.search-bar-section');
    const searchField = document.querySelector('.search-field');
    const clearSearchBtn = document.querySelector('.clear-search-btn');
    const filterDropdown = document.querySelector('.filter-dropdown');
    const filterBtn = document.querySelector('.filter-btn');
    const filterOptions = document.querySelectorAll('.filter-option');
    const menuCategories = document.querySelectorAll('.menu-category');

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
        // Filter menu items
        menuItems.forEach(item => {
            const itemName = (item.dataset.item || '').toLowerCase();
            const itemDesc = (item.querySelector('.item-desc')?.textContent || '').toLowerCase();
            const isVeg = !!item.querySelector('.veg-badge');

            let showItem = true;

            // Check search query
            if (currentSearchQuery) {
                if (!itemName.includes(currentSearchQuery) && !itemDesc.includes(currentSearchQuery)) {
                    showItem = false;
                }
            }

            // Check veg/non-veg filter
            if (showItem && currentFilter !== 'all') {
                if (currentFilter === 'veg' && !isVeg) {
                    showItem = false;
                } else if (currentFilter === 'non-veg' && isVeg) {
                    showItem = false;
                }
            }

            item.style.display = showItem ? '' : 'none';
        });

        // Filter bread items
        const breadItems = document.querySelectorAll('.bread-item');
        breadItems.forEach(item => {
            const itemName = (item.querySelector('h4')?.textContent || '').toLowerCase();
            const itemDesc = (item.querySelector('p')?.textContent || '').toLowerCase();
            const isVeg = !!item.querySelector('.veg-badge-sm');

            let showItem = true;

            if (currentSearchQuery) {
                if (!itemName.includes(currentSearchQuery) && !itemDesc.includes(currentSearchQuery)) {
                    showItem = false;
                }
            }

            if (showItem && currentFilter !== 'all') {
                if (currentFilter === 'veg' && !isVeg) {
                    showItem = false;
                } else if (currentFilter === 'non-veg' && isVeg) {
                    showItem = false;
                }
            }

            item.style.display = showItem ? '' : 'none';
        });

        // Update section visibility
        menuCategories.forEach(section => {
            const sectionMenuItems = section.querySelectorAll('.menu-item');
            const sectionBreadItems = section.querySelectorAll('.bread-item');
            let hasVisible = false;

            sectionMenuItems.forEach(item => {
                if (item.style.display !== 'none') hasVisible = true;
            });
            sectionBreadItems.forEach(item => {
                if (item.style.display !== 'none') hasVisible = true;
            });

            section.style.display = hasVisible ? '' : 'none';
        });
    }
});
