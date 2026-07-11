import assert from 'node:assert/strict';

const baseUrl = process.env.RE_TEST_BASE_URL || 'http://localhost/real-estate';
const pages = [
  `${baseUrl}/`,
  `${baseUrl}/properties/`,
  `${baseUrl}/properties/seaside-serenity-villa/`,
  `${baseUrl}/contact/`,
];

function imageTags(html) {
  return html.match(/<img\b[^>]*>/gi) || [];
}

for (const url of pages) {
  const response = await fetch(url);
  assert.equal(response.status, 200, `${url} should return 200`);

  const html = await response.text();
  assert.match(html, /<main\b[^>]*id=["']main["']/i, `${url} should expose #main`);
  assert.match(html, /<h1\b/i, `${url} should have an H1`);
  assert.match(html, /class=["'][^"']*skip-link/i, `${url} should have a skip link`);
  assert.doesNotMatch(html, /Fatal error|Warning:|Notice:/i, `${url} should not render PHP errors`);

  for (const tag of imageTags(html)) {
    assert.match(tag, /\salt=/i, `${url} image should include alt text: ${tag}`);
    assert.match(tag, /\swidth=/i, `${url} image should include width for CLS prevention: ${tag}`);
    assert.match(tag, /\sheight=/i, `${url} image should include height for CLS prevention: ${tag}`);
  }
}

console.log('site smoke OK');
