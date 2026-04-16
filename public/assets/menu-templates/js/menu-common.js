// ===== COMMON MENU FUNCTIONALITY =====
// This file contains shared menu logic for all menu templates
// Include this BEFORE the specific menu style JS

// ===== MENU UTILITIES =====
const MenuUtils = {
    // Format price with currency symbol
    formatPrice(price, currency = '₹') {
        return currency + price;
    },

    // Parse price from text (removes non-numeric characters)
    parsePrice(priceText) {
        return parseInt(priceText.replace(/[^\d]/g, '')) || 0;
    },

    // Debounce function for search input
    debounce(func, wait = 300) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Create floating animation element
    createFloatAnimation(element, text, color = 'var(--accent-primary, #c9a962)') {
        const floater = document.createElement('span');
        floater.textContent = text;
        floater.style.cssText = `
            position: fixed;
            color: ${color};
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
    },

    // Animate add button (check mark animation)
    animateAddButton(btn, successColor = '#00a651') {
        const originalHTML = btn.innerHTML;
        const originalBg = btn.style.background;

        btn.innerHTML = '<i class="bi bi-check"></i>';
        btn.style.background = successColor;
        btn.style.transform = 'scale(1.2)';

        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = originalBg;
            btn.style.transform = '';
        }, 500);
    },

    // Highlight element temporarily
    highlightElement(element, highlightStyle = '0 0 20px rgba(201, 169, 98, 0.5)', duration = 2000) {
        const originalBoxShadow = element.style.boxShadow;
        element.style.boxShadow = highlightStyle;
        element.style.transition = 'box-shadow 0.3s ease';

        setTimeout(() => {
            element.style.boxShadow = originalBoxShadow;
        }, duration);
    },

    // Scroll to element with offset
    scrollToElement(element, offset = 0) {
        const targetPosition = element.offsetTop - offset;
        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    },

    // Get scroll offset (header + nav height)
    getScrollOffset() {
        const header = document.querySelector('.header');
        const nav = document.querySelector('.category-nav, .category-tabs');
        let offset = 0;

        if (header) offset += header.offsetHeight;
        if (nav) offset += nav.offsetHeight;

        return offset + 20; // Extra padding
    }
};

// ===== CATEGORY NAVIGATION =====
class CategoryNavigation {
    constructor(options = {}) {
        this.options = {
            btnSelector: '.cat-btn, .tab-btn',
            sectionSelector: '.menu-section',
            activeClass: 'active',
            dataAttribute: 'category',
            defaultCategory: 'recommended',
            scrollOffset: 60,
            ...options
        };

        this.buttons = document.querySelectorAll(this.options.btnSelector);
        this.sections = document.querySelectorAll(this.options.sectionSelector);

        if (this.buttons.length > 0 && this.sections.length > 0) {
            this.init();
        }
    }

    init() {
        this.bindClickEvents();
        this.bindScrollEvents();
    }

    bindClickEvents() {
        this.buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const category = btn.dataset[this.options.dataAttribute] || btn.dataset.category;
                this.scrollToSection(category);
                this.setActiveButton(btn);
            });
        });
    }

    bindScrollEvents() {
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    this.updateActiveOnScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

    scrollToSection(categoryId) {
        const section = document.getElementById(categoryId);
        if (section) {
            const offset = MenuUtils.getScrollOffset();
            MenuUtils.scrollToElement(section, offset);
        }
    }

    setActiveButton(activeBtn) {
        this.buttons.forEach(btn => btn.classList.remove(this.options.activeClass));
        activeBtn.classList.add(this.options.activeClass);
    }

    updateActiveOnScroll() {
        const offset = MenuUtils.getScrollOffset();
        let current = this.options.defaultCategory;

        this.sections.forEach(section => {
            const sectionTop = section.offsetTop - offset - this.options.scrollOffset;
            if (window.scrollY >= sectionTop) {
                current = section.getAttribute('id');
            }
        });

        this.buttons.forEach(btn => {
            btn.classList.remove(this.options.activeClass);
            const btnCategory = btn.dataset[this.options.dataAttribute] || btn.dataset.category;
            if (btnCategory === current) {
                btn.classList.add(this.options.activeClass);
            }
        });
    }
}

// ===== MENU SEARCH =====
class MenuSearch {
    constructor(menuData = [], options = {}) {
        this.menuData = menuData;
        this.options = {
            overlaySelector: '.search-overlay',
            inputSelector: '.search-input',
            closeBtnSelector: '.search-close-btn',
            clearBtnSelector: '.search-clear-btn',
            resultsSelector: '.search-results',
            searchBtnSelector: '.search-btn',
            highlightColor: 'var(--accent-primary, #c9a962)',
            currency: '₹',
            ...options
        };

        this.overlay = document.querySelector(this.options.overlaySelector);
        this.input = document.querySelector(this.options.inputSelector);
        this.closeBtn = document.querySelector(this.options.closeBtnSelector);
        this.clearBtn = document.querySelector(this.options.clearBtnSelector);
        this.results = document.querySelector(this.options.resultsSelector);
        this.searchBtn = document.querySelector(this.options.searchBtnSelector);

        if (this.overlay && this.input) {
            this.init();
        }
    }

    init() {
        this.bindEvents();
        this.renderPlaceholder();
    }

    bindEvents() {
        // Open search
        if (this.searchBtn) {
            this.searchBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.open();
            });
        }

        // Close search
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.close());
        }

        // Clear input
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', () => {
                this.input.value = '';
                this.clearBtn.style.display = 'none';
                this.input.focus();
                this.renderPlaceholder();
            });
        }

        // Input handling
        if (this.input) {
            this.input.addEventListener('input', MenuUtils.debounce((e) => {
                const query = e.target.value;
                if (this.clearBtn) {
                    this.clearBtn.style.display = query ? 'flex' : 'none';
                }
                this.search(query);
            }, 200));

            // Escape to close
            this.input.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.close();
            });
        }
    }

    open() {
        this.overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => this.input.focus(), 100);
    }

    close() {
        this.overlay.classList.remove('active');
        document.body.style.overflow = '';
        this.input.value = '';
        if (this.clearBtn) this.clearBtn.style.display = 'none';
        this.renderPlaceholder();
    }

    renderPlaceholder() {
        if (this.results) {
            this.results.innerHTML = `
                <div class="search-placeholder">
                    <i class="bi bi-search"></i>
                    <p>Search for your favorite dishes</p>
                </div>
            `;
        }
    }

    search(query) {
        if (!query.trim()) {
            this.renderPlaceholder();
            return;
        }

        const filtered = this.menuData.filter(item =>
            item.name.toLowerCase().includes(query.toLowerCase()) ||
            (item.category && item.category.toLowerCase().includes(query.toLowerCase()))
        );

        if (filtered.length === 0) {
            this.renderNoResults(query);
        } else {
            this.renderResults(filtered, query);
        }
    }

    renderNoResults(query) {
        if (this.results) {
            this.results.innerHTML = `
                <div class="search-no-results">
                    <i class="bi bi-emoji-frown"></i>
                    <p>No dishes found for "${query}"</p>
                </div>
            `;
        }
    }

    renderResults(items, query) {
        if (!this.results) return;

        // Group by category
        const grouped = items.reduce((acc, item) => {
            const cat = item.category || 'Other';
            if (!acc[cat]) acc[cat] = [];
            acc[cat].push(item);
            return acc;
        }, {});

        let html = '';
        for (const category in grouped) {
            html += `<div class="search-category-label">${category}</div>`;
            grouped[category].forEach(item => {
                const highlightedName = this.highlightMatch(item.name, query);
                html += `
                    <div class="search-result-item" data-name="${item.name}" data-index="${this.menuData.indexOf(item)}">
                        <div class="search-result-info">
                            <span class="search-result-badge ${item.isVeg ? 'veg' : 'non-veg'}"></span>
                            <span class="search-result-name">${highlightedName}</span>
                        </div>
                        <span class="search-result-price">${this.options.currency}${item.price}</span>
                    </div>
                `;
            });
        }

        this.results.innerHTML = html;
        this.bindResultClickEvents();
    }

    highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    bindResultClickEvents() {
        this.results.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', () => {
                const name = item.dataset.name;
                const index = parseInt(item.dataset.index);
                this.close();
                this.onResultClick(name, index);
            });
        });
    }

    // Override this method in specific implementations
    onResultClick(name, index) {
        // Default: find and scroll to menu item
        const menuItems = document.querySelectorAll('.menu-item, .menu-card');
        menuItems.forEach((menuItem, i) => {
            const itemName = menuItem.querySelector('.item-name, .card-title');
            if (itemName && itemName.textContent.trim() === name) {
                menuItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                MenuUtils.highlightElement(menuItem);
            }
        });
    }

    // Update menu data
    setMenuData(data) {
        this.menuData = data;
    }
}

// ===== SCROLL REVEAL =====
class ScrollReveal {
    constructor(options = {}) {
        this.options = {
            selector: '.menu-section, .menu-item, .menu-card',
            rootMargin: '0px',
            threshold: 0.1,
            animationClass: 'animate-fadeInUp',
            ...options
        };

        this.init();
    }

    init() {
        const elements = document.querySelectorAll(this.options.selector);
        if (elements.length === 0) return;

        // Set initial state
        elements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.5s ease';
        });

        // Create observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            root: null,
            rootMargin: this.options.rootMargin,
            threshold: this.options.threshold
        });

        elements.forEach(el => observer.observe(el));
    }
}

// ===== FLOATING BUTTON HIDE ON SCROLL =====
class FloatingButtonScroll {
    constructor(selector = '.floating-cart', options = {}) {
        this.button = document.querySelector(selector);
        this.options = {
            hideThreshold: 200,
            showOnScrollUp: true,
            ...options
        };
        this.lastScrollY = window.scrollY;

        if (this.button) {
            this.init();
        }
    }

    init() {
        window.addEventListener('scroll', () => {
            if (this.options.showOnScrollUp) {
                this.handleScrollDirection();
            } else {
                this.handleScrollPosition();
            }
        });
    }

    handleScrollDirection() {
        const currentScrollY = window.scrollY;

        if (currentScrollY > this.lastScrollY && currentScrollY > this.options.hideThreshold) {
            // Scrolling down - hide
            this.button.style.transform = 'translateY(100px)';
            this.button.style.opacity = '0';
        } else {
            // Scrolling up - show
            this.button.style.transform = 'translateY(0)';
            this.button.style.opacity = '1';
        }

        this.lastScrollY = currentScrollY;
    }

    handleScrollPosition() {
        if (window.scrollY > this.options.hideThreshold) {
            this.button.style.transform = 'translateY(100px)';
            this.button.style.opacity = '0';
        } else {
            this.button.style.transform = 'translateY(0)';
            this.button.style.opacity = '1';
        }
    }
}

// ===== SCROLL TO TOP BUTTON =====
class ScrollToTop {
    constructor(selector = '.scroll-top-btn', options = {}) {
        this.button = document.querySelector(selector);
        this.options = {
            showThreshold: 300,
            visibleClass: 'visible',
            ...options
        };

        if (this.button) {
            this.init();
        }
    }

    init() {
        // Toggle visibility on scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > this.options.showThreshold) {
                this.button.classList.add(this.options.visibleClass);
            } else {
                this.button.classList.remove(this.options.visibleClass);
            }
        });

        // Scroll to top on click
        this.button.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// ===== ADD TO CART HANDLER =====
class AddToCartHandler {
    constructor(options = {}) {
        this.options = {
            buttonSelector: '.add-btn',
            itemSelector: '.menu-item, .menu-card',
            nameSelector: '.item-name, .card-title, h3, h4',
            priceSelector: '.price, .item-price, .card-price',
            successColor: '#00a651',
            floatAnimationColor: 'var(--accent-primary, #c9a962)',
            ...options
        };

        this.init();
    }

    init() {
        const buttons = document.querySelectorAll(this.options.buttonSelector);

        buttons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleClick(e, btn));
        });
    }

    handleClick(e, btn) {
        e.stopPropagation();

        const item = btn.closest(this.options.itemSelector);
        if (!item) return;

        const nameEl = item.querySelector(this.options.nameSelector);
        const priceEl = item.querySelector(this.options.priceSelector);

        if (!nameEl || !priceEl) return;

        const name = nameEl.textContent.trim();
        const price = MenuUtils.parsePrice(priceEl.textContent);

        // Add to cart using global menuCart (from cart-common.js)
        if (window.menuCart) {
            window.menuCart.addItem(name, price, 1);
        }

        // Animate button
        MenuUtils.animateAddButton(btn, this.options.successColor);

        // Float animation
        MenuUtils.createFloatAnimation(btn, '+1', this.options.floatAnimationColor);
    }
}

// ===== HEADER BUTTON ANIMATIONS =====
class HeaderButtonAnimations {
    constructor(selector = '.menu-btn, .search-btn') {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    }
}

// ===== AUTO INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    // Add floatUp animation keyframes if not already present
    if (!document.querySelector('#menu-common-styles')) {
        const style = document.createElement('style');
        style.id = 'menu-common-styles';
        style.textContent = `
            @keyframes floatUp {
                0% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-30px); }
            }
        `;
        document.head.appendChild(style);
    }

    // Auto-initialize common features (can be disabled by setting window.menuCommonAutoInit = false)
    if (window.menuCommonAutoInit !== false) {
        // Initialize header button animations
        new HeaderButtonAnimations();

        // Initialize scroll to top if button exists
        if (document.querySelector('.scroll-top-btn')) {
            new ScrollToTop();
        }
    }
});

// ===== RIPPLE EFFECT =====
class RippleEffect {
    constructor(selector = '.ripple-container, .btn-gradient, .pill') {
        document.querySelectorAll(selector).forEach(el => {
            el.addEventListener('click', (e) => this.createRipple(e, el));
        });
    }

    createRipple(event, element) {
        const ripple = document.createElement('span');
        ripple.className = 'ripple';

        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';

        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    }
}

// ===== STAGGERED ANIMATION =====
class StaggeredAnimation {
    constructor(options = {}) {
        this.options = {
            selector: '.menu-card, .menu-item',
            baseDelay: 0.1,
            delayIncrement: 0.05,
            animationClass: 'animate-fadeInUp',
            ...options
        };

        this.init();
    }

    init() {
        const elements = document.querySelectorAll(this.options.selector);
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.animationDelay = (this.options.baseDelay + index * this.options.delayIncrement) + 's';
            el.classList.add(this.options.animationClass);
        });
    }
}

// ===== LAZY IMAGE LOADING =====
class LazyImageLoader {
    constructor(selector = 'img[data-src]') {
        this.images = document.querySelectorAll(selector);

        if ('IntersectionObserver' in window) {
            this.initObserver();
        } else {
            this.loadAllImages();
        }
    }

    initObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '50px'
        });

        this.images.forEach(img => observer.observe(img));
    }

    loadImage(img) {
        const src = img.dataset.src;
        if (src) {
            img.src = src;
            img.removeAttribute('data-src');
            img.classList.add('loaded');
        }
    }

    loadAllImages() {
        this.images.forEach(img => this.loadImage(img));
    }
}

// ===== THEME MANAGER =====
class ThemeManager {
    constructor(options = {}) {
        this.options = {
            storageKey: 'menu-theme',
            defaultTheme: 'dark',
            themes: {
                dark: {
                    '--bg-primary': '#0d0d0d',
                    '--bg-secondary': '#1a1a1a',
                    '--text-primary': '#ffffff',
                    '--text-secondary': 'rgba(255, 255, 255, 0.7)'
                },
                light: {
                    '--bg-primary': '#ffffff',
                    '--bg-secondary': '#f8f9fa',
                    '--text-primary': '#1a1a2e',
                    '--text-secondary': '#6c757d'
                }
            },
            ...options
        };

        this.currentTheme = localStorage.getItem(this.options.storageKey) || this.options.defaultTheme;
        this.applyTheme(this.currentTheme);
    }

    applyTheme(themeName) {
        const theme = this.options.themes[themeName];
        if (!theme) return;

        Object.entries(theme).forEach(([property, value]) => {
            document.documentElement.style.setProperty(property, value);
        });

        this.currentTheme = themeName;
        localStorage.setItem(this.options.storageKey, themeName);
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        this.applyTheme(newTheme);
        return newTheme;
    }

    getTheme() {
        return this.currentTheme;
    }
}

// ===== PULL TO REFRESH =====
class PullToRefresh {
    constructor(options = {}) {
        this.options = {
            threshold: 80,
            onRefresh: () => window.location.reload(),
            ...options
        };

        this.startY = 0;
        this.currentY = 0;
        this.isPulling = false;

        this.init();
    }

    init() {
        document.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: true });
        document.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: true });
        document.addEventListener('touchend', () => this.handleTouchEnd());
    }

    handleTouchStart(e) {
        if (window.scrollY === 0) {
            this.startY = e.touches[0].clientY;
            this.isPulling = true;
        }
    }

    handleTouchMove(e) {
        if (!this.isPulling) return;
        this.currentY = e.touches[0].clientY;
    }

    handleTouchEnd() {
        if (!this.isPulling) return;

        const pullDistance = this.currentY - this.startY;
        if (pullDistance > this.options.threshold && window.scrollY === 0) {
            this.options.onRefresh();
        }

        this.isPulling = false;
        this.startY = 0;
        this.currentY = 0;
    }
}

// ===== FLOATING CART BAR =====
class FloatingCartBar {
    constructor(options = {}) {
        this.options = {
            selector: '.floating-cart-bar',
            hideOnScroll: true,
            hideThreshold: 100,
            ...options
        };

        this.element = document.querySelector(this.options.selector);
        this.lastScrollY = window.scrollY;

        if (this.element && this.options.hideOnScroll) {
            this.initScrollBehavior();
        }
    }

    initScrollBehavior() {
        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;

            if (currentScrollY > this.lastScrollY && currentScrollY > this.options.hideThreshold) {
                this.element.classList.add('hidden');
            } else {
                this.element.classList.remove('hidden');
            }

            this.lastScrollY = currentScrollY;
        });
    }

    show() {
        this.element.classList.remove('hidden');
    }

    hide() {
        this.element.classList.add('hidden');
    }

    updateCount(count) {
        const countEl = this.element.querySelector('.cart-count');
        if (countEl) countEl.textContent = count;
    }

    updateTotal(total, currency = '₹') {
        const totalEl = this.element.querySelector('.cart-total');
        if (totalEl) totalEl.textContent = currency + total;
    }
}

// ===== CARD HOVER EFFECTS =====
class CardHoverEffects {
    constructor(selector = '.menu-card, .glass-card') {
        document.querySelectorAll(selector).forEach(card => {
            card.addEventListener('mousemove', (e) => this.handleMouseMove(e, card));
            card.addEventListener('mouseleave', (e) => this.handleMouseLeave(e, card));
        });
    }

    handleMouseMove(e, card) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = (y - centerY) / 20;
        const rotateY = (centerX - x) / 20;

        card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
    }

    handleMouseLeave(e, card) {
        card.style.transform = '';
    }
}

// ===== SMOOTH ANCHOR SCROLL =====
class SmoothAnchorScroll {
    constructor() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = anchor.getAttribute('href').slice(1);
                const targetEl = document.getElementById(targetId);

                if (targetEl) {
                    const offset = MenuUtils.getScrollOffset();
                    const targetPosition = targetEl.offsetTop - offset;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
}

// ===== TOUCH SWIPE HANDLER =====
class TouchSwipeHandler {
    constructor(element, options = {}) {
        this.element = typeof element === 'string' ? document.querySelector(element) : element;
        this.options = {
            threshold: 50,
            onSwipeLeft: () => {},
            onSwipeRight: () => {},
            onSwipeUp: () => {},
            onSwipeDown: () => {},
            ...options
        };

        this.startX = 0;
        this.startY = 0;

        if (this.element) {
            this.init();
        }
    }

    init() {
        this.element.addEventListener('touchstart', (e) => {
            this.startX = e.touches[0].clientX;
            this.startY = e.touches[0].clientY;
        }, { passive: true });

        this.element.addEventListener('touchend', (e) => {
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;

            const diffX = this.startX - endX;
            const diffY = this.startY - endY;

            if (Math.abs(diffX) > Math.abs(diffY)) {
                // Horizontal swipe
                if (Math.abs(diffX) > this.options.threshold) {
                    if (diffX > 0) {
                        this.options.onSwipeLeft();
                    } else {
                        this.options.onSwipeRight();
                    }
                }
            } else {
                // Vertical swipe
                if (Math.abs(diffY) > this.options.threshold) {
                    if (diffY > 0) {
                        this.options.onSwipeUp();
                    } else {
                        this.options.onSwipeDown();
                    }
                }
            }
        }, { passive: true });
    }
}

// Export for use in specific menu styles
window.MenuUtils = MenuUtils;
window.CategoryNavigation = CategoryNavigation;
window.MenuSearch = MenuSearch;
window.ScrollReveal = ScrollReveal;
window.FloatingButtonScroll = FloatingButtonScroll;
window.ScrollToTop = ScrollToTop;
window.AddToCartHandler = AddToCartHandler;
window.HeaderButtonAnimations = HeaderButtonAnimations;
window.RippleEffect = RippleEffect;
window.StaggeredAnimation = StaggeredAnimation;
window.LazyImageLoader = LazyImageLoader;
window.ThemeManager = ThemeManager;
window.PullToRefresh = PullToRefresh;
window.FloatingCartBar = FloatingCartBar;
window.CardHoverEffects = CardHoverEffects;
window.SmoothAnchorScroll = SmoothAnchorScroll;
window.TouchSwipeHandler = TouchSwipeHandler;

// ===== MENU STYLE 17 - LEFT SIDEBAR CATEGORY LAYOUT =====
class Style17CategorySidebar {
    constructor(options = {}) {
        this.options = {
            sidebarSelector: '.s17-sidebar',
            categoryItemSelector: '.s17-cat-item',
            productCardSelector: '.s17-product-card',
            productsGridSelector: '.s17-products-grid',
            topBarBtnSelector: '.s17-top-btn',
            addBtnSelector: '.s17-add-btn',
            activeClass: 'active',
            ...options
        };

        this.sidebar = document.querySelector(this.options.sidebarSelector);
        this.categoryItems = document.querySelectorAll(this.options.categoryItemSelector);
        this.productCards = document.querySelectorAll(this.options.productCardSelector);
        this.topBarBtns = document.querySelectorAll(this.options.topBarBtnSelector);
        this.addBtns = document.querySelectorAll(this.options.addBtnSelector);

        if (this.sidebar && this.categoryItems.length > 0) {
            this.init();
        }
    }

    init() {
        this.bindCategoryClickEvents();
        this.bindTopBarClickEvents();
        this.bindAddBtnClickEvents();
    }

    bindCategoryClickEvents() {
        this.categoryItems.forEach(item => {
            item.addEventListener('click', () => {
                const category = item.dataset.category;
                this.setActiveCategory(item);
                this.filterProducts(category);
            });
        });
    }

    bindTopBarClickEvents() {
        this.topBarBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                this.topBarBtns.forEach(b => b.classList.remove(this.options.activeClass));
                btn.classList.add(this.options.activeClass);
            });
        });
    }

    bindAddBtnClickEvents() {
        this.addBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();

                const card = btn.closest(this.options.productCardSelector);
                if (!card) return;

                const nameEl = card.querySelector('.s17-product-name');
                const priceEl = card.querySelector('.s17-current-price');

                if (!nameEl || !priceEl) return;

                const name = nameEl.textContent.trim();
                const price = parseInt(priceEl.textContent.replace(/[^\d]/g, '')) || 0;

                // Add to cart
                if (window.menuCart) {
                    window.menuCart.addItem(name, price, 1);
                }

                // Toggle button state
                btn.classList.add('added');
                btn.innerHTML = '<i class="bi bi-check"></i>';

                // Create float animation
                this.createFloatAnimation(btn, '+1');
            });
        });
    }

    setActiveCategory(activeItem) {
        this.categoryItems.forEach(item => {
            item.classList.remove(this.options.activeClass);
        });
        activeItem.classList.add(this.options.activeClass);
    }

    filterProducts(category) {
        const productsGrid = document.querySelector(this.options.productsGridSelector);

        this.productCards.forEach((card, index) => {
            const cardCategory = card.dataset.category;

            if (category === 'all' || cardCategory === category) {
                card.style.display = 'block';
                // Re-trigger animation
                card.style.animation = 'none';
                card.offsetHeight;
                card.style.animation = `fadeInUp 0.4s ease forwards ${(index % 4 + 1) * 0.05}s`;
            } else {
                card.style.display = 'none';
            }
        });
    }

    createFloatAnimation(element, text) {
        const floater = document.createElement('span');
        floater.textContent = text;
        floater.style.cssText = `
            position: fixed;
            color: #0c831f;
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
}

