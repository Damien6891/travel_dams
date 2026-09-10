(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    scrollToTop()

  });

  const scrollToTop = () => {
    const scrollToTopBtn = document.querySelector('.scroll-to-top')

    if (!scrollToTopBtn) return

    scrollToTopBtn.addEventListener('click', () => {
      window.scrollTo(0, 0)
    })

  }

})();