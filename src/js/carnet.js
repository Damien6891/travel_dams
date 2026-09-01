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

        const currentEl = document.querySelector('.carnet-sommaire__current')
        const days = document.querySelectorAll('.carnet-day')

        if (currentEl && days.length) {
            const firstDay = days[0]

            const observer = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            currentEl.textContent = entry.target.dataset.title || ''
                        }
                    })
                },
                {
                    rootMargin: '-10% 0px -85% 0px',
                    threshold: 0
                }
            )

            days.forEach(function (day) {
                observer.observe(day)
            })

            // empty span when pass scroll first element top
            let ticking = false

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(function () {
                        if (firstDay.getBoundingClientRect().top > window.innerHeight * 0.2) {
                            currentEl.textContent = ''
                        }
                        ticking = false
                    })
                    ticking = true
                }
            })
        }
    })


})();