// Export Style17 class
window.Style17CategorySidebar = Style17CategorySidebar;

// ===== MENU STYLE 19 - DARK THEME LEFT SIDEBAR (Based on Style 12) =====
class Style19CategorySidebar {
    constructor(options = {}) {
        this.options = {
            sidebarSelector: '.s19-sidebar',
            categoryItemSelector: '.s19-cat-item',
            menuSectionSelector: '.s19-menu-section',
            menuItemSelector: '.s19-menu-item',
            addBtnSelector: '.s19-add-btn',
            searchBtnSelector: '.s19-search-btn',
            searchOverlaySelector: '.s19-search-overlay',
            searchInputSelector: '.s19-search-input',
            searchCloseBtnSelector: '.s19-search-close-btn',
            searchClearBtnSelector: '.s19-search-clear-btn',
            searchResultsSelector: '.s19-search-results',
            activeClass: 'active',
            ...options
        };

        this.sidebar = document.querySelector(this.options.sidebarSelector);
        this.categoryItems = document.querySelectorAll(this.options.categoryItemSelector);
        this.menuSections = document.querySelectorAll(this.options.menuSectionSelector);
        this.menuItems = document.querySelectorAll(this.options.menuItemSelector);
        this.addBtns = document.querySelectorAll(this.options.addBtnSelector);

        // Search elements
        this.searchBtn = document.querySelector(this.options.searchBtnSelector);
        this.searchOverlay = document.querySelector(this.options.searchOverlaySelector);
        this.searchInput = document.querySelector(this.options.searchInputSelector);
        this.searchCloseBtn = document.querySelector(this.options.searchCloseBtnSelector);
        this.searchClearBtn = document.querySelector(this.options.searchClearBtnSelector);
        this.searchResults = document.querySelector(this.options.searchResultsSelector);

        this.menuData = [];

        if (this.sidebar && this.categoryItems.length > 0) {
            this.init();
        }
    }

