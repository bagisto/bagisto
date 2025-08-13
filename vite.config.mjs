import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Shop theme assets
                'packages/Webkul/Shop/src/Resources/assets/css/app.css',
                'packages/Webkul/Shop/src/Resources/assets/js/app.js',

                // Admin theme assets
                'packages/Webkul/Admin/src/Resources/assets/css/app.css',
                'packages/Webkul/Admin/src/Resources/assets/js/app.js',
            ],
            refresh: true,
        }),
    ],
});