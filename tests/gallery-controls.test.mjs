import assert from 'node:assert/strict';
import { nextGalleryIndex, transitionGalleryImage } from '../src/js/gallery.js';

assert.equal(nextGalleryIndex(0, 4, 1), 1, 'next moves forward');
assert.equal(nextGalleryIndex(3, 4, 1), 0, 'next wraps from last to first');
assert.equal(nextGalleryIndex(0, 4, -1), 3, 'previous wraps from first to last');
assert.equal(nextGalleryIndex(0, 0, 1), 0, 'empty galleries stay at zero');

const classes = new Set();
const image = {
  src: 'before.jpg',
  classList: {
    add: (name) => classes.add(name),
    remove: (name) => classes.delete(name),
  },
};

const changed = await transitionGalleryImage(image, 'after.jpg', 0);
assert.equal(changed, true, 'transition reports a changed image');
assert.equal(image.src, 'after.jpg', 'transition updates the image source');
assert.equal(classes.has('is-transitioning'), false, 'transition class is removed after swap');

console.log('gallery controls OK');
