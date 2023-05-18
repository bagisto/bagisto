import { defineConfig } from "vite";
import dotenv from "dotenv";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

dotenv.config({ path: "../../../.env" });

export default defineConfig(({ mode }) => {
    return {
        server: {
            host: process.env.SHOP_VITE_HOST || "localhost",
            port: process.env.SHOP_VITE_PORT || 5173,
        },

        plugins: [
            laravel({
                hotFile: "../../../public/default-vite.hot",
                publicDirectory: "../../../public",
                buildDirectory: "themes/default/build",
                input: [
                    "src/Resources/assets/css/app.css",
                    "src/Resources/assets/js/app.js",
                ],
                refresh: true,
            }),
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
        ],
    };
});
