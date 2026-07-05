import { defineConfig } from 'vite';

export default defineConfig({
  define: {
    __TAG_INPUT_BUILD_TIME__: JSON.stringify(new Date().toISOString()),
  },
  build: {
    outDir: 'src/Resources/public',
    emptyOutDir: false,
    rollupOptions: {
      input: 'src/Resources/assets/src/tag-input.ts',
      output: {
        format: 'iife',
        entryFileNames: 'tag-input.js',
        assetFileNames: 'tag-input.[ext]',
      },
    },
    minify: true,
    sourcemap: false,
  },
});
