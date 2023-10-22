/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);

/**
 * Main vue bundler.
 */
import { createApp } from "vue/dist/vue.esm-bundler";

/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule } from "vee-validate";
import { localize } from "@vee-validate/i18n";
import en from "@vee-validate/i18n/dist/locale/en.json";
import * as AllRules from '@vee-validate/rules';

/**
 * Registration of all global validators.
 */
Object.keys(AllRules).forEach(rule => {
    defineRule(rule, AllRules[rule]);
});


defineRule("", () => true);

configure({
    /**
     * Built-in error messages and custom error messages are available. Multiple
     * locales can be added in the same way.
     */
    generateMessage: localize({
        en: {
            ...en,
            messages: {
                ...en.messages,
                phone: "This {field} must be a valid phone number",
            },
        },
    }),

    validateOnBlur: true,
    validateOnInput: true,
    validateOnChange: true,
});

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
[
    Axios,
].forEach((plugin) => app.use(plugin));


/**
 * Global components registration;
 */
import { Field, Form, ErrorMessage } from "vee-validate";

app.component("VForm", Form);
app.component("VField", Field);
app.component("VErrorMessage", ErrorMessage);


/**
 * Load event, the purpose of using the event is to mount the application
 * after all of our `Vue` components which is present in blade file have
 * been registered in the app. No matter what `app.mount()` should be
 * called in the last.
 */
window.addEventListener("load", function (event) {
    app.mount("#app");
});
