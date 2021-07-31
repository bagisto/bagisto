/**
 * Main imports.
 */
import Vue from 'vue';
import axios from 'axios';

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

window.loadDynamicScript = (src, onScriptLoaded) => {
    let baseUrl = document.querySelector('meta[name="base-url"]').content;

    let dynamicScript = document.createElement('script');
    dynamicScript.setAttribute('src', `${baseUrl}/${src}`);
    document.body.appendChild(dynamicScript);

    dynamicScript.addEventListener('load', onScriptLoaded, false);
};

/**
 * Vue prototype.
 */
Vue.prototype.$http = axios;

/**
 * Dynamic loading for mobile.
 */
$(function() {
    document.addEventListener(
        'touchstart',
        function dynamicScript() {
            loadDynamicScript(`themes/velocity/assets/js/velocity.js`, () => {
                this.removeEventListener('touchstart', dynamicScript);
            });
        },
        false
    );
});
