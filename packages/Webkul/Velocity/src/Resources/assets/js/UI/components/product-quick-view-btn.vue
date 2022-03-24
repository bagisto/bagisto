<template>
    <div
        id="quick-view-btn-container"
        class="quick-view-btn-container"
        @click="openQuickView({event: $event})">

        <span class="rango-zoom-plus"></span>

        <button type="button">{{ __('products.quick-view') }}</button>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['quickViewDetails'],

        methods: {
            openQuickView: function ({event}) {
                event.preventDefault();
                event.stopPropagation();

                this.$root.quickView = true;
                this.$root.productDetails = this.quickViewDetails;

                $('body').toggleClass('overflow-hidden');
            },
        }
    }
</script>