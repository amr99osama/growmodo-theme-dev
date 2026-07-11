import assert from 'node:assert/strict';

const baseUrl = process.env.RE_TEST_BASE_URL || 'http://localhost/real-estate';
const pages = [
  `${baseUrl}/`,
  `${baseUrl}/properties/`,
  `${baseUrl}/properties/seaside-serenity-villa/`,
  `${baseUrl}/contact/`,
];

function attr(html, selector) {
  const escaped = selector.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const match = html.match(new RegExp(`<meta\\s+(?:name|property)=["']${escaped}["'][^>]*content=["']([^"']+)["']`, 'i'));
  return match?.[1] || '';
}

for (const url of pages) {
  const response = await fetch(url);
  assert.equal(response.status, 200, `${url} should return 200`);

  const html = await response.text();
  assert.match(html, /<title>[^<]{10,}<\/title>/i, `${url} should have a meaningful title`);
  assert.match(html, /<link\s+rel=["']canonical["']\s+href=["'][^"']+["']/i, `${url} should have canonical URL`);
  assert.ok(attr(html, 'description').length >= 50, `${url} should have a meta description`);
  assert.ok(attr(html, 'og:title').length >= 10, `${url} should have og:title`);
  assert.ok(attr(html, 'og:description').length >= 50, `${url} should have og:description`);
  assert.ok(attr(html, 'og:url').startsWith(baseUrl), `${url} should have og:url`);
  assert.equal(attr(html, 'twitter:card'), 'summary_large_image', `${url} should have a Twitter card`);
}

console.log('site SEO smoke OK');
