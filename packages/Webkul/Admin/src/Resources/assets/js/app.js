/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);

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

        onInvalidSubmit({ values, errors, results }) {
            setTimeout(() => {
                const errorKeys = Object.entries(errors)
                    .map(([key, value]) => ({ key, value }))
                    .filter(error => error["value"].length);
        
                let firstErrorElement = document.querySelector('[name="' + errorKeys[0]["key"] + '"]');
        
                firstErrorElement.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
            }, 100);
        },
    },
});

/**
 * Global plugins registration.
 */
import Admin from "./plugins/admin";
import Axios from "./plugins/axios";
import CreateElement from "./plugins/createElement";
import Emitter from "./plugins/emitter";
import Flatpickr from "./plugins/flatpickr";
import VeeValidate from "./plugins/vee-validate";
import Draggable from "./plugins/draggable";

[
    Admin,
    Axios,
    CreateElement,
    Emitter,
    Flatpickr,
    VeeValidate,
    Draggable,
].forEach((plugin) => app.use(plugin));



/**
 * Global directives.
 */
import Slugify from "./directives/slugify";
import SlugifyTarget from "./directives/slugify-target";
import Debounce from "./directives/debounce";
import Code from "./directives/code";

app.directive("slugify", Slugify);
app.directive("slugify-target", SlugifyTarget);
app.directive("debounce", Debounce);
app.directive("code", Code);

/**
 * Load event, the purpose of using the event is to mount the application
 * after all of our `Vue` components which is present in blade file have
 * been registered in the app. No matter what `app.mount()` should be
 * called in the last.
 */
window.addEventListener("load", function (event) {
    app.mount("#app");
});
