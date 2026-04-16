// ===== COMMON CATEGORY POPUP JS =====
// Handles "View All" button click → opens category popup bottom sheet
// Works with all menu templates (style-1 through style-13+)

(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var viewAllBtn = document.getElementById('viewall-btn');
        var catPopupOverlay = document.getElementById('cat-popup-overlay');
        var catPopup = document.getElementById('cat-popup');
        var catPopupClose = document.getElementById('cat-popup-close');
        var catPopupItems = document.querySelectorAll('.cat-popup-item');

        if (!catPopup) return; // No popup in this template

        function openCatPopup() {
            if (catPopupOverlay) catPopupOverlay.classList.add('active');
            if (catPopup) catPopup.classList.add('active');
        }

        function closeCatPopup() {
            if (catPopupOverlay) catPopupOverlay.classList.remove('active');
            if (catPopup) catPopup.classList.remove('active');
        }

        // View All button click
        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openCatPopup();
            });
        }

        // Close popup
        if (catPopupOverlay) catPopupOverlay.addEventListener('click', closeCatPopup);
        if (catPopupClose) catPopupClose.addEventListener('click', closeCatPopup);

        // Popup category item click
        catPopupItems.forEach(function(item) {
            item.addEventListener('click', function() {
                var category = this.dataset.category;

                // Update nav active state (works with all button selectors)
                var navBtns = document.querySelectorAll('.cat-btn, .tab-btn, .pill, .s20-cat-item');
                navBtns.forEach(function(b) { b.classList.remove('active'); });
                var activeNav = document.querySelector(
                    '.cat-btn[data-category="' + category + '"], ' +
                    '.tab-btn[data-category="' + category + '"], ' +
                    '.pill[data-category="' + category + '"], ' +
                    '.s20-cat-item[data-category="' + category + '"]'
                );
                if (activeNav) activeNav.classList.add('active');

                // Update popup active state
                catPopupItems.forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');

                // Scroll to section
                var targetSection = document.getElementById(category);
                if (targetSection) {
                    var header = document.querySelector('.header, .sticky-header');
                    var nav = document.querySelector('.category-nav, .category-tabs');
                    var headerH = header ? header.offsetHeight : 0;
                    var navH = nav ? nav.offsetHeight : 0;
                    var targetPosition = targetSection.offsetTop - headerH - navH - 10;
                    window.scrollTo({ top: Math.max(0, targetPosition), behavior: 'smooth' });
                }

                closeCatPopup();
            });
        });
    });
})();
