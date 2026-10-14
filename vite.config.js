import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { nodePolyfills } from 'vite-plugin-node-polyfills';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/wallet/app.js'],
      refresh: true,
    }),
    nodePolyfills(),
  ],
  resolve: {
    alias: {
      process: "process/browser",
      buffer: "buffer",
    },
  },
});
