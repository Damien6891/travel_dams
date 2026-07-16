(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initHeaderHeightVar();
        initHeaderScrollState();
    });

    function initHeaderHeightVar() {
        console.log('here i am');

        const header = document.getElementById('masthead');
        if (!header) return;

        function setHeaderHeightVar() {
            document.documentElement.style.setProperty('--header-height', header.offsetHeight + 'px');
        }

        setHeaderHeightVar();

        if ('ResizeObserver' in window) {
            new ResizeObserver(setHeaderHeightVar).observe(header);
        } else {
            window.addEventListener('resize', setHeaderHeightVar);
        }
    }

    function initHeaderScrollState() {
        const header = document.getElementById('masthead');
        const sentinel = document.getElementById('hero-sentinel');

        if (!header) return;

        // Pas de hero sur cette page : header solide dès le départ.
        if (!sentinel || !('IntersectionObserver' in window)) {
            header.classList.add('site-header--solid');
            return;
        }

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                header.classList.toggle('site-header--solid', !entry.isIntersecting);
            });
        }, {
            rootMargin: '-' + header.offsetHeight + 'px 0px 0px 0px',
        });

        observer.observe(sentinel);
    }
})();