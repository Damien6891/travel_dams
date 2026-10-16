(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {

        const summary = document.querySelector('.summary__container')
        const container = document.querySelector('.summary__container')
        // const listItems = container.querySelector('.summary__list').querySelectorAll('>li')
        const currentEl = document.querySelector('.summary__current')
        const h2s = document.querySelectorAll('h2')
        const h3s = document.querySelectorAll('h3')
        const hasChildren = document.querySelector('[data-has-children]')
        // console.log(hasChildren);
        let items

        if (!container) {
            return
        }

        if (!hasChildren) {
            items = document.querySelectorAll('h2')
        } else {
            items = document.querySelectorAll('h2, h3')
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
            const item = link ? link.closest('.summary__item') : null

            if (activeItem) {
                activeItem.classList.remove('summary__item--active')
            }

            if (item) {
                item.classList.add('summary__item--active')
            }

            activeItem = item
        }

        if (currentEl && items.length) {
            const firstDay = items[0]
            console.log(items);


            const observer = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {

                        if (entry.isIntersecting) {
                            console.log(entry.target);

                            currentEl.textContent = entry.target.textContent || ''
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

            items.forEach(function (item) {
                observer.observe(item)
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