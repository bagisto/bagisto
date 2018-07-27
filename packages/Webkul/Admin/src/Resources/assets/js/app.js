window.jQuery = window.$ = $ = require("jquery");
window.Vue = require("vue");
window.VeeValidate = require("vee-validate");

Vue.use(VeeValidate);

//register single file components here
// import VueFlatpickr from "vue-flatpickr";
// import "vue-flatpickr/theme/dark.css";
// Vue.use(VueFlatpickr);

Vue.component("datetime", require("./components/datetime"));

$(document).ready(function() {
    const app = new Vue({
        el: "#app",

        mounted: function() {
            this.addServerErrors();
            this.addFlashMessages();
        },

        methods: {
            onSubmit: function(e) {
                this.$validator.validateAll().then(result => {
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
                    const field = this.$validator.fields.find({
                        name: key,
                        scope: scope
                    });
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
