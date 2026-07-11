// Theme entry. Vite bundles the SCSS import into a hashed CSS file and this
// JS into a hashed module.
import '../scss/main.scss';

import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initGallery } from './gallery.js';
import { initFilters } from './filters.js';
import { initCarousels } from './carousel.js';

const onReady = (fn) =>
  document.readyState !== 'loading'
    ? fn()
    : document.addEventListener('DOMContentLoaded', fn);

onReady(() => {
  initNav();
  initReveal();
  initGallery();
  initFilters();
  initCarousels();
});
