import { defineConfig } from 'vite';

export default defineConfig(async () => {
    const laravel = (await import('laravel-vite-plugin')).default;

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
    };
});