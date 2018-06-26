$(document).ready(function () {
    function addFlash(flash) {
        flashMessages.push(flash)
    }

    Vue.component('flash-wrapper', require('./components/flash-wrapper'))
    Vue.component('flash', require('./components/flash'))

    const app = new Vue({
        el: '#app',

        mounted: function() {
            this.addFlashMessages()
        },

        methods: {
            addFlashMessages: function() {
                const flashes = this.$refs.flashes

                flashMessages.forEach(function(flash) {
                    flashes.addFlash(flash)
                }, this);
            }
        }
    });
})