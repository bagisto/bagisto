window.jQuery = window.$ = $ = require("jquery");
window.Vue = require("vue");
window.VeeValidate = require("vee-validate");
window.axios = require("axios");
require("./bootstrap");

Vue.use(VeeValidate);
Vue.prototype.$http = axios

window.eventBus = new Vue();

window.onload = function () {
    moveDistance = 30;
    navbarLeftCssTop = parseInt($('.navbar-left').css("top"));
    windowHeight = $(window).height();
    menubarHeight = $('ul.menubar').height();
    documentHeight = $(document).height();

    if (menubarHeight < windowHeight) {
        differenceInHeight = windowHeight - menubarHeight;
    } else {
        differenceInHeight = menubarHeight - windowHeight;
    }

    scrollTopWhenWindowLoaded = $(document).scrollTop();

    $('.navbar-left').css('top', -scrollTopWhenWindowLoaded + 60 + 'px');

    $(document).ready(function() {
        if (menubarHeight > documentHeight && menubarHeight > windowHeight) {
            $('.inner-section').css("position", "fixed");
            $('.navbar-left').css("position", "absolute");
        } else {
            if (scrollTopWhenWindowLoaded > differenceInHeight) {
                $('.navbar-left').css('top', -differenceInHeight + 'px');
            }

            if (menubarHeight > windowHeight) {
                $(document).scroll(function() {
                    documentScrollWhenScrolled = $(document).scrollTop();
                    if (documentScrollWhenScrolled <= differenceInHeight + 70) {
                        $('.navbar-left').css('top', -documentScrollWhenScrolled + 60 + 'px');
                        scrollTopValueWhenNavBarFixed = $(document).scrollTop();
                    }
                });
            } else if(menubarHeight < windowHeight) {
                $(document).scroll(function() {
                    documentScrollWhenScrolled = $(document).scrollTop();
                    if (documentScrollWhenScrolled <= differenceInHeight + 70) {
                        $('.navbar-left').css('top', -documentScrollWhenScrolled + 60 + 'px');
                        scrollTopValueWhenNavBarFixed = $(document).scrollTop();
                    }
                });
            }

        }
    });
};

$(document).ready(function () {
    Vue.config.ignoredElements = [
        'option-wrapper',
        'group-form',
        'group-list'
    ];

    var app = new Vue({
        el: "#app",

        data: {
            modalIds: {}
        },

        mounted() {
            this.addServerErrors();
            this.addFlashMessages();
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
                    }
                });
            },

            toggleButtonDisable (value) {
                var buttons = document.getElementsByTagName("button");

                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = value;
                }
            },

            addServerErrors() {
                var scope = null;
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

            addFlashMessages() {
                const flashes = this.$refs.flashes;

                flashMessages.forEach(function(flash) {
                    flashes.addFlash(flash);
                }, this);
            },

            showModal(id) {
                this.$set(this.modalIds, id, true);
            }
        }
    });
});