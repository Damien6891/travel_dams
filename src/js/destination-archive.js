(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.querySelector('.view-toggle');
        var grid = document.querySelector('[data-view-target="grid"]');

        if (!toggle || !grid) return;

        var buttons = toggle.querySelectorAll('.icon-btn');

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var view = button.getAttribute('data-view');

                buttons.forEach(function (btn) {
                    var active = btn === button;
                    btn.classList.toggle('is-active', active);
                    btn.setAttribute('aria-pressed', active ? 'true' : 'false');
                });

                grid.setAttribute('data-view', view);
            });
        });
    });
})();
