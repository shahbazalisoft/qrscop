// Menu Style 8 - Dark Minimal (No Images) JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Category Navigation
    const catBtns = document.querySelectorAll('.cat-btn');
    const sections = document.querySelectorAll('.menu-section');

    catBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;

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

    // Header buttons
    const menuBtn = document.querySelector('.menu-btn');
    const searchBtn = document.querySelector('.search-btn');

    if (menuBtn) {
        menuBtn.addEventListener('click', function() {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }

    // Search functionality
    const searchOverlay = document.querySelector('.search-overlay');
    const searchInput = document.querySelector('.search-input');
    const searchCloseBtn = document.querySelector('.search-close-btn');
    const searchClearBtn = document.querySelector('.search-clear-btn');
    const searchResults = document.querySelector('.search-results');

    // Menu items data for search
    const menuItemsData = [
        { name: 'Margherita Pizza', price: 299, mrp: 399, category: 'Recommended', isVeg: true },
        { name: 'Butter Chicken', price: 349, mrp: 449, category: 'Recommended', isVeg: false },
        { name: 'Fluffy Pancakes', price: 199, mrp: 249, category: 'Recommended', isVeg: true },
        { name: 'Crispy Samosa', price: 79, mrp: 99, category: 'Starters', isVeg: true },
        { name: 'Paneer Tikka', price: 249, mrp: 299, category: 'Starters', isVeg: true },
        { name: 'Buffalo Wings', price: 299, mrp: 399, category: 'Starters', isVeg: false },
        { name: 'Veg Spring Rolls', price: 149, mrp: 179, category: 'Starters', isVeg: true },
        { name: 'Dal Makhani', price: 229, mrp: 279, category: 'Main Course', isVeg: true },
        { name: 'Hyderabadi Biryani', price: 399, mrp: 499, category: 'Main Course', isVeg: false },
        { name: 'Garlic Naan', price: 59, mrp: 79, category: 'Main Course', isVeg: true },
        { name: 'Palak Paneer', price: 269, mrp: 329, category: 'Main Course', isVeg: true },
        { name: 'Gulab Jamun', price: 99, mrp: 129, category: 'Desserts', isVeg: true },
        { name: 'Blueberry Cheesecake', price: 199, mrp: 249, category: 'Desserts', isVeg: true },
        { name: 'Belgian Chocolate', price: 149, mrp: 179, category: 'Desserts', isVeg: true },
        { name: 'Masala Chai', price: 49, mrp: 59, category: 'Beverages', isVeg: true },
        { name: 'Cold Coffee', price: 129, mrp: 159, category: 'Beverages', isVeg: true },
        { name: 'Mango Lassi', price: 99, mrp: 129, category: 'Beverages', isVeg: true },
        { name: 'Fresh Orange Juice', price: 89, mrp: 109, category: 'Beverages', isVeg: true }
    ];

    // Initialize Item Detail Popup
    if (typeof initItemDetailPopup === 'function') {
        initItemDetailPopup(menuItemsData);
    }

    // Menu item click for popup
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach((item, index) => {
        item.style.cursor = 'pointer';
        item.addEventListener('click', function(e) {
            if (e.target.closest('.add-btn')) return;
            const itemData = menuItemsData[index];
            if (itemData && typeof openItemDetail === 'function') {
                openItemDetail(itemData, '');
            }
        });
    });

    // Open search
    function openSearch() {
        searchOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            searchInput.focus();
        }, 100);
    }

    // Close search
    function closeSearch() {
        searchOverlay.classList.remove('active');
        document.body.style.overflow = '';
        searchInput.value = '';
        searchClearBtn.style.display = 'none';
        renderSearchPlaceholder();
    }

    // Render search placeholder
    function renderSearchPlaceholder() {
        searchResults.innerHTML = `
            <div class="search-placeholder">
                <i class="bi bi-search"></i>
                <p>Search for your favorite dishes</p>
            </div>
        `;
    }

    // Render search results
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
            if (!acc[item.category]) {
                acc[item.category] = [];
            }
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

        // Add click listeners to results
        document.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', function() {
                const name = this.dataset.name;
                closeSearch();
                // Find and scroll to the item
                const menuItems = document.querySelectorAll('.menu-item');
                menuItems.forEach(menuItem => {
                    const itemName = menuItem.querySelector('.item-name');
                    if (itemName && itemName.textContent === name) {
                        const headerHeight = document.querySelector('.header').offsetHeight;
                        const navHeight = document.querySelector('.category-nav').offsetHeight;
                        const targetPosition = menuItem.offsetTop - headerHeight - navHeight - 20;
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                        // Highlight the item briefly
                        menuItem.style.background = 'rgba(201, 169, 98, 0.2)';
                        setTimeout(() => {
                            menuItem.style.background = '';
                        }, 2000);
                    }
                });
            });
        });
    }

    // Event listeners for search
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            openSearch();
        });
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

        // Close on Escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSearch();
            }
        });
    }

});
