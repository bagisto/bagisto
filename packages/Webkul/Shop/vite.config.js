import { defineConfig } from "vite";
import dotenv from "dotenv";
import dotenvExpand from 'dotenv-expand';
import laravel from "laravel-vite-plugin";

const config = dotenv.config({ path: "../../../.env" });
dotenvExpand(config);

export default defineConfig(({ mode }) => {
    return {
        build: {
            emptyOutDir: true,
        },

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
        ],
    };
});
