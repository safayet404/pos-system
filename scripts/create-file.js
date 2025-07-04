import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Required to resolve __dirname in ES module
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Parse CLI args
const args = process.argv.slice(2);
const typeArg = args.find(arg => arg.startsWith('--type='));
const rawName = args.find(arg => !arg.startsWith('--'));

if (!typeArg || !rawName) {
  console.log('❌ Usage: node scripts/create-file.js --type=component Folder/FileName');
  process.exit(1);
}

const type = typeArg.split('=')[1];

const folders = {
  page: 'Pages',
  component: 'Components',
  layout: 'Layouts',
  store: 'Stores',
  util: 'Utils',
  composable: 'Composables',
  api: 'APIRequest'
};

const folderName = folders[type];

if (!folderName) {
  console.log(`❌ Unknown type: ${type}`);
  process.exit(1);
}

// Parse folder + filename
const parsedPath = path.parse(rawName);
const name = parsedPath.name;
const subFolder = parsedPath.dir;

const ext = ['util', 'composable', 'store', 'api'].includes(type) ? 'js' : 'vue';

// Construct paths
const fileDir = path.join(__dirname, `../resources/js/${folderName}`, subFolder);
const filePath = path.join(fileDir, `${name}.${ext}`);

// Create folder if needed
if (!fs.existsSync(fileDir)) {
  fs.mkdirSync(fileDir, { recursive: true });
}

// File templates
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
`,
  empty: ''
};

// Determine content
const content =
  type === 'api' ? templates.empty :
    ext === 'vue' ? templates.vue :
      templates.js;

// Write the file
fs.writeFileSync(filePath, content);
console.log(`✅ Created ${type} → ${rawName}.${ext} in resources/js/${folderName}`);
