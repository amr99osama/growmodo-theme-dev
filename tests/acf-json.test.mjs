import assert from 'node:assert/strict';
import { readFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

const acfDir = join(process.cwd(), 'acf-json');
const files = readdirSync(acfDir).filter((file) => file.endsWith('.json'));

assert.ok(files.length >= 5, 'ACF local JSON groups should be present');

for (const file of files) {
  const group = JSON.parse(readFileSync(join(acfDir, file), 'utf8'));
  assert.ok(group.key?.startsWith('group_'), `${file} should have a group key`);
  assert.ok(group.title, `${file} should have a title`);
  assert.ok(Array.isArray(group.fields), `${file} should have fields`);
  assert.ok(group.fields.length > 0, `${file} should not be empty`);
}

console.log(`ACF JSON OK (${files.length} groups)`);
