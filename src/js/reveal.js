// Scroll-reveal (block-level containers) + count-up for [data-countup].
// Progressive enhancement: without JS everything is visible.

const REVEAL_SELECTORS = [
  '.section-head',
  '.hero__content',
  '.about__inner',
  '.stats__grid',
  '.cta__panel',
  '.grid',
  '.post-grid',
  '.testimonials__track',
];

function animateCount(el) {
  const raw = el.textContent.trim();
  const match = raw.match(/^(\D*)([\d.,]+)(.*)$/);
  if (!match) return;
  const [, prefix, numStr, suffix] = match;
  const target = parseFloat(numStr.replace(/,/g, ''));
  if (!isFinite(target)) return;

  const hasComma = numStr.includes(',');
  const duration = 1400;
  const start = performance.now();

  const tick = (now) => {
    const p = Math.min((now - start) / duration, 1);
    const eased = 1 - Math.pow(1 - p, 3);
    const val = Math.round(target * eased);
    el.textContent = prefix + (hasComma ? val.toLocaleString() : val) + suffix;
    if (p < 1) requestAnimationFrame(tick);
  };
  requestAnimationFrame(tick);
}

export function initReveal() {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Count-up.
  const counters = document.querySelectorAll('[data-countup]');
  if (counters.length) {
    if (prefersReduced || !('IntersectionObserver' in window)) {
      // leave numbers as-is
    } else {
      const co = new IntersectionObserver(
        (entries, obs) => {
          entries.forEach((e) => {
            if (e.isIntersecting) {
              animateCount(e.target);
              obs.unobserve(e.target);
            }
          });
        },
        { threshold: 0.6 }
      );
      counters.forEach((c) => co.observe(c));
    }
  }

  // Reveal.
  const targets = document.querySelectorAll(REVEAL_SELECTORS.join(','));
  if (!targets.length || prefersReduced || !('IntersectionObserver' in window)) return;

  targets.forEach((t) => t.classList.add('re-reveal'));
  const ro = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          e.target.classList.add('is-visible');
          obs.unobserve(e.target);
        }
      });
    },
    { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
  );
  targets.forEach((t) => ro.observe(t));
}
