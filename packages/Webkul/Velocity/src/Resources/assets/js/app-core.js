/**
 * Main imports.
 */
import Vue from 'vue';
import axios from 'axios';

/**
 * Helper functions.
 */
import {
    getBaseUrl,
    isMobile,
    loadDynamicScript,
    showAlert,
    removeTrailingSlash
} from './app-helpers';

/**
 * Vue prototype.
 */
Vue.prototype.$http = axios;

/**
 * Window assignation.
 */
window.Vue = Vue;

window.eventBus = new Vue();

window.axios = axios;

window.jQuery = window.$ = require('jquery');

window.BootstrapSass = require('bootstrap-sass');

window.getBaseUrl = getBaseUrl;

window.isMobile = isMobile;

window.loadDynamicScript = loadDynamicScript;

window.showAlert = showAlert;

/**
 * Dynamic loading for mobile.
 */
$(function() {
    /**
     * Base url.
     */
    let baseUrl = getBaseUrl();

    /**
     * Velocity JS path. Just make sure if you are renaming
     * file then update this path also for mobile.
     */
    let velocityJSPath = 'themes/velocity/assets/js/velocity.js';

    if (
        isMobile() &&
        removeTrailingSlash(baseUrl) ===
            removeTrailingSlash(window.location.href)
    ) {
        /**
         * Event for mobile to check the user interaction for the homepage. In mobile,
         * if your viewport is having dynamic content then, feel free to override this.
         * Else it is recommended to have some, static content in the viewport as the
         * first impression to reduce LCP.
         */
        document.addEventListener(
            'touchstart',
            function dynamicScript() {
                window.scrollTo(0, 0);

                document.body.style.overflow = 'hidden';

                loadDynamicScript(`${baseUrl}/${velocityJSPath}`, () => {
                    window.scrollTo(0, 0);
                    
                    document.body.style.overflow = '';

                    this.removeEventListener('touchstart', dynamicScript);
                });
            },
            false
        );
    } else {
        /**
         * Else leave it default as previous.
         */
        loadDynamicScript(`${baseUrl}/${velocityJSPath}`, () => {});
    }
});
