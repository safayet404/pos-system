import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// CLI args
const args = process.argv.slice(2);
const typeArg = args.find(arg => arg.startsWith('--type='));
const name = args.find(arg => !arg.startsWith('--'));

if (!typeArg || !name) {
  console.log('❌ Usage: node scripts/create-file.js --type=component FileName');
  process.exit(1);
}

const type = typeArg.split('=')[1];

// Map file types to folders
const folders = {
  page: 'Pages',
  component: 'Components',
  layout: 'Layouts',
  store: 'Stores',
  util: 'Utility',
  composable: 'Composables'
};

const folderName = folders[type];

if (!folderName) {
  console.log(`❌ Unknown type: ${type}`);
  process.exit(1);
}

const ext = type === 'util' || type === 'composable' || type === 'store' ? 'js' : 'vue';
const fileName = `${name}.${ext}`;
const filePath = path.join(__dirname, `../resources/js/${folderName}`, fileName);

// Templates
const templates = {
  vue: `<script setup>

</script>

<template>
  <h1>This is ${name}</h1>
</template>

<style scoped>

</style>
`,
  js: `// ${name} utility
export default function ${name}() {
  // ...
}
`
};

const content = ext === 'vue' ? templates.vue : templates.js;

fs.writeFileSync(filePath, content);
console.log(`✅ Created ${type} -> ${fileName} in resources/js/${folderName}`);
