(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initMobileToggle();
        initMegaMenu();
    });

    function initMobileToggle() {
        const nav = document.getElementById('site-navigation');
        if (!nav) return;

        const toggle = nav.querySelector('.menu-toggle');
        const container = document.getElementById('primary-menu-container');
        const backdrop = nav.querySelector('.mobile-nav-overlay-backdrop');

        if (!toggle || !container) return;

        function openMenu() {
            container.classList.add('is-open');
            if (backdrop) backdrop.classList.add('is-visible');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.classList.add('mobile-nav-is-open');
        }

        function closeMenu() {
            container.classList.remove('is-open');
            if (backdrop) backdrop.classList.remove('is-visible');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('mobile-nav-is-open');
        }

        toggle.addEventListener('click', function () {
            container.classList.contains('is-open') ? closeMenu() : openMenu();
        });

        backdrop && backdrop.addEventListener('click', closeMenu);

        document.addEventListener('keydown', function (e) {
            if ('Escape' === e.key && container.classList.contains('is-open')) {
                closeMenu();
                toggle.focus();
            }
        });
    }

    function initMegaMenu() {
        const items = document.querySelectorAll('.has-megamenu');

        function isDesktop() {
            return window.matchMedia('(min-width: 992px)').matches;
            // return window.matchMedia('(min-width: 900px)').matches;
        }

        items.forEach(function (item) {
            const trigger = item.querySelector(':scope > .mega-menu-trigger');
            const panel = item.querySelector(':scope > .mega-menu');
            let hoverTimeout;

            if (!trigger || !panel) return;

            function openMega() {
                item.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }

            function closeMega() {
                item.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            }

            item.addEventListener('mouseenter', function () {
                if (!isDesktop()) return;
                clearTimeout(hoverTimeout);
                openMega();
            });

            item.addEventListener('mouseleave', function () {
                if (!isDesktop()) return;
                hoverTimeout = setTimeout(closeMega, 150);
            });

            trigger.addEventListener('click', function () {
                item.classList.contains('is-open') ? closeMega() : openMega();
            });

            document.addEventListener('click', function (e) {
                if (!item.contains(e.target)) closeMega();
            });

            item.addEventListener('keydown', function (e) {
                if ('Escape' === e.key) {
                    closeMega();
                    trigger.focus();
                }
            });
        });
    }
})();