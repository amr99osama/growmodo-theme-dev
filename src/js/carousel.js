// Card carousels (Featured / Testimonials / FAQ): pager buttons scroll the track,
// and the "NN of NN" counter reflects the real number of cards and updates live.
export function nextCarouselIndex(current, total, delta) {
  if (!total) return 0;
  return (current + delta + total) % total;
}

export function carouselPageCount(total, perPage) {
  if (!total) return 0;
  return Math.ceil(total / Math.max(1, perPage));
}

export function carouselPageIndex(itemIndex, perPage, pageCount) {
  if (!pageCount) return 0;
  return Math.min(pageCount - 1, Math.max(0, Math.floor(itemIndex / Math.max(1, perPage))));
}

export function initCarousels() {
  document.querySelectorAll('[data-carousel]').forEach((track) => {
    const scope = track.closest('section') || document;
    const prev = scope.querySelector('[data-carousel-prev]');
    const next = scope.querySelector('[data-carousel-next]');
    const curEl = scope.querySelector('[data-pager-current]');
    const totEl = scope.querySelector('[data-pager-total]');
    const dotsWrap = scope.querySelector('[data-carousel-dots]') || scope.querySelector('.property-faq__dots');
    let dots = Array.from(scope.querySelectorAll('[data-carousel-dot]'));

    const pad = (n) => String(n).padStart(2, '0');
    const total = track.children.length;
    if (!total) return;

    const step = () => {
      const card = track.firstElementChild;
      const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      return card ? card.getBoundingClientRect().width + gap : track.clientWidth;
    };

    const perPage = () => {
      const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      const itemStep = step();
      if (!itemStep) return 1;
      return Math.max(1, Math.min(total, Math.round((track.clientWidth + gap) / itemStep)));
    };

    const pages = () => carouselPageCount(total, perPage());

    const currentIndex = () => {
      if (track.scrollWidth <= track.clientWidth + 2) return 0;
      if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 2) return total - 1;
      return Math.min(total - 1, Math.max(0, Math.round(track.scrollLeft / step())));
    };

    const currentPage = () => carouselPageIndex(currentIndex(), perPage(), pages());

    const buildDots = () => {
      if (!dotsWrap) return;
      const pageTotal = pages();
      if (dots.length === pageTotal) return;
      dotsWrap.textContent = '';
      for (let index = 0; index < pageTotal; index += 1) {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.setAttribute('data-carousel-dot', String(index));
        dot.setAttribute('aria-label', `Show page ${index + 1}`);
        dot.addEventListener('click', () => scrollToPage(index));
        dotsWrap.appendChild(dot);
      }
      dots = Array.from(dotsWrap.querySelectorAll('[data-carousel-dot]'));
    };

    const scrollToPage = (page) => {
      const pageTotal = pages();
      const targetPage = nextCarouselIndex(page, pageTotal, 0);
      const maxScroll = Math.max(0, track.scrollWidth - track.clientWidth);
      track.scrollTo({ left: Math.min(maxScroll, step() * perPage() * targetPage), behavior: 'smooth' });
    };

    const update = () => {
      buildDots();
      const page = currentPage();
      const pageTotal = pages();
      if (curEl) curEl.textContent = pad(Math.min(pageTotal, page + 1));
      if (totEl) totEl.textContent = pad(pageTotal);
      dots.forEach((dot, dotIndex) => {
        dot.classList.toggle('is-active', dotIndex === page);
        dot.setAttribute('aria-current', dotIndex === page ? 'true' : 'false');
      });
    };

    if (prev) prev.addEventListener('click', () => scrollToPage(nextCarouselIndex(currentPage(), pages(), -1)));
    if (next) next.addEventListener('click', () => scrollToPage(nextCarouselIndex(currentPage(), pages(), 1)));
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => scrollToPage(index));
    });

    let ticking = false;
    track.addEventListener('scroll', () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => { update(); ticking = false; });
    }, { passive: true });
    window.addEventListener('resize', update, { passive: true });
    update();
  });
}
