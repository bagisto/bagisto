window.jQuery = window.$ = $ = require('jquery');
window.Vue = require('vue');
window.VeeValidate = require('vee-validate');

Vue.use(VeeValidate);

$(document).ready(function () {
    const form = new Vue({
        el: 'form',
        
        mounted: function() {
            this.addServerErrors()
        },

        methods: {
            addServerErrors: function() {
                // this.errors.add('email', "Hello")
                // for (var key in serverErrors) {
                //     this.errors.add(key, serverErrors[key][0])
                // }
            }
        }
    });
});