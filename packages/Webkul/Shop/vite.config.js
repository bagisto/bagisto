import { defineConfig } from "vite";
import dotenv from "dotenv";
import laravel from "laravel-vite-plugin";

dotenv.config({ path: "../../../.env" });

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
            // {
            //     name: "process-css-url",
            //     transform(code, id) {
            //         if (id.endsWith(".css")) {

            //         }

            //         return code;
            //     },
            // },
        ],
    };
});
