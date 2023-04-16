import vue from '@vitejs/plugin-vue2'
import laravel from "laravel-vite-plugin";
import {defineConfig} from "vite";
import path from 'path';


export default defineConfig({
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directory as expected.
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: ['resources/assets/sass/app.scss', 'resources/assets/js/app.js'],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            vue: 'vue/dist/vue.esm.js',
        },
    },
})