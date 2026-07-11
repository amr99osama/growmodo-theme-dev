// Header: mobile menu toggle + sticky-on-scroll + body lock + promo dismiss.
// The open-state class lives on <body> (not <header>) so it reaches the
// off-canvas .mobile-menu, which is rendered as a sibling of the header.
export function initNav() {
  const header = document.querySelector('[data-site-header]');
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-mobile-menu]');
  const body = document.body;

  // Expose the header's rendered height so the mobile overlay can sit beneath it.
  const setHeaderHeight = () => {
    if (header) document.documentElement.style.setProperty('--re-header-h', `${header.offsetHeight}px`);
  };
  setHeaderHeight();
  window.addEventListener('resize', setHeaderHeight, { passive: true });

  const closeMenu = () => {
    body.classList.remove('is-menu-open', 'no-scroll');
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
  };

  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      const open = body.classList.toggle('is-menu-open');
      body.classList.toggle('no-scroll', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', closeMenu));
    menu.querySelectorAll('[data-menu-close]').forEach((b) => b.addEventListener('click', closeMenu));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMenu(); });
  }

  // Dismissible promo strip.
  const promoClose = document.querySelector('[data-promo-close]');
  if (promoClose && header) {
    promoClose.addEventListener('click', () => {
      header.classList.add('is-promo-dismissed');
      setHeaderHeight();
    });
  }

  if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }
}
