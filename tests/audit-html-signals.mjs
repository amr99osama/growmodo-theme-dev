const pages = ['/', '/properties/', '/properties/seaside-serenity-villa/', '/contact/'];
const baseUrl = process.env.RE_TEST_BASE_URL || 'http://localhost/real-estate';

for (const path of pages) {
  const html = await (await fetch(baseUrl + path)).text();
  const imgs = [...html.matchAll(/<img\b[^>]*>/gi)].map((match) => match[0]);
  const meta = (name) =>
    new RegExp(`<meta\\s+(?:name|property)=["']${name}["'][^>]*content=["']([^"']+)`, 'i').exec(html)?.[1] || '';

  console.log(path, JSON.stringify({
    h1: (html.match(/<h1\b/gi) || []).length,
    images: imgs.length,
    imagesMissingAlt: imgs.filter((tag) => !/\salt=/i.test(tag)).length,
    imagesMissingDimensions: imgs.filter((tag) => !(/\swidth=/i.test(tag) && /\sheight=/i.test(tag))).length,
    metaDescriptionLength: meta('description').length,
    hasOgTitle: Boolean(meta('og:title')),
    twitterCard: meta('twitter:card'),
  }));
}