    init() {
        this.bindCategoryClickEvents();
        this.bindAddBtnClickEvents();
        this.bindSearchEvents();
    }

    setMenuData(data) {
        this.menuData = data;
    }

    bindCategoryClickEvents() {
        this.categoryItems.forEach(item => {
            item.addEventListener('click', () => {
                const category = item.dataset.category;
                this.setActiveCategory(item);
                this.filterSections(category);
            });
        });
    }

    setActiveCategory(activeItem) {
        this.categoryItems.forEach(item => {
            item.classList.remove(this.options.activeClass);
        });
        activeItem.classList.add(this.options.activeClass);
    }

    filterSections(category) {
        this.menuSections.forEach(section => {
            const sectionCategory = section.dataset.category || section.getAttribute('id');

            if (category === 'all' || sectionCategory === category) {
                section.style.display = 'block';
                // Re-trigger animations
                section.querySelectorAll('.s19-menu-item').forEach((item, i) => {
                    item.style.animation = 'none';
                    item.offsetHeight;
                    item.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                });
            } else {
                section.style.display = 'none';
            }
        });
    }

    bindAddBtnClickEvents() {
        this.addBtns.forEach((btn, index) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();

                const item = btn.closest('.s19-menu-item');
                if (!item) return;

                const nameEl = item.querySelector('.s19-item-name');
                const priceEl = item.querySelector('.s19-item-price');

                if (!nameEl || !priceEl) return;

                const name = nameEl.textContent.trim();
                const price = parseInt(priceEl.textContent.replace(/[^\d]/g, '')) || 0;

                // Add to cart
                if (window.menuCart) {
                    window.menuCart.addItem(name, price, 1);
                }

                // Toggle button state
                if (!btn.classList.contains('added')) {
                    btn.classList.add('added');
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-plus');
                        icon.classList.add('bi-check');
                    }

                    // Create float animation
                    this.createFloatAnimation(btn, '+1');
                }
            });
        });
    }

    createFloatAnimation(element, text) {
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

    // Search functionality
    bindSearchEvents() {
        if (this.searchBtn) {
            this.searchBtn.addEventListener('click', () => this.openSearch());
        }

        if (this.searchCloseBtn) {
            this.searchCloseBtn.addEventListener('click', () => this.closeSearch());
        }

        if (this.searchClearBtn) {
            this.searchClearBtn.addEventListener('click', () => {
                if (this.searchInput) {
                    this.searchInput.value = '';
                    this.searchClearBtn.style.display = 'none';
                    this.searchInput.focus();
                    this.renderSearchPlaceholder();
                }
            });
        }

        if (this.searchInput) {
            this.searchInput.addEventListener('input', MenuUtils.debounce((e) => {
                const query = e.target.value;
                if (this.searchClearBtn) {
                    this.searchClearBtn.style.display = query ? 'flex' : 'none';
                }
                this.search(query);
            }, 200));

            this.searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.closeSearch();
            });
        }
    }

    openSearch() {
        if (this.searchOverlay) {
            this.searchOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                if (this.searchInput) this.searchInput.focus();
            }, 100);
        }
    }

    closeSearch() {
        if (this.searchOverlay) {
            this.searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
            if (this.searchInput) this.searchInput.value = '';
            if (this.searchClearBtn) this.searchClearBtn.style.display = 'none';
            this.renderSearchPlaceholder();
        }
    }

    renderSearchPlaceholder() {
        if (this.searchResults) {
            this.searchResults.innerHTML = `
                <div class="s19-search-placeholder">
                    <i class="bi bi-search"></i>
                    <p>Search for your favorite dishes</p>
                </div>
            `;
        }
    }

    search(query) {
        if (!query.trim()) {
            this.renderSearchPlaceholder();
            return;
        }

        const filtered = this.menuData.filter(item =>
            item.name.toLowerCase().includes(query.toLowerCase()) ||
            (item.category && item.category.toLowerCase().includes(query.toLowerCase()))
        );

        if (filtered.length === 0) {
            this.renderNoResults(query);
        } else {
            this.renderSearchResults(filtered, query);
        }
    }

    renderNoResults(query) {
        if (this.searchResults) {
            this.searchResults.innerHTML = `
                <div class="s19-search-no-results">
                    <i class="bi bi-emoji-frown"></i>
                    <p>No dishes found for "${query}"</p>
                </div>
            `;
        }
    }

    renderSearchResults(items, query) {
        if (!this.searchResults) return;

        // Group by category
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
                const highlightedName = this.highlightMatch(item.name, query);
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

        this.searchResults.innerHTML = html;
        this.bindSearchResultClickEvents();
    }

    highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    bindSearchResultClickEvents() {
        if (!this.searchResults) return;

        this.searchResults.querySelectorAll('.s19-search-result-item').forEach(item => {
            item.addEventListener('click', () => {
                const name = item.dataset.name;
                this.closeSearch();
                this.scrollToMenuItem(name);
            });
        });
    }

    scrollToMenuItem(name) {
        const menuItems = document.querySelectorAll('.s19-menu-item');
        menuItems.forEach(menuItem => {
            const itemName = menuItem.querySelector('.s19-item-name');
            if (itemName && itemName.textContent.trim() === name) {
                // Show all sections first
                this.categoryItems.forEach(cat => {
                    if (cat.dataset.category === 'all') {
                        cat.click();
                    }
                });

                setTimeout(() => {
                    menuItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Highlight effect
                    menuItem.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.5)';
                    setTimeout(() => {
                        menuItem.style.boxShadow = '';
                    }, 2000);
                }, 100);
            }
        });
    }
}

