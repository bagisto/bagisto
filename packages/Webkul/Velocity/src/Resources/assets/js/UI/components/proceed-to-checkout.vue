<template>
    <a :href="href" :class="addClass" v-text="text" @click="checkMinimumOrder($event)"></a>
</template>

<script>
export default {
    props: [
        'href',
        'addClass',
        'text',
        'cartDetails',
        'minimumOrderAmount',
        'minimumOrderMessage'
    ],

    methods: {
        checkMinimumOrder: function (e) {
            if (! (this.getCartDetails().base_sub_total > this.minimumOrderAmount)) {
                e.preventDefault();
                window.showAlert(`alert-warning`, 'Warning', this.minimumOrderMessage);
            }
        },

        getCartDetails: function () {
            return JSON.parse(this.cartDetails);
        }
    }
}
</script>