export function nextGalleryIndex(current, total, delta) {
  if (!total) return 0;
  return (current + delta + total) % total;
}

export function transitionGalleryImage(image, src, duration = 180) {
  if (!image || !src || image.src === src) return Promise.resolve(false);

  image.classList.add('is-transitioning');

  return new Promise((resolve) => {
    setTimeout(() => {
      image.src = src;
      image.classList.remove('is-transitioning');
      resolve(true);
    }, duration);
  });
}

// Single-property gallery: thumbnails, dots, and arrows keep the two-image stage in sync.
export function initGallery() {
  document.querySelectorAll('[data-gallery]').forEach((gallery) => {
    const main = gallery.querySelector('[data-gallery-main]');
    const side = gallery.querySelector('[data-gallery-side]');
    const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
    const dots = Array.from(gallery.querySelectorAll('[data-gallery-dot]'));
    const prev = gallery.querySelector('[data-gallery-prev]');
    const next = gallery.querySelector('[data-gallery-next]');
    if (!main || !thumbs.length) return;

    let active = 0;

    const setActive = (index) => {
      active = nextGalleryIndex(index, thumbs.length, 0);
      const thumb = thumbs[active];
      const src = thumb?.getAttribute('data-gallery-thumb');
      if (!src) return;

      transitionGalleryImage(main, src);

      if (side) {
        const sideThumb = thumbs[nextGalleryIndex(active, thumbs.length, 1)];
        const sideSrc = sideThumb?.getAttribute('data-gallery-thumb');
        if (sideSrc) transitionGalleryImage(side, sideSrc);
      }

      thumbs.forEach((item, itemIndex) => {
        item.classList.toggle('is-active', itemIndex === active);
        item.setAttribute('aria-pressed', itemIndex === active ? 'true' : 'false');
      });

      dots.forEach((dot, dotIndex) => {
        dot.classList.toggle('is-active', dotIndex === active);
        dot.setAttribute('aria-current', dotIndex === active ? 'true' : 'false');
      });
    };

    thumbs.forEach((thumb, index) => {
      thumb.addEventListener('click', () => setActive(index));
    });

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => setActive(index));
    });

    if (prev) prev.addEventListener('click', () => setActive(nextGalleryIndex(active, thumbs.length, -1)));
    if (next) next.addEventListener('click', () => setActive(nextGalleryIndex(active, thumbs.length, 1)));

    setActive(0);
  });
}
