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
            let base_sub_total = parseFloat(this.getCartDetails().base_sub_total);
            let minimumOrderAmount = parseFloat(this.minimumOrderAmount);

            if (! (base_sub_total > minimumOrderAmount)) {
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