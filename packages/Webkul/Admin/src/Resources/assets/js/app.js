window.jQuery = window.$ = $ = require("jquery");
window.Vue = require("vue");
window.VeeValidate = require("vee-validate");

Vue.use(VeeValidate);

Vue.component("datetime", require("./components/datetime"));
Vue.component("date", require("./components/date"));
require('flatpickr/dist/flatpickr.css');

$(document).ready(function () {
    Vue.config.ignoredElements = [
        'option-wrapper',
        'group-form',
        'group-list'
    ];

    var app = new Vue({
        el: '#app',

        data: {
            modalIds: {}
        },

        mounted () {
            this.addServerErrors()
            this.addFlashMessages()
        },

        methods: {
            onSubmit (e) {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        e.target.submit();
                    }
                });
            },

            addServerErrors () {
                var scope = null;
                for (var key in serverErrors) {
                    var inputName = key;
                    if(key.indexOf('.') !== -1) {
                        inputName = key.replace(".", "[") + ']';
                    }

                    const field = this.$validator.fields.find({ name: inputName, scope: scope });
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
                const flashes = this.$refs.flashes;

                flashMessages.forEach(function(flash) {
                    flashes.addFlash(flash);
                }, this);
            },

            showModal (id) {
                this.$set(this.modalIds, id, true);
            }
        }
    });
});