// Export Style19 class
window.Style19CategorySidebar = Style19CategorySidebar;

// ===== MENU STYLE 20 - CATEGORY GRID WITH IMAGES =====
class Style20CategoryGrid {
    constructor(options = {}) {
        this.options = {
            categoryNavSelector: '.s20-category-nav',
            categoryItemSelector: '.s20-cat-item',
            menuSectionSelector: '.s20-menu-section',
            menuCardSelector: '.s20-menu-card',
            addBtnSelector: '.s20-add-cart-btn',
            bannerCarouselSelector: '.s20-banner-carousel',
            bannerTrackSelector: '.s20-banner-track',
            bannerSlideSelector: '.s20-banner-slide',
            bannerDotsSelector: '.s20-banner-dots',
            searchBtnSelector: '.s20-search-btn',
            searchOverlaySelector: '.s20-search-overlay',
            searchInputSelector: '.s20-search-input',
            searchCloseBtnSelector: '.s20-search-close-btn',
            searchClearBtnSelector: '.s20-search-clear-btn',
            searchResultsSelector: '.s20-search-results',
            activeClass: 'active',
            ...options
        };

        this.categoryItems = document.querySelectorAll(this.options.categoryItemSelector);
        this.menuSections = document.querySelectorAll(this.options.menuSectionSelector);
        this.menuCards = document.querySelectorAll(this.options.menuCardSelector);
        this.addBtns = document.querySelectorAll(this.options.addBtnSelector);

        // Banner elements
        this.bannerCarousel = document.querySelector(this.options.bannerCarouselSelector);
        this.bannerTrack = document.querySelector(this.options.bannerTrackSelector);
        this.bannerSlides = document.querySelectorAll(this.options.bannerSlideSelector);
        this.bannerDots = document.querySelectorAll(`${this.options.bannerDotsSelector} .dot`);

        // Search elements
        this.searchBtn = document.querySelector(this.options.searchBtnSelector);
        this.searchOverlay = document.querySelector(this.options.searchOverlaySelector);
        this.searchInput = document.querySelector(this.options.searchInputSelector);
        this.searchCloseBtn = document.querySelector(this.options.searchCloseBtnSelector);
        this.searchClearBtn = document.querySelector(this.options.searchClearBtnSelector);
        this.searchResults = document.querySelector(this.options.searchResultsSelector);

        // Banner state
        this.currentSlide = 0;
        this.autoSlideInterval = null;
        this.autoSlideDelay = 4000;

        // Menu data for search
        this.menuData = this.collectMenuData();

        this.init();
    }

