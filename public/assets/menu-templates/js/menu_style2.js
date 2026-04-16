// Menu Style 8 - Dark Minimal (No Images) JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Category Navigation
    const catBtns = document.querySelectorAll('.cat-btn');
    const sections = document.querySelectorAll('.menu-section');

    catBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            if (category === 'view-all') return; // handled by popup

            catBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const targetSection = document.getElementById(category);
            if (targetSection) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const navHeight = document.querySelector('.category-nav').offsetHeight;
                const targetPosition = targetSection.offsetTop - headerHeight - navHeight - 10;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Update active category on scroll
    window.addEventListener('scroll', function() {
        const headerHeight = document.querySelector('.header').offsetHeight;
        const navHeight = document.querySelector('.category-nav').offsetHeight;
        let current = 'recommended';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - headerHeight - navHeight - 60;
            if (window.scrollY >= sectionTop) {
                current = section.getAttribute('id');
            }
        });

        catBtns.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.category === current) {
                btn.classList.add('active');
            }
        });
    });

    // Scroll reveal for sections
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    sections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'all 0.5s ease';
        observer.observe(section);
    });

    // Use dynamic menu items data from blade template
    const menuItemsData = window.menuItemsData || [];
    const menuItemElements = document.querySelectorAll('.menu-item');

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
