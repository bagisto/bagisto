import Vue from 'vue';
import VueCarousel from 'vue-carousel';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';

window.axios = require("axios");
window.VeeValidate = require("vee-validate");
window.jQuery = window.$ = require("jquery");
window.BootstrapSass = require("bootstrap-sass");

Vue.use(VueToast);
Vue.use(VeeValidate);
Vue.use(VueCarousel);
Vue.use(BootstrapSass);
Vue.prototype.$http = axios;

window.Vue = Vue;
window.Carousel = VueCarousel;

// UI components
Vue.component('modal-component', require('./UI/components/modal'));
Vue.component('carousel-component', require('./UI/components/carousel'));
Vue.component('quantity-btn', require('./UI/components/quantity-btn'));
Vue.component('sidebar-component', require('./UI/components/sidebar'));
Vue.component('child-sidebar', require('./UI/components/child-sidebar'));
Vue.component('card-list-content', require('./UI/components/card-list'));
Vue.component('card-list-header', require('./UI/components/card-header'));
Vue.component('magnify-image', require('./UI/components/image-magnifier'));
Vue.component('content-header', require('./UI/components/content-header'));

window.eventBus = new Vue();

$(document).ready(function () {
    // define a mixin object
    Vue.mixin({
        methods: {
            redirect: function (route) {
                route ? window.location.href = route : '';
            },

            toggleSidebar: function () {
                let rightBarContainer = document.getElementById('home-right-bar-container');
                let categoryListContainer = document.getElementById('sidebar');

                if (categoryListContainer) {
                    categoryListContainer.classList.toggle('hide');
                }

                if (rightBarContainer.className.search('col-10') > -1) {
                    rightBarContainer.className = rightBarContainer.className.replace('col-10', 'col-12');
                } else {
                    rightBarContainer.className = rightBarContainer.className.replace('col-12', 'col-10');
                }
            },

            toggleButtonDisability ({event, actionType}) {
                let button = event.target.querySelector('button[type=submit]');

                button ? button.disabled = actionType : '';
            },

            onSubmit: function (event) {
                this.toggleButtonDisability({event, actionType: true});

                if(typeof tinyMCE !== 'undefined')
                    tinyMCE.triggerSave();

                this.$validator.validateAll().then(result => {
                    if (result) {
                        event.target.submit();
                    } else {
                        this.toggleButtonDisability({event, actionType: false});

                        eventBus.$emit('onFormError')
                    }
                });
            },
        }
    });

    const app = new Vue({
        el: "#app",
        VueToast,

        data: function () {
            return {
                modalIds: {}
            }
        },

        created: function () {
            window.addEventListener('click', () => {
                let modals = document.getElementsByClassName('sensitive-modal');

                Array.from(modals).forEach(modal => {
                    modal.classList.add('hide');
                });

            })
        },

        methods: {
            onSubmit: function (event) {
                this.toggleButtonDisability({event, actionType: true});

                if(typeof tinyMCE !== 'undefined')
                    tinyMCE.triggerSave();

                this.$validator.validateAll().then(result => {
                    if (result) {
                        event.target.submit();
                    } else {
                        this.toggleButtonDisability({event, actionType: false});

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

            showModal: function (id) {
                this.$set(this.modalIds, id, true);
            },
        }
    });
});