    init() {
        this.bindCategoryClickEvents();
        this.bindAddBtnClickEvents();
        this.bindCardClickEvents();
        this.initBannerCarousel();
        this.bindSearchEvents();
    }

    collectMenuData() {
        const data = [];
        this.menuCards.forEach(card => {
            const nameEl = card.querySelector('.s20-card-title');
            const priceEl = card.querySelector('.s20-card-price');
            const typeEl = card.querySelector('.s20-item-type');
            const category = card.dataset.category || card.closest('.s20-menu-section')?.dataset.category || 'Other';

            if (nameEl && priceEl) {
                data.push({
                    name: nameEl.textContent.trim(),
                    price: parseInt(priceEl.textContent.replace(/[^\d]/g, '')) || 0,
                    isVeg: typeEl?.classList.contains('veg') || false,
                    category: category
                });
            }
        });
        return data;
    }

    bindCategoryClickEvents() {
        this.categoryItems.forEach(item => {
            item.addEventListener('click', () => {
                const category = item.dataset.category;
                this.setActiveCategory(item);
                this.filterSections(category);
            });
        });
    }

    setActiveCategory(activeItem) {
        this.categoryItems.forEach(item => {
            item.classList.remove(this.options.activeClass);
        });
        activeItem.classList.add(this.options.activeClass);
    }

