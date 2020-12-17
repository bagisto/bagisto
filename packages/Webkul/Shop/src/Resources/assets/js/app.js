import $ from 'jquery';
import Vue from 'vue';
import VeeValidate, { Validator } from 'vee-validate';
import de from 'vee-validate/dist/locale/de';
import ar from 'vee-validate/dist/locale/ar';
import axios from 'axios';
import VueSlider from 'vue-slider-component';
import accounting from 'accounting';
import ImageSlider from './components/image-slider';
import 'lazysizes';

window.jQuery = window.$ = $;
window.Vue = Vue;
window.VeeValidate = VeeValidate;
window.axios = axios;

require("./bootstrap");
require("ez-plus/src/jquery.ez-plus.js");

Vue.use(VeeValidate, {
    dictionary: {
        ar: ar,
        de: de,
    },
    events: 'input|change|blur',
});

Vue.prototype.$http = axios

window.eventBus = new Vue();

Vue.component('image-slider', ImageSlider);
Vue.component('vue-slider', VueSlider);
Vue.component('proceed-to-checkout', require('./components/checkout/proceed-to-checkout').default);

Vue.filter('currency', function (value, argument) {
    return accounting.formatMoney(value, argument);
})

$(document).ready(function () {
    const app = new Vue({
        el: "#app",

        data: {
            modalIds: {},

            show_loader: false
        },

        mounted: function () {
            this.addServerErrors();
            this.addFlashMessages();

            this.$validator.localize(document.documentElement.lang);
        },

        methods: {
            onSubmit: function (e) {
                this.toggleButtonDisable(true);

                if(typeof tinyMCE !== 'undefined')
                    tinyMCE.triggerSave();

                this.$validator.validateAll().then(result => {
                    if (result) {
                        e.target.submit();
                    } else {
                        this.toggleButtonDisable(false);

                        eventBus.$emit('onFormError')
                    }
                });
            },

            toggleButtonDisable (value) {
                var buttons = document.getElementsByTagName("button");

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = value;
                }
            },

            addServerErrors: function (scope = null) {
                for (var key in serverErrors) {
                    var inputNames = [];
                    key.split('.').forEach(function(chunk, index) {
                        if(index) {
                            inputNames.push('[' + chunk + ']')
                        } else {
                            inputNames.push(chunk)
                        }
                    })

                    var inputName = inputNames.join('');

                    const field = this.$validator.fields.find({
                        name: inputName,
                        scope: scope
                    });
                    if (field) {
                        this.$validator.errors.add({
                            id: field.id,
                            field: inputName,
                            msg: serverErrors[key][0],
                            scope: scope
                        });
                    }
                }
            },

            addFlashMessages: function () {
                const flashes = this.$refs.flashes;

                flashMessages.forEach(function (flash) {
                    flashes.addFlash(flash);
                }, this);
            },

            responsiveHeader: function () { },

            showModal(id) {
                this.$set(this.modalIds, id, true);
            },

            showLoader() {
                this.show_loader = true;
            },

            hideLoader() {
                this.show_loader = false;
            }
        }
    });

    window.app = app;
});