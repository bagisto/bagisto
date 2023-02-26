/**
 * Main imports.
 */
import Vue    from 'vue';
import axios  from 'axios';

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

// TODO once every package is migrated to laravel-mix 6, this can be removed safely (jquery will be injected when needed)
window.jQuery = window.$ = require('jquery');

require('./dropdown.js');

window.BootstrapSass = require('bootstrap-sass');

window.lazySize = require('lazysizes');

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

    loadDynamicScript(`${baseUrl}/${velocityJSPath}`, () => {});
});
