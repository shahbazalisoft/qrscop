// ===== MENU INIT COMMON JS =====
// Shared initialization for menu items across all templates
// Handles: item detail popup, add-to-cart (with variation detection), float animation

(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var menuItemsData = window.menuItemsData || [];
        if (!menuItemsData.length) return;

        // Initialize Item Detail Popup
        if (typeof initItemDetailPopup === 'function') {
            initItemDetailPopup(menuItemsData);
        }

        // Initialize Size Picker
        if (typeof initSizePicker === 'function') {
            initSizePicker(menuItemsData);
        }

        // Find all menu item elements with data-index
        var menuItemElements = document.querySelectorAll('[data-index]');

        // Add-to-cart buttons
        menuItemElements.forEach(function (menuItem) {
            var addBtn = menuItem.querySelector('.add-btn, .card-add-btn, .add-cart-btn, .s19-add-btn, .s20-add-cart-btn, [data-add-btn]');
            if (!addBtn) return;

            addBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                var dataIndex = parseInt(menuItem.dataset.index);
                var itemData = menuItemsData[dataIndex];
                if (!itemData) return;

                var hasVariations = itemData.foodVariations && itemData.foodVariations.length > 0 &&
                    itemData.foodVariations.some(function (v) { return v.values && v.values.length > 0; });

                if (hasVariations && typeof openSizePicker === 'function') {
                    openSizePicker(dataIndex, addBtn);
                    return;
                }

                // Direct add to cart
                var priceEl = menuItem.querySelector('.price, .card-price, .item-price, .s19-item-price, .s20-card-price');
                var price = priceEl ? parseInt(priceEl.textContent.replace(/[^\d]/g, '')) : itemData.price;
                var itemId = parseInt(menuItem.dataset.itemId) || 0;

                if (window.menuCart) {
                    var origHTML = addBtn.innerHTML;
                    window.menuCart.addToCart({
                        cartKey: dataIndex + '-default',
                        index: dataIndex,
                        itemId: itemId,
                        name: itemData.name,
                        price: price,
                        mrp: itemData.mrp || price,
                        img: itemData.img || '',
                        isVeg: itemData.isVeg || false,
                        size: 'default',
                        qty: 1
                    }, function () {
                        addBtn.innerHTML = '<i class="bi bi-check"></i>';
                        setTimeout(function () { addBtn.innerHTML = origHTML; }, 500);
                    });
                }
            });
        });

        // Item click for detail popup
        menuItemElements.forEach(function (menuItem) {
            menuItem.style.cursor = 'pointer';
            menuItem.addEventListener('click', function (e) {
                if (e.target.closest('.add-btn, .card-add-btn, .add-cart-btn, .s19-add-btn, .s20-add-cart-btn, [data-add-btn]')) return;
                var dataIndex = parseInt(menuItem.dataset.index);
                var itemData = menuItemsData[dataIndex];
                if (itemData && typeof openItemDetail === 'function') {
                    itemData.index = dataIndex;
                    var img = menuItem.querySelector('img');
                    var mainImage = img ? img.src : '';
                    openItemDetail(itemData, mainImage);
                }
            });
        });
    });
})();
