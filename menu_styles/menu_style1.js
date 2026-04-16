// Menu Style 7 - Dark Minimal JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // ========== MENU DATA ==========
    const menuItemsData = [
        { name: 'Butter Chicken', price: 349, mrp: 449, isVeg: false, category: 'recommended' },
        { name: 'Margherita Pizza', price: 299, mrp: 399, isVeg: true, category: 'recommended' },
        { name: 'Hyderabadi Biryani', price: 399, mrp: 499, isVeg: false, category: 'recommended' },
        { name: 'Grilled Salmon', price: 599, mrp: 749, isVeg: false, category: 'recommended' },
        { name: 'Paneer Tikka', price: 249, mrp: 299, isVeg: true, category: 'starters' },
        { name: 'Crispy Samosa', price: 79, mrp: 99, isVeg: true, category: 'starters' },
        { name: 'Buffalo Wings', price: 299, mrp: 399, isVeg: false, category: 'starters' },
        { name: 'Spring Rolls', price: 149, mrp: 179, isVeg: true, category: 'starters' },
        { name: 'Dal Makhani', price: 229, mrp: 279, isVeg: true, category: 'mains' },
        { name: 'Palak Paneer', price: 269, mrp: 329, isVeg: true, category: 'mains' },
        { name: 'Garlic Naan', price: 69, mrp: 89, isVeg: true, category: 'mains' },
        { name: 'Tandoori Roti', price: 39, mrp: 49, isVeg: true, category: 'mains' },
        { name: 'Fluffy Pancakes', price: 179, mrp: 229, isVeg: true, category: 'desserts' },
        { name: 'Blueberry Cheesecake', price: 199, mrp: 249, isVeg: true, category: 'desserts' },
        { name: 'Gulab Jamun', price: 99, mrp: 129, isVeg: true, category: 'desserts' },
        { name: 'Masala Chai', price: 49, mrp: 59, isVeg: true, category: 'drinks' },
        { name: 'Cold Coffee', price: 129, mrp: 159, isVeg: true, category: 'drinks' },
        { name: 'Mango Lassi', price: 99, mrp: 129, isVeg: true, category: 'drinks' },
        { name: 'Fresh Orange Juice', price: 89, mrp: 109, isVeg: true, category: 'drinks' }
    ];

    // Initialize Item Detail Popup
    if (typeof initItemDetailPopup === 'function') {
        initItemDetailPopup(menuItemsData);
    }

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

    // Add to cart functionality
    const addBtns = document.querySelectorAll('.add-btn');
    const menuItemElements = document.querySelectorAll('.menu-item');

    addBtns.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            const itemData = menuItemsData[index];
            const menuItem = this.closest('.menu-item');
            const name = menuItem.querySelector('.item-name').textContent;
            const priceText = menuItem.querySelector('.price').textContent;
            const price = parseInt(priceText.replace(/[^\d]/g, ''));
            const isVeg = itemData ? itemData.isVeg : true;

            if (window.menuCart) {
                window.menuCart.addItem(name, price, 1, isVeg);
            }

            // Button animation
            this.innerHTML = '<i class="bi bi-check"></i>';
            this.style.background = 'var(--veg)';
            this.style.transform = 'scale(1.2)';

            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-plus"></i>';
                this.style.background = '';
                this.style.transform = '';
            }, 500);

            // Create floating animation
            createFloatAnimation(this, '+1');
        });
    });

    // Menu item click for popup
    menuItemElements.forEach((item, index) => {
        item.style.cursor = 'pointer';
        item.addEventListener('click', function(e) {
            if (e.target.closest('.add-btn')) return;

            const itemData = menuItemsData[index];
            if (itemData) {
                const img = item.querySelector('img');
                const mainImage = img ? img.src : '';
                openItemDetail(itemData, mainImage);
            }
        });
    });

    function createFloatAnimation(element, text) {
        const floater = document.createElement('span');
        floater.textContent = text;
        floater.style.cssText = `
            position: fixed;
            color: var(--accent-gold, #c9a962);
            font-size: 1rem;
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
            100% { opacity: 0; transform: translateY(-30px); }
        }
    `;
    document.head.appendChild(style);

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

    // Hide floating cart when scrolling down
    const floatingCart = document.querySelector('.floating-cart');
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', function() {
        if (floatingCart) {
            if (window.scrollY > lastScrollY && window.scrollY > 200) {
                floatingCart.style.transform = 'translateY(100px)';
                floatingCart.style.opacity = '0';
            } else {
                floatingCart.style.transform = 'translateY(0)';
                floatingCart.style.opacity = '1';
            }
        }
        lastScrollY = window.scrollY;
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

    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }
});
