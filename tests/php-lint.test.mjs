import assert from 'node:assert/strict';
import { spawnSync } from 'node:child_process';
import { readdirSync, statSync } from 'node:fs';
import { join } from 'node:path';

const php = process.env.PHP_BIN || 'C:\\xampp\\php\\php.exe';
const root = process.cwd();
const excluded = new Set(['node_modules', 'vendor', 'dist']);

function phpFiles(dir) {
  return readdirSync(dir).flatMap((entry) => {
    if (excluded.has(entry)) return [];
    const path = join(dir, entry);
    const stat = statSync(path);
    if (stat.isDirectory()) return phpFiles(path);
    return path.endsWith('.php') ? [path] : [];
  });
}

const files = phpFiles(root);
assert.ok(files.length > 10, 'theme should contain PHP files to lint');

const failures = [];
for (const file of files) {
  const result = spawnSync(php, ['-l', file], { encoding: 'utf8' });
  if (result.status !== 0) {
    failures.push(`${file}\n${result.stdout}${result.stderr}`);
  }
}

assert.deepEqual(failures, [], 'all PHP files should lint cleanly');
console.log(`PHP lint OK (${files.length} files)`);
