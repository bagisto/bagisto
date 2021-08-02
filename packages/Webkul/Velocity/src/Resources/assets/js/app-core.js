/**
 * Main imports.
 */
import Vue from 'vue';
import axios from 'axios';

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

window.showAlert = (messageType, messageLabel, message) => {
    if (messageType && message !== '') {
        let alertId = Math.floor(Math.random() * 1000);

        let html = `<div class="alert ${messageType} alert-dismissible" id="${alertId}">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>${
                    messageLabel ? messageLabel + '!' : ''
                } </strong> ${message}.
        </div>`;

        $('#alert-container')
            .append(html)
            .ready(() => {
                window.setTimeout(() => {
                    $(`#alert-container #${alertId}`).remove();
                }, 5000);
            });
    }
};

/**
 * Helper functions.
 */
function isMobile() {
    if (
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i |
        /mobi/i.test(navigator.userAgent)
    ) {
        return true;
    }

    return false;
}

function loadDynamicScript(src, onScriptLoaded) {
    let dynamicScript = document.createElement('script');

    dynamicScript.setAttribute('src', src);

    document.body.appendChild(dynamicScript);

    dynamicScript.addEventListener('load', onScriptLoaded, false);
}

function removeTrailingSlash(site) {
    return site.replace(/\/$/, '');
}

/**
 * Dynamic loading for mobile.
 */
$(function() {
    /**
     * Base url.
     */
    let baseUrl = document.querySelector('meta[name="base-url"]').content;

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
         * Event for mobile to check the user interaction for homepage.
         */
        document.addEventListener(
            'touchstart',
            function dynamicScript() {
                loadDynamicScript(`${baseUrl}/${velocityJSPath}`, () => {
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
