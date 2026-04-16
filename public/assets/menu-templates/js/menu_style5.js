// Menu Style 11 - Flavour House with Background Images JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Use dynamic menu items data from blade template
    const menuItemsData = window.menuItemsData || [];
    const menuItemElements = document.querySelectorAll('.menu-item');

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
            if (category === 'view-all') return; // handled by popup
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
        sections.forEach(section => {
            const sectionItems = section.querySelectorAll('.menu-item');
            let sectionHasVisible = false;
            sectionItems.forEach(item => {
                if (item.style.display !== 'none') sectionHasVisible = true;
            });

            section.style.display = sectionHasVisible ? '' : 'none';
        });
    }
});
