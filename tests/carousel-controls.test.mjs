import assert from 'node:assert/strict';
import { carouselPageCount, carouselPageIndex, nextCarouselIndex } from '../src/js/carousel.js';

assert.equal(nextCarouselIndex(0, 4, 1), 1, 'next moves forward');
assert.equal(nextCarouselIndex(3, 4, 1), 0, 'next wraps from last to first');
assert.equal(nextCarouselIndex(0, 4, -1), 3, 'previous wraps from first to last');
assert.equal(nextCarouselIndex(0, 0, 1), 0, 'empty carousels stay at zero');
assert.equal(carouselPageCount(6, 3), 2, 'six cards shown three at a time make two pages');
assert.equal(carouselPageCount(6, 1), 6, 'single-card mobile carousels count every card');
assert.equal(carouselPageCount(2, 3), 1, 'short carousels still have one page');
assert.equal(carouselPageIndex(5, 3, 2), 1, 'last visible group reports the last page');

console.log('carousel controls OK');
