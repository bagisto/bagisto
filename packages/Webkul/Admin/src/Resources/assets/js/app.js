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

    if (menubarHeight > windowHeight ) {
        remainent = documentHeight - menubarHeight;
        travelRatio = remainent / (documentHeight - windowHeight);
    }

    console.log(menubarHeight, documentHeight, windowHeight);

    if (menubarHeight > documentHeight) {
        console.log('menu greater than document');

        $('.navbar-left').css('position', 'absolute');
    } else if (menubarHeight > windowHeight && menubarHeight < documentHeight) {
        console.log('menu bar height is greater than window but lesser than document');
    }

    $(document).scroll(function() {
        st = $(document).scrollTop();

        if (menubarHeight > windowHeight && menubarHeight < documentHeight) {
            console.log('case true');

            if (initialScroll == 0) {
                marginTopForMenubar = travelRatio * st;

                $('.navbar-left').css('top', + 60 - marginTopForMenubar);
            }
        }
    });
}

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
