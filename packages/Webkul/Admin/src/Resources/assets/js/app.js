require("./bootstrap");

window.Vue = require("vue");
window.VeeValidate = require("vee-validate");

Vue.use(VeeValidate);
Vue.prototype.$http = axios

window.eventBus = new Vue();

window.addEventListener('DOMContentLoaded', function() {
    moveDown = 60;
    moveUp =  -60;
    count = 0;
    countKeyUp = 0;
    pageDown = 60;
    pageUp = -60;
    scroll = 0;

    listLastElement = $('.menubar li:last-child').offset();
    lastElementOfNavBar = listLastElement.top;

    navbarTop = $('.navbar-left').css("top");
    menuTopValue = $('.navbar-left').css('top');
    menubarTopValue = menuTopValue;

    documentHeight = $(document).height();
    menubarHeight = $('ul.menubar').height();
    navbarHeight = $('.navbar-left').height();
    windowHeight = $(window).height();
    contentHeight = $('.content').height();
    innerSectionHeight = $('.inner-section').height();
    gridHeight = $('.grid-container').height();
    pageContentHeight = $('.page-content').height();

    if (menubarHeight <= windowHeight) {
        differenceInHeight = windowHeight - menubarHeight;
    } else {
        differenceInHeight = menubarHeight - windowHeight;
    }

    if (menubarHeight > windowHeight) {
        document.addEventListener("keydown", function(event) {
            if ((event.keyCode == 38) && count <= 0) {
                count = count + moveDown;

                $('.navbar-left').css("top", count + "px");
            } else if ((event.keyCode == 40) && count >= -differenceInHeight) {
                count = count + moveUp;

                $('.navbar-left').css("top", count + "px");
            } else if ((event.keyCode == 33) && countKeyUp <= 0) {
                countKeyUp = countKeyUp + pageDown;

                $('.navbar-left').css("top", countKeyUp + "px");
            } else if ((event.keyCode == 34) && countKeyUp >= -differenceInHeight) {
                countKeyUp = countKeyUp + pageUp;

                $('.navbar-left').css("top", countKeyUp + "px");
            } else {
                 $('.navbar-left').css("position", "fixed");
            }
        });

        $("body").css({minHeight: $(".menubar").outerHeight() + 100 + "px"});

        window.addEventListener('scroll', function() {
            documentScrollWhenScrolled = $(document).scrollTop();

                if (documentScrollWhenScrolled <= differenceInHeight + 200) {
                    $('.navbar-left').css('top', -documentScrollWhenScrolled + 60 + 'px');
                    scrollTopValueWhenNavBarFixed = $(document).scrollTop();
                }
        });
    }
});

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
