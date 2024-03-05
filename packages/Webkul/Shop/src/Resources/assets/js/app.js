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
import VeeValidate from "./plugins/vee-validate";
import Flatpickr from "./plugins/flatpickr";

[
    Axios,
    Emitter, 
    Shop, 
    VeeValidate, 
    Flatpickr,
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
