import assert from 'node:assert/strict';
import { readFileSync, statSync } from 'node:fs';
import { join } from 'node:path';
import zlib from 'node:zlib';

const baseUrl = process.env.RE_TEST_BASE_URL || 'http://localhost/real-estate';
const manifest = JSON.parse(readFileSync(join(process.cwd(), 'dist/.vite/manifest.json'), 'utf8'));
const entry = manifest['src/js/main.js'];

assert.ok(entry?.file, 'Vite manifest should include the main JS entry');
assert.ok(entry?.css?.length, 'Vite manifest should include CSS for the main entry');

const jsPath = join(process.cwd(), 'dist', entry.file);
const cssPath = join(process.cwd(), 'dist', entry.css[0]);
const js = readFileSync(jsPath);
const css = readFileSync(cssPath);

assert.ok(statSync(jsPath).size < 25 * 1024, 'main JS should stay below 25KB raw');
assert.ok(statSync(cssPath).size < 80 * 1024, 'main CSS should stay below 80KB raw');
assert.ok(zlib.gzipSync(js).length < 8 * 1024, 'main JS should stay below 8KB gzip');
assert.ok(zlib.gzipSync(css).length < 16 * 1024, 'main CSS should stay below 16KB gzip');

const response = await fetch(`${baseUrl}/`);
assert.equal(response.status, 200, 'home page should be reachable for performance checks');
const html = await response.text();

assert.match(html, /rel=["']preload["'][^>]+as=["']font["']/i, 'home should preload the primary font');
assert.match(html, /fetchpriority=["']high["']/i, 'home hero image should use high fetch priority');
assert.match(html, /decoding=["']async["']/i, 'images should use async decoding');
assert.match(html, /loading=["']lazy["']/i, 'below-fold images should use lazy loading');

console.log('performance budget OK');
