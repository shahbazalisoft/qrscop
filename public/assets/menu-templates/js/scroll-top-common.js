(function(){
    var btn = document.getElementById('scrollTopBtn');
    if (!btn) return;

    var bottomNav = document.querySelector('.s14-bottom-nav')
        || document.querySelector('.bottom-nav')
        || document.querySelector('[class*="bottom-nav"]');

    window.addEventListener('scroll', function(){
        if (bottomNav && bottomNav.classList.contains('hidden')) {
            btn.classList.add('visible');
        } else if (!bottomNav && window.scrollY > 300) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    });

    btn.addEventListener('click', function(){
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();
