/**
 * Main imports.
 */
import Vue from 'vue';
import VeeValidate from 'vee-validate';
import './bootstrap';

/**
 * Lang imports.
 */
import de from 'vee-validate/dist/locale/de';
import ar from 'vee-validate/dist/locale/ar';
import fa from 'vee-validate/dist/locale/fa';
import fr from 'vee-validate/dist/locale/fr';
import nl from 'vee-validate/dist/locale/nl';
import tr from 'vee-validate/dist/locale/tr';

/**
 * Vue plugins.
 */
Vue.use(VeeValidate, {
    dictionary: {
        ar: ar,
        de: de,
        fa: fa,
        fr: fr,
        nl: nl,
        tr: tr
    },
    events: 'input|change|blur'
});

/**
 * Vue prototype.
 */
Vue.prototype.$http = axios;

/**
 * Window assignation.
 */
window.Vue = Vue;
window.eventBus = new Vue();
window.VeeValidate = VeeValidate;

/**
 * Global components.
 */
Vue.component(
    'nav-slide-button',
    require('./components/navigation/nav-slide-button').default
);
Vue.component(
    'required-if',
    require('./components/validators/required-if').default
);

$(function() {
    Vue.config.ignoredElements = ['option-wrapper', 'group-form', 'group-list'];

    let app = new Vue({
        el: '#app',

        data: {
            modalIds: {}
        },

        mounted() {
            this.addServerErrors();

            this.addFlashMessages();

            this.$validator.localize(document.documentElement.lang);
        },

        methods: {
            onSubmit: function(e) {
                this.toggleButtonDisable(true);

                if (typeof tinyMCE !== 'undefined') tinyMCE.triggerSave();

                this.$validator.validateAll().then(result => {
                    if (result) {
                        e.target.submit();
                    } else {
                        this.activateAutoScroll();

                        this.toggleButtonDisable(false);

                        eventBus.$emit('onFormError');
                    }
                });
            },

            activateAutoScroll: function() {
                /**
                 * This is accordion element.
                 */
                const accordionElement = document.querySelector(
                    '.accordian.error'
                );

                /**
                 * This is normal element.
                 */
                const normalElement = document.querySelector(
                    '.control-error:first-of-type'
                );

                /**
                 * Scroll configs.
                 */
                const scrollConfigs = {
                    behavior: 'smooth',
                    block: 'end',
                    inline: 'nearest'
                };

                /**
                 * If accordion error is not found then scroll will fall to the normal element.
                 */
                if (accordionElement) {
                    accordionElement.scrollIntoView(scrollConfigs);
                    return;
                }

                normalElement.scrollIntoView(scrollConfigs);
            },

            toggleButtonDisable: function(value) {
                let buttons = document.getElementsByTagName('button');

                for (let i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = value;
                }
            },

            addServerErrors: function(scope = null) {
                for (let key in serverErrors) {
                    let inputNames = [];

                    key.split('.').forEach(function(chunk, index) {
                        if (index) {
                            inputNames.push('[' + chunk + ']');
                        } else {
                            inputNames.push(chunk);
                        }
                    });

                    let inputName = inputNames.join('');

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

            addFlashMessages: function() {
                if (typeof flashMessages == 'undefined') {
                    return;
                }

                const flashes = this.$refs.flashes;

                flashMessages.forEach(function(flash) {
                    flashes.addFlash(flash);
                }, this);
            },

            showModal: function(id) {
                this.$set(this.modalIds, id, true);
            }
        }
    });
});
