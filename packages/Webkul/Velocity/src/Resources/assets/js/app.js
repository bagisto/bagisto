import Vue from 'vue';
import accounting from 'accounting';
import VueCarousel from 'vue-carousel';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';
import messagesAr from 'vee-validate/dist/locale/ar';

window.axios = require("axios");
window.VeeValidate = require("vee-validate");
window.jQuery = window.$ = require("jquery");
window.BootstrapSass = require("bootstrap-sass");

Vue.use(VueToast);
Vue.use(VeeValidate);
Vue.use(VueCarousel);
Vue.use(BootstrapSass);
Vue.prototype.$http = axios;

Vue.use(VeeValidate, {
    dictionary: {
        ar: { messages: messagesAr }
    }
});

Vue.filter('currency', function (value, argument) {
    return accounting.formatMoney(value, argument);
})

window.Vue = Vue;
window.Carousel = VueCarousel;

import VueSlider from 'vue-slider-component';
import MiniCart from './UI/components/mini-cart';
import ModalComponent from './UI/components/modal';
import AddToCart from './UI/components/add-to-cart';
import StarRating from './UI/components/star-rating';
import QuantityBtn from './UI/components/quantity-btn';
import Sidebar from './UI/components/sidebar';
import ProductCard from './UI/components/product-card';
import Wishlist from './UI/components/wishlist';
import Carousel from './UI/components/carousel';
import ChildSidebar from './UI/components/child-sidebar';
import CardHeader from './UI/components/card-header';
import ImageMagnifier from './UI/components/image-magnifier';
import ProductCompare from './UI/components/product-compare';
import ShimmerComponent from './UI/components/shimmer-component';
import ResponsiveSidebar from './UI/components/responsive-sidebar';
import ProductQuickView from './UI/components/product-quick-view';
import ProductQuickViewBtn from './UI/components/product-quick-view-btn';

// UI components
Vue.component("vue-slider", VueSlider);
Vue.component('mini-cart', MiniCart);
Vue.component('modal-component', ModalComponent);
Vue.component("add-to-cart", AddToCart);
Vue.component('star-ratings', StarRating);
Vue.component('quantity-btn', QuantityBtn);
Vue.component('sidebar-component', Sidebar);
Vue.component("product-card", ProductCard);
Vue.component("wishlist-component", Wishlist);
Vue.component('carousel-component', Carousel);
Vue.component('child-sidebar', ChildSidebar);
Vue.component('card-list-header', CardHeader);
Vue.component('magnify-image', ImageMagnifier);
Vue.component('compare-component', ProductCompare);
Vue.component("shimmer-component", ShimmerComponent);
Vue.component('responsive-sidebar', ResponsiveSidebar);
Vue.component('product-quick-view', ProductQuickView);
Vue.component('product-quick-view-btn', ProductQuickViewBtn);

window.eventBus = new Vue();

$(document).ready(function () {
    // define a mixin object
    Vue.mixin(require('./UI/components/trans'));

    Vue.mixin({
        data: function () {
            return {
                'baseUrl': document.querySelector("script[src$='velocity.js']").getAttribute('baseUrl'),
                'navContainer': false,
                'headerItemsCount': 0,
                'responsiveSidebarTemplate': '',
                'responsiveSidebarKey': Math.random(),
                'sharedRootCategories': [],
                'imageObserver': null,
            }
        },

        methods: {
            redirect: function (route) {
                route ? window.location.href = route : '';
            },

            debounceToggleSidebar: function (id, {target}, type) {
                // setTimeout(() => {
                    this.toggleSidebar(id, target, type);
                // }, 500);
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
                    Array.from(target.classList)[0]     == "category"
                    || Array.from(target.classList)[0]  == "category-icon"
                    || Array.from(target.classList)[0]  == "category-title"
                    || Array.from(target.classList)[0]  == "category-content"
                    || Array.from(target.classList)[0]  == "rango-arrow-right"
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

            isMobile: function () {
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                  return true
                } else {
                  return false
                }
            },

            getDynamicHTML: function (input) {
                var _staticRenderFns;
                const { render, staticRenderFns } = Vue.compile(input);

                if (this.$options.staticRenderFns.length > 0) {
                    _staticRenderFns = this.$options.staticRenderFns;
                } else {
                    _staticRenderFns = this.$options.staticRenderFns = staticRenderFns;
                }

                try {
                    var output = render.call(this, this.$createElement);
                } catch (exception) {
                    console.log(this.__('error.something_went_wrong'));
                }

                this.$options.staticRenderFns = _staticRenderFns;

                return output;
            },

            getStorageValue: function (key) {
                let value = window.localStorage.getItem(key);

                if (value) {
                    value = JSON.parse(value);
                }

                return value;
            },

            setStorageValue: function (key, value) {
                window.localStorage.setItem(key, JSON.stringify(value));

                return true;
            },
        }
    });

    new Vue({
        el: "#app",
        VueToast,

        data: function () {
            return {
                modalIds: {},
                miniCartKey: 0,
                quickView: false,
                productDetails: [],
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
            setTimeout(() => {
                this.addServerErrors();
            }, 0);

            document.body.style.display = "block";
            this.$validator.localize(document.documentElement.lang);

            this.loadCategories();
            this.addIntersectionObserver();
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

            loadCategories: function () {
                this.$http.get(`${this.baseUrl}/categories`)
                .then(response => {
                    this.sharedRootCategories = response.data.categories;
                    $(`<style type='text/css'> .sub-categories{ min-height:${response.data.categories.length * 30}px;} </style>`).appendTo("head");
                })
                .catch(error => {
                    console.log('failed to load categories');
                })
            },

            addIntersectionObserver: function () {
                this.imageObserver = new IntersectionObserver((entries, imgObserver) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            const lazyImage = entry.target
                            lazyImage.src = lazyImage.dataset.src
                        }
                    })
                });
            },
        }
    });

    // for compilation of html coming from server
    Vue.component('vnode-injector', {
        functional: true,
        props: ['nodes'],
        render(h, {props}) {
            return props.nodes;
        }
    });
});
