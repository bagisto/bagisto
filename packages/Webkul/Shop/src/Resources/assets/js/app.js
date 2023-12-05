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
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { localize } from "@vee-validate/i18n";
import en from "@vee-validate/i18n/dist/locale/en.json";
import * as AllRules from '@vee-validate/rules';

/**
 * Registration of all global validators.
 */
Object.keys(AllRules).forEach(rule => {
    defineRule(rule, AllRules[rule]);
});

/**
 * This regular expression allows phone numbers with the following conditions:
 * - The phone number can start with an optional "+" sign.
 * - After the "+" sign, there should be one or more digits.
 *
 * This validation is sufficient for global-level phone number validation. If
 * someone wants to customize it, they can override this rule.
 */
defineRule("phone", (value) => {
    if (! value || ! value.length) {
        return true;
    }

    if (! /^\+?\d+$/.test(value)) {
        return false;
    }

    return true;
});

defineRule("address", (value) => {
    if (! value || ! value.length) {
        return true;
    }

    if (! /^[a-zA-Z0-9\s'\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\u0590-\u05FF\u3040-\u309F\u30A0-\u30FF\u0400-\u04FF\u0D80-\u0DFF\u3400-\u4DBF\u2000-\u2A6D\u00C0-\u017F\u0980-\u09FF\u0900-\u097F\u4E00-\u9FFF,\(\)-]{1,60}$/iu.test(value)) {
        return false;
    }

    return true;
});

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

        en: {
            ...en,
            messages: {
                ...en.messages,
                address: "This {field} must be a valid address",
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

    mounted() {
        this.lazyImages();

        this.animateBoxes();
    },

    methods: {
        onSubmit() {},

        onInvalidSubmit() {},

        lazyImages() {
            var lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));

            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
    
                        lazyImage.src = lazyImage.dataset.src;
                        
                        lazyImage.classList.remove('lazy');
    
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });
    
            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        },

        animateBoxes() {
            let animateBoxes = document.querySelectorAll('.scroll-trigger');

            if (! animateBoxes.length) {
                return;
            }

            animateBoxes.forEach((animateBox) => {
                let animateBoxObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            animateBox.classList.remove('scroll-trigger--offscreen');

                            animateBoxObserver.unobserve(animateBox);
                        }
                    });
                });
        
                animateBoxObserver.observe(animateBox);
            });
        }
    },
});

/**
 * Global plugins registration.
 */
import Axios from "./plugins/axios";
import Emitter from "./plugins/emitter";
import Shop from "./plugins/shop";

[Axios, Emitter, Shop].forEach((plugin) => app.use(plugin));

import Flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.css';
window.Flatpickr = Flatpickr;

/**
 * Global components registration;
 */
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
