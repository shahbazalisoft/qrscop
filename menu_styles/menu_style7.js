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

    // Search functionality
    const searchBtn = document.querySelector('.search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            // Create search modal
            const modal = document.createElement('div');
            modal.className = 'search-modal';
            modal.innerHTML = `
                <div class="search-modal-content">
                    <div class="search-header">
                        <input type="text" placeholder="Search menu items..." class="search-input" autofocus>
                        <button class="close-search"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="search-results"></div>
                </div>
            `;

            document.body.appendChild(modal);

            // Add modal styles dynamically
            const style = document.createElement('style');
            style.textContent = `
                .search-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.95);
                    z-index: 3000;
                    display: flex;
                    flex-direction: column;
                    padding: 20px;
                }
                .search-modal-content {
                    max-width: 480px;
                    width: 100%;
                    margin: 0 auto;
                }
                .search-header {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 20px;
                }
                .search-modal .search-input {
                    flex: 1;
                    padding: 15px 20px;
                    border-radius: 25px;
                    border: 2px solid var(--primary-color, #c9a227);
                    background: var(--card-bg, #1a1a1a);
                    color: var(--text-primary, #fff);
                    font-size: 1rem;
                    outline: none;
                }
                .search-modal .search-input::placeholder {
                    color: var(--text-secondary, #888);
                }
                .close-search {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    background: var(--card-bg, #1a1a1a);
                    border: none;
                    color: var(--text-primary, #fff);
                    font-size: 1.2rem;
                    cursor: pointer;
                }
                .search-results {
                    max-height: calc(100vh - 120px);
                    overflow-y: auto;
                }
                .search-result-item {
                    padding: 15px;
                    background: var(--card-bg, #1a1a1a);
                    border-radius: 10px;
                    margin-bottom: 10px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }
                .search-result-item:hover {
                    background: rgba(201, 162, 39, 0.1);
                }
                .search-result-item h4 {
                    margin: 0 0 5px 0;
                    font-size: 1rem;
                    color: var(--text-primary, #fff);
                }
                .search-result-item p {
                    margin: 0;
                    font-size: 0.8rem;
                    color: var(--text-secondary, #888);
                }
                .search-result-price {
                    color: var(--primary-color, #c9a227);
                    font-weight: 600;
                }
                .no-results {
                    text-align: center;
                    padding: 40px;
                    color: var(--text-secondary, #888);
                }
            `;
            document.head.appendChild(style);

            const searchInput = modal.querySelector('.search-input');
            const searchResults = modal.querySelector('.search-results');
            const closeBtn = modal.querySelector('.close-search');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                searchResults.innerHTML = '';

                if (query.length < 2) {
                    return;
                }

                let hasResults = false;

                menuItems.forEach(item => {
                    const name = item.dataset.item.toLowerCase();
                    const desc = item.querySelector('.item-desc')?.textContent.toLowerCase() || '';

                    if (name.includes(query) || desc.includes(query)) {
                        hasResults = true;
                        const resultItem = document.createElement('div');
                        resultItem.className = 'search-result-item';

                        const quarterP = item.dataset.quarter;
                        const fullP = item.dataset.full;

                        resultItem.innerHTML = `
                            <div>
                                <h4>${item.dataset.item}</h4>
                                <p>${item.querySelector('.item-desc')?.textContent.substring(0, 50) || ''}...</p>
                            </div>
                            <span class="search-result-price">₹${quarterP} - ₹${fullP}</span>
                        `;

                        // Click to open popup
                        resultItem.addEventListener('click', function() {
                            modal.remove();
                            style.remove();
                            openSizePopup(item);
                        });

                        searchResults.appendChild(resultItem);
                    }
                });

                // Also search bread items
                const breadItems = document.querySelectorAll('.bread-item');
                breadItems.forEach(item => {
                    const name = item.querySelector('h4').textContent.toLowerCase();
                    const desc = item.querySelector('p')?.textContent.toLowerCase() || '';

                    if (name.includes(query) || desc.includes(query)) {
                        hasResults = true;
                        const resultItem = document.createElement('div');
                        resultItem.className = 'search-result-item';

                        const price = item.querySelector('.bread-price span').textContent;
                        const addBtnSm = item.querySelector('.add-btn-sm');

                        resultItem.innerHTML = `
                            <div>
                                <h4>${item.querySelector('h4').textContent}</h4>
                                <p>${item.querySelector('p')?.textContent || ''}</p>
                            </div>
                            <span class="search-result-price">${price}</span>
                        `;

                        // Click to add bread item directly
                        resultItem.addEventListener('click', function() {
                            if (addBtnSm) {
                                const itemName = addBtnSm.dataset.item;
                                const itemPrice = parseInt(addBtnSm.dataset.price);
                                if (window.menuCart) {
                                    window.menuCart.addItem(itemName, itemPrice, 1);
                                }
                            }
                            modal.remove();
                            style.remove();
                        });

                        searchResults.appendChild(resultItem);
                    }
                });

                if (!hasResults) {
                    searchResults.innerHTML = '<div class="no-results"><i class="bi bi-search" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>No items found</div>';
                }
            });

            closeBtn.addEventListener('click', function() {
                modal.remove();
                style.remove();
            });

            // Close on escape key
            function handleEscape(e) {
                if (e.key === 'Escape') {
                    modal.remove();
                    style.remove();
                    document.removeEventListener('keydown', handleEscape);
                }
            }
            document.addEventListener('keydown', handleEscape);
        });
    }
});
