import { defineConfig } from 'vite';

// Assets are built to dist/ with a manifest; inc/enqueue.php reads
// dist/.vite/manifest.json and enqueues the hashed files.
// `base` must match the theme's public dist/ URL so asset references resolve.
export default defineConfig({
  base: '/real-estate/wp-content/themes/realestate/dist/',
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: 'src/js/main.js',
    },
  },
  server: {
    host: '127.0.0.1',
    port: 5173,
    strictPort: true,
  },
});
