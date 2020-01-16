<template>
    <carousel
        :perPage="parseInt(slidesPerPage)"
        :navigationEnabled="true"
        :paginationEnabled="true"
        :class="
            [(navigationEnabled == 'hide') ? 'navigation-hide' : '',
            (paginationEnabled == 'hide') ? 'pagination-hide' : '',
            addClass]
        ">

        <slot
            v-for="index in slidesCount"
            :name="`slide-${parseInt(index) - 1}`">
        </slot>
    </carousel>
</template>

<script type="text/javascript">
    export default {
        props: [
            'id',
            'addClass',
            'slidesCount',
            'slidesPerPage',
            'navigationEnabled',
            'paginationEnabled',
        ],

        data: function () {
            return {}
        }
    }
</script>