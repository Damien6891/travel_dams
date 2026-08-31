(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {

        const summary = document.querySelector('.carnet-sommaire__container')

        if (!summary) {
            return
        }


        // Close summary when click on a day
        summary.addEventListener('click', (e) => {
            const link = e.target.closest('a')

            if (link) {
                summary.removeAttribute('open')
            }

        })

        // Close summary on click outside
        document.addEventListener('click', (e) => {
            if (summary.hasAttribute('open') && !summary.contains(e.target)) {
                summary.removeAttribute('open')
            }
        })

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && summary.hasAttribute('open')) {
                summary.removeAttribute('open')
            }
        })
    })


})();