/**
 * Main imports.
 */
import 'lazysizes';
import Vue from 'vue';
import axios from 'axios';
import accounting from 'accounting';
import VueCarousel from 'vue-carousel';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';
import VeeValidate from 'vee-validate';

/**
 * Lang imports.
 */
import ar from 'vee-validate/dist/locale/ar';
import de from 'vee-validate/dist/locale/de';
import fa from 'vee-validate/dist/locale/fa';
import fr from 'vee-validate/dist/locale/fr';
import nl from 'vee-validate/dist/locale/nl';
import tr from 'vee-validate/dist/locale/tr';

/**
 * Window assignation.
 */
window.Vue = Vue;
window.axios = axios;
window.eventBus = new Vue();
window.Carousel = VueCarousel;
window.VeeValidate = VeeValidate;
window.jQuery = window.$ = require('jquery');
window.BootstrapSass = require('bootstrap-sass');

/**
 * Vue use.
 */
Vue.use(VueToast);
Vue.use(VueCarousel);
Vue.use(BootstrapSass);
Vue.use(VeeValidate, {
    dictionary: {
        ar: ar,
        de: de,
        fa: fa,
        fr: fr,
        nl: nl,
        tr: tr
    }
});

/**
 * Filters and prototype.
 */
Vue.prototype.$http = axios;

Vue.filter('currency', function(value, argument) {
    return accounting.formatMoney(value, argument);
});

/**
 * UI components.
 **/
Vue.component('vue-slider', require('vue-slider-component'));
Vue.component('mini-cart', require('./UI/components/mini-cart'));
Vue.component('modal-component', require('./UI/components/modal'));
Vue.component('add-to-cart', require('./UI/components/add-to-cart'));
Vue.component('star-ratings', require('./UI/components/star-rating'));
Vue.component('quantity-btn', require('./UI/components/quantity-btn'));
Vue.component('proceed-to-checkout', require('./UI/components/proceed-to-checkout'));
Vue.component('sidebar-component', require('./UI/components/sidebar'));
Vue.component('product-card', require('./UI/components/product-card'));
Vue.component('wishlist-component', require('./UI/components/wishlist'));
Vue.component('carousel-component', require('./UI/components/carousel'));
Vue.component('child-sidebar', require('./UI/components/child-sidebar'));
Vue.component('card-list-header', require('./UI/components/card-header'));
Vue.component('logo-component', require('./UI/components/image-logo'));
Vue.component('magnify-image', require('./UI/components/image-magnifier'));
Vue.component('image-search-component', require('./UI/components/image-search'));
Vue.component('compare-component', require('./UI/components/product-compare'));
Vue.component('shimmer-component', require('./UI/components/shimmer-component'));
Vue.component('responsive-sidebar', require('./UI/components/responsive-sidebar'));
Vue.component('product-quick-view', require('./UI/components/product-quick-view'));
Vue.component('product-quick-view-btn', require('./UI/components/product-quick-view-btn'));
Vue.component('recently-viewed', require('./UI/components/recently-viewed'));
Vue.component('product-collections', require('./UI/components/product-collections'));
Vue.component('hot-category', require('./UI/components/hot-category'));
Vue.component('hot-categories', require('./UI/components/hot-categories'));
Vue.component('popular-category', require('./UI/components/popular-category'));
Vue.component('popular-categories', require('./UI/components/popular-categories'));
Vue.component('velocity-overlay-loader', require('./UI/components/overlay-loader'));

/**
 * Start from here.
 **/
