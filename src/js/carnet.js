(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {

        const summary = document.querySelector('.carnet-sommaire__container')
        const container = document.querySelector('.carnet-sommaire__container')
        const currentEl = document.querySelector('.carnet-sommaire__current')
        const days = document.querySelectorAll('.carnet-day')

        if (!container) {
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

        // Close summary on escape keydown
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && summary.hasAttribute('open')) {
                summary.removeAttribute('open')
            }
        })

        let activeItem = null

        function setActive(link) {
            const item = link ? link.closest('.carnet-sommaire__item') : null

            if (activeItem) {
                activeItem.classList.remove('carnet-sommaire__item--active')
            }

            if (item) {
                item.classList.add('carnet-sommaire__item--active')
            }

            activeItem = item
        }

        if (currentEl && days.length) {
            const firstDay = days[0]

            const observer = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            currentEl.textContent = entry.target.dataset.title || ''
                            const link = summary.querySelector('a[href="#' + entry.target.id + '"]')
                            setActive(link)
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
                            setActive(null)
                        }
                        ticking = false
                    })
                    ticking = true
                }
            })

            // Scroll auto in summary on active day
            container.addEventListener('toggle', function () {
                if (container.open && activeItem) {
                    activeItem.scrollIntoView({ block: 'nearest', bheavior: 'auto' })
                }
            })
        }
    })


})();