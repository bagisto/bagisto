import { h, resolveComponent } from "vue/dist/vue.esm-bundler";

export default {
    install(app) {
        /**
         * Create the virtual dom element
         */
        app.config.globalProperties.$h = h;

        /**
         * Resolve the component which is globally registered
         */
        app.config.globalProperties.$resolveComponent = resolveComponent;
    },
};
