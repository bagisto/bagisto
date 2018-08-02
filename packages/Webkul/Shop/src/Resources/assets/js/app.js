window.jQuery = window.$ = $ = require("jquery");
window.Vue = require("vue");
window.VeeValidate = require("vee-validate");

Vue.use(VeeValidate);

//pure JS for resizing of browser purposes only

Vue.component("category-nav", require("./components/category-nav.vue"));
Vue.component("category-item", require("./components/category-item.vue"));
Vue.component("image-slider", require("./components/imageSlider.vue"));

$(window).resize(function() {
    var w = $(document).width();
    var window = {};
    window.width = $(document).width();
    window.height = $(document).height();
    if (window.width < 785) {
        $(".header").css("margin-bottom", "0");
        $(".header-top").css("margin-bottom", "0");
        $("ul.search-container").css("display", "none");
        $(".header-bottom").css("display", "none");
        $("div.right-content").css("display", "none");
        $(".right-responsive").css("display", "inherit");
    } else if (window.width > 785) {
        $(".header").css("margin-bottom", "21px");
        $(".header-top").css("margin-bottom", "16px");
        $("ul.search-container").css("display", "inherit");
        $(".header-bottom").css("display", "block");
        $("div.right-content").css("display", "inherit");
        $(".right-responsive").css("display", "none");
    }
});

$(document).ready(function() {
    /* Responsiveness script goes here */
    var w = $(document).width();
    var window = {};
    window.width = $(document).width();
    window.height = $(document).height();
    if (window.width < 785) {
        $(".header").css("margin-bottom", "0");
        $(".header-top").css("margin-bottom", "0");
        $("ul.search-container").css("display", "none");
        $(".header-bottom").css("display", "none");
        $("div.right-content").css("display", "none");
        $(".right-responsive").css("display", "inherit");
    }
    /* Responsiveness script ends here */

    const app = new Vue({
        el: "#app",

        mounted: function() {
            this.addServerErrors();
            this.addFlashMessages();
        },

        methods: {
            onSubmit: function(e) {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        e.target.submit();
                    }
                });
            },

            addServerErrors: function() {
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

            addFlashMessages: function() {
                const flashes = this.$refs.flashes;

                flashMessages.forEach(function(flash) {
                    flashes.addFlash(flash);
                }, this);
            },
            responsiveHeader: function() {}
        }
    });
});