    filterSections(category) {
        this.menuSections.forEach(section => {
            const sectionCategory = section.dataset.category || section.getAttribute('id');

            if (category === 'all' || sectionCategory === category) {
                section.style.display = 'block';
                // Re-trigger animations
                section.querySelectorAll('.s20-menu-card').forEach((card, i) => {
                    card.style.animation = 'none';
                    card.offsetHeight;
                    card.style.animation = `fadeInUp 0.4s ease forwards ${(i + 1) * 0.05}s`;
                });
            } else {
                section.style.display = 'none';
            }
        });
    }

    bindAddBtnClickEvents() {
        this.addBtns.forEach((btn, index) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();

                const card = btn.closest('.s20-menu-card');
                if (!card) return;

                const nameEl = card.querySelector('.s20-card-title');
                const priceEl = card.querySelector('.s20-card-price');

                if (!nameEl || !priceEl) return;

                const name = nameEl.textContent.trim();
                const price = parseInt(priceEl.textContent.replace(/[^\d]/g, '')) || 0;

                // Add to cart
                if (window.menuCart) {
                    window.menuCart.addItem(name, price, 1);
                }

                // Toggle button state
                if (!btn.classList.contains('added')) {
                    btn.classList.add('added');
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-plus');
                        icon.classList.add('bi-check');
                    }

                    // Create float animation
                    this.createFloatAnimation(btn, '+1');
                }
            });
        });
    }

    bindCardClickEvents() {
        // Initialize item detail popup if available
        if (typeof initItemDetailPopup === 'function' && this.menuData.length > 0) {
            // Add mrp field if not present (estimate as price * 1.25)
            const menuDataWithMrp = this.menuData.map(item => ({
                ...item,
                mrp: item.mrp || Math.round(item.price * 1.25)
            }));
            initItemDetailPopup(menuDataWithMrp);
        }

        this.menuCards.forEach((card, index) => {
            card.addEventListener('click', (e) => {
                // Don't open popup if clicking on add-to-cart button
                if (e.target.closest('.s20-add-cart-btn')) return;

                const itemData = this.menuData[index];
                if (itemData && typeof openItemDetail === 'function') {
                    const img = card.querySelector('.s20-card-image img');
                    const mainImage = img ? img.src : '';
                    // Add mrp if not present
                    const itemWithMrp = {
                        ...itemData,
                        mrp: itemData.mrp || Math.round(itemData.price * 1.25)
                    };
                    openItemDetail(itemWithMrp, mainImage);
                }
            });

            // Add cursor pointer style
            card.style.cursor = 'pointer';
        });
    }

    createFloatAnimation(element, text) {
        const floater = document.createElement('span');
        floater.textContent = text;
        floater.style.cssText = `
            position: fixed;
            color: #10847e;
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

    // Banner Carousel Methods
    initBannerCarousel() {
        if (!this.bannerCarousel || !this.bannerSlides.length) return;

        const prevBtn = this.bannerCarousel.querySelector('.s20-banner-nav.prev');
        const nextBtn = this.bannerCarousel.querySelector('.s20-banner-nav.next');

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                this.nextSlide();
                this.startAutoSlide();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                this.prevSlide();
                this.startAutoSlide();
            });
        }

        // Dot navigation
        this.bannerDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
                this.startAutoSlide();
            });
        });

        // Touch/Swipe support
        let touchStartX = 0;
        let touchEndX = 0;

        this.bannerCarousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            this.stopAutoSlide();
        }, { passive: true });

        this.bannerCarousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }
            this.startAutoSlide();
        }, { passive: true });

        // Pause on hover
        this.bannerCarousel.addEventListener('mouseenter', () => this.stopAutoSlide());
        this.bannerCarousel.addEventListener('mouseleave', () => this.startAutoSlide());

        // Start auto-slide
        if (this.bannerSlides.length > 1) {
            this.startAutoSlide();
        }
    }

    goToSlide(index) {
        if (index < 0) index = this.bannerSlides.length - 1;
        if (index >= this.bannerSlides.length) index = 0;

        this.currentSlide = index;
        if (this.bannerTrack) {
            this.bannerTrack.style.transform = `translateX(-${this.currentSlide * 100}%)`;
        }

        // Update dots
        this.bannerDots.forEach((dot, i) => {
            dot.classList.toggle('active', i === this.currentSlide);
        });
    }

    nextSlide() {
        this.goToSlide(this.currentSlide + 1);
    }

    prevSlide() {
        this.goToSlide(this.currentSlide - 1);
    }

    startAutoSlide() {
        this.stopAutoSlide();
        this.autoSlideInterval = setInterval(() => this.nextSlide(), this.autoSlideDelay);
    }

    stopAutoSlide() {
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
        }
    }

    // Search Methods
    bindSearchEvents() {
        if (this.searchBtn) {
            this.searchBtn.addEventListener('click', () => this.openSearch());
        }

        if (this.searchCloseBtn) {
            this.searchCloseBtn.addEventListener('click', () => this.closeSearch());
        }

        if (this.searchClearBtn) {
            this.searchClearBtn.addEventListener('click', () => {
                if (this.searchInput) {
                    this.searchInput.value = '';
                    this.searchClearBtn.style.display = 'none';
                    this.searchInput.focus();
                    this.renderSearchPlaceholder();
                }
            });
        }

        if (this.searchInput) {
            this.searchInput.addEventListener('input', MenuUtils.debounce((e) => {
                const query = e.target.value;
                if (this.searchClearBtn) {
                    this.searchClearBtn.style.display = query ? 'flex' : 'none';
                }
                this.search(query);
            }, 200));

            this.searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.closeSearch();
            });
        }
    }

    openSearch() {
        if (this.searchOverlay) {
            this.searchOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                if (this.searchInput) this.searchInput.focus();
            }, 100);
        }
    }

    closeSearch() {
        if (this.searchOverlay) {
            this.searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
            if (this.searchInput) this.searchInput.value = '';
            if (this.searchClearBtn) this.searchClearBtn.style.display = 'none';
            this.renderSearchPlaceholder();
        }
    }

    renderSearchPlaceholder() {
        if (this.searchResults) {
            this.searchResults.innerHTML = `
                <div class="s20-search-placeholder">
                    <i class="bi bi-search"></i>
                    <p>Search for your favorite dishes</p>
                </div>
            `;
        }
    }

    search(query) {
        if (!query.trim()) {
            this.renderSearchPlaceholder();
            return;
        }

        const filtered = this.menuData.filter(item =>
            item.name.toLowerCase().includes(query.toLowerCase()) ||
            (item.category && item.category.toLowerCase().includes(query.toLowerCase()))
        );

        if (filtered.length === 0) {
            this.renderNoResults(query);
        } else {
            this.renderSearchResults(filtered, query);
        }
    }

    renderNoResults(query) {
        if (this.searchResults) {
            this.searchResults.innerHTML = `
                <div class="s20-search-no-results">
                    <i class="bi bi-emoji-frown"></i>
                    <p>No dishes found for "${query}"</p>
                </div>
            `;
        }
    }

    renderSearchResults(items, query) {
        if (!this.searchResults) return;

        // Group by category
        const grouped = items.reduce((acc, item) => {
            const cat = item.category || 'Other';
            if (!acc[cat]) acc[cat] = [];
            acc[cat].push(item);
            return acc;
        }, {});

        let html = '';
        for (const category in grouped) {
            html += `<div class="s20-search-category-label">${category}</div>`;
            grouped[category].forEach(item => {
                const highlightedName = this.highlightMatch(item.name, query);
                html += `
                    <div class="s20-search-result-item" data-name="${item.name}">
                        <div class="s20-search-result-info">
                            <span class="s20-search-result-badge ${item.isVeg ? 'veg' : 'non-veg'}"></span>
                            <span class="s20-search-result-name">${highlightedName}</span>
                        </div>
                        <span class="s20-search-result-price">₹${item.price}</span>
                    </div>
                `;
            });
        }

        this.searchResults.innerHTML = html;
        this.bindSearchResultClickEvents();
    }

    highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    bindSearchResultClickEvents() {
        if (!this.searchResults) return;

        this.searchResults.querySelectorAll('.s20-search-result-item').forEach(item => {
            item.addEventListener('click', () => {
                const name = item.dataset.name;
                this.closeSearch();
                this.scrollToMenuItem(name);
            });
        });
    }

    scrollToMenuItem(name) {
        // First show all sections
        this.categoryItems.forEach(cat => {
            if (cat.dataset.category === 'all') {
                cat.click();
            }
        });

        setTimeout(() => {
            this.menuCards.forEach(card => {
                const itemName = card.querySelector('.s20-card-title');
                if (itemName && itemName.textContent.trim() === name) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Highlight effect
                    card.style.boxShadow = '0 0 20px rgba(16, 132, 126, 0.5)';
                    card.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        card.style.boxShadow = '';
                        card.style.transform = '';
                    }, 2000);
                }
            });
        }, 100);
    }
}

// Export Style20 class
window.Style20CategoryGrid = Style20CategoryGrid;