$(document).ready(function() {
    /**
     * Define a mixin object.
     */
    Vue.mixin(require('./UI/components/trans'));

    Vue.mixin({
        data: function() {
            return {
                imageObserver: null,
                navContainer: false,
                headerItemsCount: 0,
                sharedRootCategories: [],
                responsiveSidebarTemplate: '',
                responsiveSidebarKey: Math.random(),
                baseUrl: document.querySelector('meta[name="base-url"]').content
            };
        },

        methods: {
            redirect: function(route) {
                route ? (window.location.href = route) : '';
            },

            debounceToggleSidebar: function(id, { target }, type) {
                this.toggleSidebar(id, target, type);
            },

            toggleSidebar: function(id, { target }, type) {
                if (
                    Array.from(target.classList)[0] == 'main-category' ||
                    Array.from(target.parentElement.classList)[0] ==
                        'main-category'
                ) {
                    let sidebar = $(`#sidebar-level-${id}`);
                    if (sidebar && sidebar.length > 0) {
                        if (type == 'mouseover') {
                            this.show(sidebar);
                        } else if (type == 'mouseout') {
                            this.hide(sidebar);
                        }
                    }
                } else if (
                    Array.from(target.classList)[0] == 'category' ||
                    Array.from(target.classList)[0] == 'category-icon' ||
                    Array.from(target.classList)[0] == 'category-title' ||
                    Array.from(target.classList)[0] == 'category-content' ||
                    Array.from(target.classList)[0] == 'rango-arrow-right'
                ) {
                    let parentItem = target.closest('li');

                    if (target.id || parentItem.id.match('category-')) {
                        let subCategories = $(
                            `#${
                                target.id ? target.id : parentItem.id
                            } .sub-categories`
                        );

                        if (subCategories && subCategories.length > 0) {
                            let subCategories1 = Array.from(subCategories)[0];
                            subCategories1 = $(subCategories1);

                            if (type == 'mouseover') {
                                this.show(subCategories1);

                                let sidebarChild = subCategories1.find(
                                    '.sidebar'
                                );
                                this.show(sidebarChild);
                            } else if (type == 'mouseout') {
                                this.hide(subCategories1);
                            }
                        } else {
                            if (type == 'mouseout') {
                                let sidebar = $(`#${id}`);
                                sidebar.hide();
                            }
                        }
                    }
                }
            },

            show: function(element) {
                element.show();
                element.mouseleave(({ target }) => {
                    $(target.closest('.sidebar')).hide();
                });
            },

            hide: function(element) {
                element.hide();
            },

            toggleButtonDisability({ event, actionType }) {
                let button = event.target.querySelector('button[type=submit]');

                button ? (button.disabled = actionType) : '';
            },

            onSubmit: function(event) {
                this.toggleButtonDisability({ event, actionType: true });

                if (typeof tinyMCE !== 'undefined') tinyMCE.triggerSave();

                this.$validator.validateAll().then(result => {
                    if (result) {
                        event.target.submit();
                    } else {
                        this.toggleButtonDisability({
                            event,
                            actionType: false
                        });

                        eventBus.$emit('onFormError');
                    }
                });
            },

            isMobile: function() {
                if (
                    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i |
                    /mobi/i.test(navigator.userAgent)
                ) {
                    if (this.isMaxWidthCrossInLandScape()) {
                        return false;
                    }
                    return true;
                } else {
                    return false;
                }
            },

            isMaxWidthCrossInLandScape: function() {
                return window.innerWidth > 900;
            },

            loadDynamicScript: function(src, onScriptLoaded) {
                let dynamicScript = document.createElement('script');
                dynamicScript.setAttribute('src', src);
                document.body.appendChild(dynamicScript);

                dynamicScript.addEventListener('load', onScriptLoaded, false);
            },

            getDynamicHTML: function(input) {
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

            getStorageValue: function(key) {
                let value = window.localStorage.getItem(key);

                if (value) {
                    value = JSON.parse(value);
                }

                return value;
            },

            setStorageValue: function(key, value) {
                window.localStorage.setItem(key, JSON.stringify(value));

                return true;
            }
        }
    });

    const app = new Vue({
        el: '#app',
        VueToast,

        data: function() {
            return {
                modalIds: {},
                miniCartKey: 0,
                quickView: false,
                productDetails: [],
                showPageLoader: false
            };
        },

        created: function() {
            setTimeout(() => {
                document.body.classList.remove('modal-open');
            }, 0);

            window.addEventListener('click', () => {
                let modals = document.getElementsByClassName('sensitive-modal');

                Array.from(modals).forEach(modal => {
                    modal.classList.add('hide');
                });
            });
        },

        mounted: function() {
            setTimeout(() => {
                this.addServerErrors();
            }, 0);

            document.body.style.display = 'block';
            this.$validator.localize(document.documentElement.lang);

            this.loadCategories();
            this.addIntersectionObserver();
        },

        methods: {
            onSubmit: function(event) {
                this.toggleButtonDisability({ event, actionType: true });

                if (typeof tinyMCE !== 'undefined') tinyMCE.triggerSave();

                this.$validator.validateAll().then(result => {
                    if (result) {
                        event.target.submit();
                    } else {
                        this.toggleButtonDisability({
                            event,
                            actionType: false
                        });

                        eventBus.$emit('onFormError');
                    }
                });
            },

            toggleButtonDisable(value) {
                var buttons = document.getElementsByTagName('button');

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = value;
                }
            },

            addServerErrors: function(scope = null) {
                for (var key in serverErrors) {
                    var inputNames = [];
                    key.split('.').forEach(function(chunk, index) {
                        if (index) {
                            inputNames.push('[' + chunk + ']');
                        } else {
                            inputNames.push(chunk);
                        }
                    });

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

            addFlashMessages: function() {
                if (window.flashMessages.alertMessage)
                    window.alert(window.flashMessages.alertMessage);
            },

            showModal: function(id) {
                this.$set(this.modalIds, id, true);
            },

            loadCategories: function() {
                this.$http
                    .get(`${this.baseUrl}/categories`)
                    .then(response => {
                        this.sharedRootCategories = response.data.categories;
                        $(
                            `<style type='text/css'> .sub-categories{ min-height:${response
                                .data.categories.length * 30}px;} </style>`
                        ).appendTo('head');
                    })
                    .catch(error => {
                        console.log('failed to load categories');
                    });
            },

            addIntersectionObserver: function() {
                this.imageObserver = new IntersectionObserver(
                    (entries, imgObserver) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const lazyImage = entry.target;
                                lazyImage.src = lazyImage.dataset.src;
                            }
                        });
                    }
                );
            },

            showLoader: function() {
                $('#loader').show();
                $('.overlay-loader').show();

                document.body.classList.add('modal-open');
            },

            hideLoader: function() {
                $('#loader').hide();
                $('.overlay-loader').hide();

                document.body.classList.remove('modal-open');
            }
        }
    });

    /**
     * For compilation of html coming from server.
     */
     Vue.component('vnode-injector', {
        functional: true,
        props: ['nodes'],
        render(h, { props }) {
            return props.nodes;
        }
    });

    window.app = app;

    window.showAlert = (messageType, messageLabel, message) => {
        if (messageType && message !== '') {
            let alertId = Math.floor(Math.random() * 1000);

            let html = `<div class="alert ${messageType} alert-dismissible" id="${alertId}">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>${messageLabel ? messageLabel + '!' : ''} </strong> ${message}.
            </div>`;

            $('#alert-container').append(html).ready(() => {
                window.setTimeout(() => {
                    $(`#alert-container #${alertId}`).remove();
                }, 5000);
            });
        }
    };
});
