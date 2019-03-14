window.jQuery = window.$ = $ = require("jquery");
window.Vue = require("vue");
window.VeeValidate = require("vee-validate");
window.axios = require("axios");
require("./bootstrap");

Vue.use(VeeValidate);
Vue.prototype.$http = axios

window.eventBus = new Vue();

window.onload = function () {
    st = $(document).scrollTop();
    initialScroll = st;
    windowHeight = $(window).height();
    documentHeight = $(document).height();
    menubarHeight = $('ul.menubar').height();

    // console.log(windowHeight, menubarHeight);

    if (menubarHeight > windowHeight ) {
        remainent = documentHeight - menubarHeight;
        travelRatio = remainent / (documentHeight - windowHeight);
    }

    // if(initialScroll > windowHeight) {
    //     fold =
    // }

    marginTopForMenubar = travelRatio * initialScroll;

    console.log(initialScroll, travelRatio, marginTopForMenubar);

    // if(menubarHeight > windowHeight && initialScroll > 0) {
    //     marginTopForMenubar = travelRatio * initialScroll;
    //     console.log(marginTopForMenubar);
    //     // $('.navbar-left').css('top', + 60 - marginTopForMenubar);
    // }

    // $(document).scroll(function() {
    //     st = $(document).scrollTop();

    //     if (menubarHeight > windowHeight) {
    //         if (initialScroll == 0 && st < (windowHeight - 60)) {
    //             marginTopForMenubar = travelRatio * st;

    //             $('.navbar-left').css('top', + 60 - marginTopForMenubar);
    //         }
    //     }
    // });
}

$(document).ready(function () {
    var lastScrollTop = 0;

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
