window.jQuery = window.$ = $ = require("jquery");
window.Vue = require("vue");
window.VeeValidate = require("vee-validate");
window.axios = require("axios");

Vue.use(VeeValidate);
Vue.prototype.$http = axios

Vue.component("category-nav", require("./components/category-nav.vue"));
Vue.component("category-item", require("./components/category-item.vue"));
Vue.component("image-slider", require("./components/image-slider.vue"));
Vue.component("vue-slider", require("vue-slider-component"));
Vue.component("cart-dropdown", require("./components/cart-dropdown.vue"));

$(document).ready(function () {

    const app = new Vue({
        el: "#app",

        mounted: function () {
            this.addServerErrors();
            this.addFlashMessages();
        },

        methods: {
            onSubmit: function (e) {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        e.target.submit();
                    }
                });
            },

            addServerErrors: function () {
                var scope = null;
                for (var key in serverErrors) {
                    const field = this.$validator.fields.find({
                        name: key,
                        scope: scope
                    });
                    if (field) {
                        this.$validator.errors.add({
                            id: field.id,
                            field: key,
                            msg: serverErrors[key][0],
                            scope: scope
                        });
                    }
                }
            },

            addFlashMessages: function () {
                const flashes = this.$refs.flashes;

                flashMessages.forEach(function (flash) {
                    console.log(flash);
                    flashes.addFlash(flash);
                }, this);
            },
            responsiveHeader: function () { }
        }
    });
});
