/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/installer/**", "../fonts/**"]);

/**
 * Main vue bundler.
 */
import { createApp } from "vue/dist/vue.esm-bundler";

/**
 * Main root application registry.
 */
window.app = createApp({
    data() {
        return {};
    },

    methods: {
        onSubmit() {},

        onInvalidSubmit() {},
    },
});

/**
 * Global plugins registration.
 */
import Axios from "./plugins/axios";
import VeeValidate from "./plugins/vee-validate";

[
    Axios,
    VeeValidate
].forEach((plugin) => app.use(plugin));

/**
 * Load event, the purpose of using the event is to mount the application
 * after all of our `Vue` components which is present in blade file have
 * been registered in the app. No matter what `app.mount()` should be
 * called in the last.
 */
window.addEventListener("load", function (event) {
    app.mount("#app");
});
