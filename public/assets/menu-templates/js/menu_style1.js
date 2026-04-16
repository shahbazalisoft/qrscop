// Menu Style 1 - Dark Minimal JavaScript
// Only handles unique UI: category nav, search/filter, scroll animations
// Add-to-cart, item detail, size picker handled by common JS files

document.addEventListener('DOMContentLoaded', function() {
    var menuItemsData = window.menuItemsData || [];

    // Category Navigation
    var catBtns = document.querySelectorAll('.cat-btn');
    var sections = document.querySelectorAll('.menu-section');

    catBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var category = this.dataset.category;
            if (category === 'view-all') return; // handled by popup

            catBtns.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');

            if (category === 'all') {
                sections.forEach(function(section) {
                    section.style.display = '';
                });
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            var targetSection = document.getElementById(category);
            if (targetSection) {
                var headerHeight = document.querySelector('.header').offsetHeight;
                var navHeight = document.querySelector('.category-nav').offsetHeight;
                var targetPosition = targetSection.offsetTop - headerHeight - navHeight - 10;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Update active category on scroll
    window.addEventListener('scroll', function() {
        var header = document.querySelector('.header');
        var nav = document.querySelector('.category-nav');
        if (!header || !nav) return;
        var headerHeight = header.offsetHeight;
        var navHeight = nav.offsetHeight;
        var current = '';

        sections.forEach(function(section) {
            var sectionTop = section.offsetTop - headerHeight - navHeight - 60;
            if (window.scrollY >= sectionTop) {
                current = section.getAttribute('id');
            }
        });

        catBtns.forEach(function(btn) {
            btn.classList.remove('active');
            if (btn.dataset.category === current) {
                btn.classList.add('active');
            }
        });
    });

    // ========== SEARCH BAR & FILTER ==========
    var searchBtn = document.querySelector('.search-btn');
    var searchBarSection = document.querySelector('.search-bar-section');
    var searchField = document.querySelector('.search-field');
    var clearSearchBtn = document.querySelector('.clear-search-btn');
    var filterDropdown = document.querySelector('.filter-dropdown');
    var filterBtn = document.querySelector('.filter-btn');
    var filterOptions = document.querySelectorAll('.filter-option');
    var menuItemElements = document.querySelectorAll('.menu-item');

    var currentFilter = 'all';
    var currentSearchQuery = '';

    // Toggle search bar visibility
    if (searchBtn && searchBarSection) {
        searchBtn.addEventListener('click', function() {
            var isVisible = searchBarSection.style.display !== 'none';
            searchBarSection.style.display = isVisible ? 'none' : 'block';
            if (!isVisible && searchField) {
                setTimeout(function() { searchField.focus(); }, 100);
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
    filterOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            var filter = this.dataset.filter;
            currentFilter = filter;

            filterOptions.forEach(function(opt) { opt.classList.remove('active'); });
            this.classList.add('active');

            var iconClass = filter === 'all' ? 'all-icon' : filter === 'veg' ? 'veg-icon' : 'non-veg-icon';
            var text = filter === 'all' ? 'All' : filter === 'veg' ? 'Veg' : 'Non-Veg';
            if (filterBtn) {
                filterBtn.querySelector('.filter-icon').className = 'filter-icon ' + iconClass;
                filterBtn.querySelector('.filter-text').textContent = text;
            }

            filterDropdown.classList.remove('active');
            applyFilters();
        });
    });

    // Search input
    if (searchField) {
        searchField.addEventListener('input', function() {
            var rawValue = this.value;
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
        menuItemElements.forEach(function(item) {
            var dataIndex = parseInt(item.dataset.index);
            var itemData = menuItemsData[dataIndex];
            if (!itemData) return;

            var showItem = true;

            if (currentSearchQuery) {
                var itemName = itemData.name.toLowerCase();
                if (!itemName.includes(currentSearchQuery)) {
                    showItem = false;
                }
            }

            if (showItem && currentFilter !== 'all') {
                var isVeg = itemData.isVeg;
                if (currentFilter === 'veg' && !isVeg) {
                    showItem = false;
                } else if (currentFilter === 'non-veg' && isVeg) {
                    showItem = false;
                }
            }

            item.style.display = showItem ? '' : 'none';
        });

        sections.forEach(function(section) {
            var sectionItems = section.querySelectorAll('.menu-item');
            var sectionHasVisible = false;
            sectionItems.forEach(function(item) {
                if (item.style.display !== 'none') sectionHasVisible = true;
            });
            section.style.display = sectionHasVisible ? '' : 'none';
        });
    }

    // Scroll reveal for sections
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { root: null, rootMargin: '0px', threshold: 0.1 });

    sections.forEach(function(section) {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'all 0.5s ease';
        observer.observe(section);
    });
});
