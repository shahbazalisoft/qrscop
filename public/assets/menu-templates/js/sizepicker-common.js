// ===== SIZE PICKER COMMON JS =====
// Compact variation selector popup - shared across all menu templates

(function () {
    var spMenuItems = null;
    var spIndex = -1;
    var spSourceBtn = null;
    var spSize = 'default';
    var spQty = 1;
    var spVariationData = null;

    // DOM refs (cached after init)
    var spOverlay, spPopup, spTitle, spClose, spOptionsEl;
    var spMinus, spPlus, spQtyVal, spTotal, spAddBtn;

    function calcDiscount(price, item) {
        if (!item || !item.discount || item.discount <= 0) return price;
        if (item.discountType === 'percent') {
            return Math.round(price - (price * item.discount / 100));
        }
        return Math.max(0, price - item.discount);
    }

    function getSpPrice() {
        if (!spVariationData || !spMenuItems) return 0;
        var item = spMenuItems[spIndex];
        if (!item) return 0;
        var val = spVariationData.values.find(function (v) { return v.label === spSize; });
        var rawPrice = val ? parseFloat(val.optionPrice) : item.mrp || item.price;
        return calcDiscount(rawPrice, item);
    }

    function updateSpTotal() {
        var price = getSpPrice();
        spTotal.textContent = '\u20B9' + (price * spQty);
    }

    function closeSizePicker() {
        spOverlay.classList.remove('active');
        spPopup.classList.remove('active');
    }

    function openSizePicker(index, sourceBtn) {
        var item = spMenuItems[index];
        if (!item) return;
        var variations = item.foodVariations || [];
        var variation = null;
        for (var i = 0; i < variations.length; i++) {
            if (variations[i].values && variations[i].values.length > 0) {
                variation = variations[i];
                break;
            }
        }
        if (!variation) return;

        spIndex = index;
        spSourceBtn = sourceBtn;
        spQty = 1;
        spVariationData = variation;
        spQtyVal.textContent = spQty;
        spOptionsEl.innerHTML = '';
        spTitle.innerHTML = '<i class="bi bi-sliders"></i> ' + (variation.name ? ('Select ' + variation.name) : 'Select Size');

        var hasDiscount = item.discount && item.discount > 0;
        var defaultMrp = item.mrp || item.price;
        var defaultDiscounted = calcDiscount(defaultMrp, item);

        // Default option
        var defaultBtn = document.createElement('button');
        defaultBtn.className = 'sizepicker-btn active';
        var defaultPriceHTML = '';
        if (hasDiscount && defaultDiscounted < defaultMrp) {
            defaultPriceHTML = '<s class="sizepicker-mrp">\u20B9' + defaultMrp + '</s> <span class="sizepicker-disc-price">\u20B9' + defaultDiscounted + '</span>';
        } else {
            defaultPriceHTML = '\u20B9' + defaultMrp;
        }
        defaultBtn.innerHTML =
            '<span class="sizepicker-radio"></span>' +
            '<span class="sizepicker-name">Default</span>' +
            '<span class="sizepicker-price">' + defaultPriceHTML + '</span>';
        defaultBtn.addEventListener('click', function () {
            spOptionsEl.querySelectorAll('.sizepicker-btn').forEach(function (b) { b.classList.remove('active'); });
            defaultBtn.classList.add('active');
            spSize = 'default';
            updateSpTotal();
        });
        spOptionsEl.appendChild(defaultBtn);

        variation.values.forEach(function (val) {
            var optPrice = parseFloat(val.optionPrice);
            var discountedPrice = calcDiscount(optPrice, item);
            var btn = document.createElement('button');
            btn.className = 'sizepicker-btn';
            var priceHTML = '';
            if (hasDiscount && discountedPrice < optPrice) {
                priceHTML = '<s class="sizepicker-mrp">\u20B9' + optPrice + '</s> <span class="sizepicker-disc-price">\u20B9' + discountedPrice + '</span>';
            } else {
                priceHTML = '\u20B9' + optPrice;
            }
            btn.innerHTML =
                '<span class="sizepicker-radio"></span>' +
                '<span class="sizepicker-name">' + val.label + '</span>' +
                '<span class="sizepicker-price">' + priceHTML + '</span>';
            btn.addEventListener('click', function () {
                spOptionsEl.querySelectorAll('.sizepicker-btn').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                spSize = val.label;
                updateSpTotal();
            });
            spOptionsEl.appendChild(btn);
        });

        spSize = 'default';
        updateSpTotal();
        spOverlay.classList.add('active');
        spPopup.classList.add('active');
    }

    function initSizePicker(menuItemsData) {
        spMenuItems = menuItemsData;

        // Create HTML
        var html =
            '<div class="sizepicker-overlay"></div>' +
            '<div class="sizepicker-popup">' +
                '<div class="sizepicker-header">' +
                    '<span class="sizepicker-title"></span>' +
                    '<button class="sizepicker-close"><i class="bi bi-x-lg"></i></button>' +
                '</div>' +
                '<div class="sizepicker-options"></div>' +
                '<div class="sizepicker-footer">' +
                    '<div class="sizepicker-qty">' +
                        '<button class="sizepicker-minus"><i class="bi bi-dash"></i></button>' +
                        '<span class="sizepicker-qty-val">1</span>' +
                        '<button class="sizepicker-plus"><i class="bi bi-plus"></i></button>' +
                    '</div>' +
                    '<button class="sizepicker-add">' +
                        '<span>Add to Cart</span>' +
                        '<span class="sizepicker-total"></span>' +
                    '</button>' +
                '</div>' +
            '</div>';
        document.body.insertAdjacentHTML('beforeend', html);

        // Cache DOM
        spOverlay = document.querySelector('.sizepicker-overlay');
        spPopup = document.querySelector('.sizepicker-popup');
        spTitle = document.querySelector('.sizepicker-title');
        spClose = document.querySelector('.sizepicker-close');
        spOptionsEl = document.querySelector('.sizepicker-options');
        spMinus = document.querySelector('.sizepicker-minus');
        spPlus = document.querySelector('.sizepicker-plus');
        spQtyVal = document.querySelector('.sizepicker-qty-val');
        spTotal = document.querySelector('.sizepicker-total');
        spAddBtn = document.querySelector('.sizepicker-add');

        // Events
        spClose.addEventListener('click', closeSizePicker);
        spOverlay.addEventListener('click', closeSizePicker);

        spMinus.addEventListener('click', function () {
            if (spQty > 1) { spQty--; spQtyVal.textContent = spQty; updateSpTotal(); }
        });
        spPlus.addEventListener('click', function () {
            if (spQty < 20) { spQty++; spQtyVal.textContent = spQty; updateSpTotal(); }
        });

        spAddBtn.addEventListener('click', function () {
            var price = getSpPrice();
            var item = spMenuItems[spIndex];
            var el = document.querySelector('[data-index="' + spIndex + '"]');
            var itemId = el ? (parseInt(el.dataset.itemId) || 0) : 0;

            // Get undiscounted MRP for cart discount calculation
            var val = spVariationData ? spVariationData.values.find(function (v) { return v.label === spSize; }) : null;
            var mrp = val ? parseFloat(val.optionPrice) : (item.mrp || item.price);

            if (window.menuCart) {
                window.menuCart.addToCart({
                    cartKey: spIndex + '-' + spSize,
                    index: spIndex,
                    itemId: itemId,
                    name: item.name,
                    price: price,
                    mrp: mrp,
                    img: item.img || '',
                    isVeg: item.isVeg || false,
                    size: spSize,
                    qty: spQty
                }, function () {
                    closeSizePicker();
                    if (spSourceBtn) {
                        var origHTML = spSourceBtn.innerHTML;
                        spSourceBtn.innerHTML = '<i class="bi bi-check"></i>';
                        setTimeout(function () { spSourceBtn.innerHTML = origHTML; }, 500);
                    }
                });
            }
        });
    }

    // Expose globally
    window.initSizePicker = initSizePicker;
    window.openSizePicker = openSizePicker;
    window.closeSizePicker = closeSizePicker;
})();
