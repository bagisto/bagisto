<template>
    <a :href="href" class="btn btn-lg btn-primary" v-text="text" @click="checkMinimumOrder($event)"></a>
</template>

<script>
export default {
    props: [
        'href',
        'text',
        'cartDetails',
        'minimumOrderAmount',
        'minimumOrderMessage'
    ],

    methods: {
        checkMinimumOrder: function (e) {
            if (! (this.getCartDetails().base_sub_total > this.minimumOrderAmount)) {
                e.preventDefault();
                window.flashMessages = [{'type': 'alert-warning', 'message': this.minimumOrderMessage}];
                this.$root.addFlashMessages();
            }
        },

        getCartDetails: function () {
            return JSON.parse(this.cartDetails);
        }
    }
}
</script>