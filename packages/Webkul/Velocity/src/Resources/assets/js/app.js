import Vue from 'vue';
import accounting from 'accounting';
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

Vue.filter('currency', function (value, argument) {
    return accounting.formatMoney(value, argument);
})

window.Vue = Vue;
window.Carousel = VueCarousel;

// UI components
Vue.component("vue-slider", require("vue-slider-component"));
Vue.component('modal-component', require('./UI/components/modal'));
Vue.component('quantity-btn', require('./UI/components/quantity-btn'));
Vue.component('sidebar-component', require('./UI/components/sidebar'));
Vue.component("wishlist-component", require("./UI/components/wishlist"));
Vue.component('carousel-component', require('./UI/components/carousel'));
Vue.component('child-sidebar', require('./UI/components/child-sidebar'));
Vue.component('card-list-content', require('./UI/components/card-list'));
Vue.component('card-list-header', require('./UI/components/card-header'));
Vue.component('magnify-image', require('./UI/components/image-magnifier'));
Vue.component('responsive-sidebar', require('./UI/components/responsive-sidebar'));

window.eventBus = new Vue();

$(document).ready(function () {
    // define a mixin object
    Vue.mixin({
        data: function () {
            return {
                'navContainer': false,
                'responsiveSidebarTemplate': '',
                'responsiveSidebarKey': Math.random(),
            }
        },

        methods: {
            redirect: function (route) {
                route ? window.location.href = route : '';
            },

            toggleSidebar: function (id, {target}, type) {
                if (
                    Array.from(target.classList)[0] == "main-category"
                    || Array.from(target.parentElement.classList)[0] == "main-category"
                ) {
                    let sidebar = $(`#sidebar-level-${id}`);
                    if (sidebar && sidebar.length > 0) {
                        if (type == "mouseover") {
                            this.show(sidebar);
                        } else if (type == "mouseout") {
                            this.hide(sidebar);
                        }
                    }
                } else if (
                    Array.from(target.classList)[0] == "category"
                    || Array.from(target.classList)[0] == "category-icon"
                    || Array.from(target.classList)[0] == "category-title"
                    || Array.from(target.classList)[0] == "category-content"
                    || Array.from(target.classList)[0] == "rango-arrow-right"
                ) {
                    let parentItem = target.closest('li');
                    if (target.id || parentItem.id.match('category-')) {
                        let subCategories = $(`#${target.id ? target.id : parentItem.id} .sub-categories`);

                        if (subCategories && subCategories.length > 0) {
                            let subCategories1 = Array.from(subCategories)[0];
                            subCategories1 = $(subCategories1);

                            if (type == "mouseover") {
                                this.show(subCategories1);

                                let sidebarChild = subCategories1.find('.sidebar');
                                this.show(sidebarChild);
                            } else if (type == "mouseout") {
                                this.hide(subCategories1);
                            }
                        } else {
                            if (type == "mouseout") {
                                let sidebar = $(`#${id}`);
                                sidebar.hide();
                            }
                        }
                    }
                }
            },

            show: function (element) {
                element.show();
                element.mouseleave(({target}) => {
                    $(target.closest('.sidebar')).hide();
                });
            },

            hide: function (element) {
                element.hide();
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

            isMobile() {
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                  return true
                } else {
                  return false
                }
            },
        }
    });

    new Vue({
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
            });
        },

        mounted: function () {
            document.body.style.display = "block";

            let sliderContainer = $('.slides-container');
            if (sliderContainer && sliderContainer.length) {
                sliderContainer = sliderContainer[0];

                let carouselImg = sliderContainer.querySelector('.VueCarousel-inner img');

                setTimeout(() => {
                    // debugger
                    carouselImg.scrollTo(0, 500);
                    // carouselImg.scrollTo(500, 0);
                }, 1000);
            }
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
                // const flashes = this.$refs.flashes;

                // flashMessages.forEach(function (flash) {
                //     flashes.addFlash(flash);
                // }, this);

                if (window.flashMessages.alertMessage)
                    window.alert(window.flashMessages.alertMessage);
            },

            showModal: function (id) {
                this.$set(this.modalIds, id, true);
            },
        }
    });
});
