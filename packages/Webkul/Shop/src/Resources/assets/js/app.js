/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Main vue bundler.
 */
import { createApp } from "vue/dist/vue.esm-bundler";

/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { localize } from "@vee-validate/i18n";
import en from "@vee-validate/i18n/dist/locale/en.json";
import AllRules from "@vee-validate/rules";

configure({
    generateMessage: localize({
        en,
    }),
    validateOnBlur: true,
    validateOnInput: true,
    validateOnChange: true,
});

Object.keys(AllRules).forEach((rule) => {
    defineRule(rule, AllRules[rule]);
});

/**
 * Main root application registry.
 */
window.app = createApp({
    components: {
        VForm: Form,
        VField: Field,
        VErrorMessage: ErrorMessage,
    },

    data() {
        return {};
    },

    methods: {
        onSubmit() {},

        onInvalidSubmit() {},
    },
});

/**
 * Global properties registration.
 */
app.config.globalProperties.$axios = axios;

/**
 * Load event, the purpose of using the event is to mount the application
 * after all of our `Vue` components which is present in blade file have
 * been registered in the app. No matter what `app.mount()` should be
 * called in the last.
 */
window.addEventListener('load', function(event) {
    app.mount("#app");
});
