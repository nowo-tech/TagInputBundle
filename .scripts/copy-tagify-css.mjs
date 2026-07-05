import { readFileSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const tagifyCss = readFileSync(join(root, 'node_modules/@yaireo/tagify/dist/tagify.css'), 'utf8');
const customCss = readFileSync(join(root, 'src/Resources/assets/src/tag-input.css'), 'utf8');

writeFileSync(join(root, 'src/Resources/public/tag-input.css'), `${tagifyCss}\n${customCss}`);
