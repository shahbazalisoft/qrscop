// Menu Style 10 - Cafe Royale Light Theme JavaScript
// Uses common utilities from menu-common.js

document.addEventListener('DOMContentLoaded', function() {
    // Menu items data for search
    const menuItemsData = [
        // Recommended
        { name: 'Butter Chicken', price: 349, mrp: 449, category: 'Recommended', isVeg: false },
        { name: 'Hyderabadi Biryani', price: 399, mrp: 499, category: 'Recommended', isVeg: false },
        { name: 'Margherita Pizza', price: 299, mrp: 399, category: 'Recommended', isVeg: true },
        { name: 'Tiramisu', price: 229, mrp: 279, category: 'Recommended', isVeg: true },
        // Beverages
        { name: 'Masala Chai', price: 49, mrp: 59, category: 'Beverages', isVeg: true },
        { name: 'Cold Coffee', price: 129, mrp: 159, category: 'Beverages', isVeg: true },
        { name: 'Orange Juice', price: 89, mrp: 109, category: 'Beverages', isVeg: true },
        { name: 'Mango Lassi', price: 99, mrp: 129, category: 'Beverages', isVeg: true },
        { name: 'Green Tea', price: 59, mrp: 79, category: 'Beverages', isVeg: true },
        { name: 'Strawberry Smoothie', price: 149, mrp: 179, category: 'Beverages', isVeg: true },
        { name: 'Cappuccino', price: 119, mrp: 149, category: 'Beverages', isVeg: true },
        { name: 'Lemonade', price: 69, mrp: 89, category: 'Beverages', isVeg: true },
        // Appetizers
        { name: 'Crispy Samosa', price: 79, mrp: 99, category: 'Appetizers', isVeg: true },
        { name: 'Paneer Tikka', price: 249, mrp: 299, category: 'Appetizers', isVeg: true },
        { name: 'Spring Rolls', price: 149, mrp: 179, category: 'Appetizers', isVeg: true },
        { name: 'Buffalo Wings', price: 299, mrp: 399, category: 'Appetizers', isVeg: false },
        { name: 'Chicken Momos', price: 179, mrp: 229, category: 'Appetizers', isVeg: false },
        { name: 'Garlic Bread', price: 99, mrp: 129, category: 'Appetizers', isVeg: true },
        { name: 'Onion Rings', price: 129, mrp: 159, category: 'Appetizers', isVeg: true },
        { name: 'Bruschetta', price: 159, mrp: 199, category: 'Appetizers', isVeg: true },
        // Main Course
        { name: 'Butter Chicken', price: 349, mrp: 449, category: 'Main Course', isVeg: false },
        { name: 'Hyderabadi Biryani', price: 399, mrp: 499, category: 'Main Course', isVeg: false },
        { name: 'Dal Makhani', price: 229, mrp: 279, category: 'Main Course', isVeg: true },
        { name: 'Palak Paneer', price: 269, mrp: 329, category: 'Main Course', isVeg: true },
        { name: 'Garlic Naan', price: 59, mrp: 79, category: 'Main Course', isVeg: true },
        { name: 'Chicken Tikka Masala', price: 369, mrp: 449, category: 'Main Course', isVeg: false },
        { name: 'Grilled Salmon', price: 549, mrp: 699, category: 'Main Course', isVeg: false },
        { name: 'Veg Fried Rice', price: 189, mrp: 229, category: 'Main Course', isVeg: true },
        { name: 'Tandoori Roti', price: 35, mrp: 45, category: 'Main Course', isVeg: true },
        // Pizza & Pasta
        { name: 'Margherita Pizza', price: 299, mrp: 399, category: 'Pizza & Pasta', isVeg: true },
        { name: 'Pepperoni Pizza', price: 399, mrp: 499, category: 'Pizza & Pasta', isVeg: false },
        { name: 'BBQ Chicken Pizza', price: 449, mrp: 549, category: 'Pizza & Pasta', isVeg: false },
        { name: 'Pasta Alfredo', price: 279, mrp: 349, category: 'Pizza & Pasta', isVeg: true },
        { name: 'Penne Arrabiata', price: 249, mrp: 299, category: 'Pizza & Pasta', isVeg: true },
        { name: 'Spaghetti Carbonara', price: 329, mrp: 399, category: 'Pizza & Pasta', isVeg: false },
        // Desserts
        { name: 'Gulab Jamun', price: 99, mrp: 129, category: 'Desserts', isVeg: true },
        { name: 'Blueberry Cheesecake', price: 199, mrp: 249, category: 'Desserts', isVeg: true },
        { name: 'Fluffy Pancakes', price: 179, mrp: 229, category: 'Desserts', isVeg: true },
        { name: 'Chocolate Brownie', price: 169, mrp: 199, category: 'Desserts', isVeg: true },
        { name: 'Tiramisu', price: 229, mrp: 279, category: 'Desserts', isVeg: true },
        { name: 'Belgian Chocolate', price: 149, mrp: 179, category: 'Desserts', isVeg: true },
        { name: 'Rasmalai', price: 119, mrp: 149, category: 'Desserts', isVeg: true },
        { name: 'Mango Kulfi', price: 89, mrp: 109, category: 'Desserts', isVeg: true }
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
                const img = item.querySelector('.item-image img');
                const mainImage = img ? img.src : '';
                openItemDetail(itemData, mainImage);
            }
        });
    });

    // Initialize Category Navigation using common utility
    new CategoryNavigation({
        btnSelector: '.tab-btn',
        sectionSelector: '.menu-section',
        activeClass: 'active',
        dataAttribute: 'category',
        defaultCategory: 'recommended',
        scrollOffset: 50
    });

    // Initialize Search using common utility
    const search = new MenuSearch(menuItemsData, {
        overlaySelector: '.search-overlay',
        inputSelector: '.search-input',
        closeBtnSelector: '.search-close-btn',
        clearBtnSelector: '.search-clear-btn',
        resultsSelector: '.search-results',
        searchBtnSelector: '.search-btn',
        currency: '₹'
    });

    // Custom search result click handler for this template
    search.onResultClick = function(name, index) {
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach((menuItem) => {
            const itemName = menuItem.querySelector('.item-name');
            if (itemName && itemName.textContent.trim() === name) {
                menuItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                MenuUtils.highlightElement(menuItem, '0 0 20px rgba(245, 166, 35, 0.5)', 2000);
            }
        });
    };

    // Initialize Add to Cart with custom selectors for this template
    const addBtns = document.querySelectorAll('.item-image .add-btn');
    addBtns.forEach((btn) => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            const menuItem = this.closest('.menu-item');
            if (!menuItem) return;

            const nameEl = menuItem.querySelector('.item-name');
            const priceEl = menuItem.querySelector('.price-tag');

            if (!nameEl || !priceEl) return;

            const name = nameEl.textContent.trim();
            const price = MenuUtils.parsePrice(priceEl.textContent);

            // Add to cart
            if (window.menuCart) {
                window.menuCart.addItem(name, price, 1);
            }

            // Use common utilities for animations
            MenuUtils.animateAddButton(this, '#28a745');
            MenuUtils.createFloatAnimation(this, '+1', 'var(--accent-yellow, #f5a623)');
        });
    });
});
