import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',

        'resources/js/api/auth.js',
        'resources/js/api/customers.js',
        'resources/js/api/dashboard.js',
        'resources/js/api/order-details.js',
        'resources/js/api/orders.js',
        'resources/js/api/payments.js',
        'resources/js/api/report.js',
        'resources/js/api/services.js',
        'resources/js/api/users.js',

        'resources/js/components/modal.js',
        'resources/js/components/sidebar.js',

        'resources/js/bootstrap.js',
        'resources/js/check-auth.js',
      ],
      refresh: true,
    }),
    tailwindcss(),
  ],
  resolve: {
    alias: {
      '@js': path.resolve(__dirname, 'resources/js'),
    },
  },
});