import Vue from 'vue';
import VeeValidate from 'vee-validate';
import de from 'vee-validate/dist/locale/de';
import ar from 'vee-validate/dist/locale/ar';
import fa from 'vee-validate/dist/locale/fa';
import fr from 'vee-validate/dist/locale/fr';
import nl from 'vee-validate/dist/locale/nl';
import tr from 'vee-validate/dist/locale/tr';
import hi_IN from 'vee-validate/dist/locale/hi';
import zh_CN from 'vee-validate/dist/locale/zh_CN';
import axios from 'axios';

window.Vue = Vue;
window.VeeValidate = VeeValidate;
window.axios = axios;

require("./bootstrap");

Vue.use(VeeValidate, {
    dictionary: {
        ar: ar,
        de: de,
		fa: fa,
		fr: fr,
		nl: nl,
		tr: tr,
        hi_IN: hi_IN,
        zh_CN: zh_CN
    },
    events: 'input|change|blur',
});

Vue.prototype.$http = axios

window.eventBus = new Vue();

const app = new Vue({
    el: "#app",

    data: {
        modalIds: {},

        show_loader: false,
        baseUrl: document.querySelector('meta[name="base-url"]').content
    },

    mounted: function () {
        this.addServerErrors();

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
