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
        'isMinimumOrderCompleted',
        'minimumOrderMessage'
    ],

    methods: {
        checkMinimumOrder: function (e) {
            if (! this.isMinimumOrderCompleted) {
                e.preventDefault();
                window.flashMessages = [{'type': 'alert-warning', 'message': this.minimumOrderMessage.replace(/\&nbsp;/g, ' ') }];
                this.$root.addFlashMessages();
            }
        }
    }
}
</script>