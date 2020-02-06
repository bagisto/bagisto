<template>
    <carousel
        :id="id"
        :navigationEnabled="true"
        :paginationEnabled="true"
        :perPage="parseInt(slidesPerPage)"
        :loop="loop == 'true' ? true : false"
        :autoplay="autoplay == 'true' ? true : false"
        :autoplayTimeout="timeout ? parseInt(timeout) : 2000"
        :autoplayDirection="sliderDirection ? sliderDirection : 'forward'"
        :class="[
            (navigationEnabled == 'hide') ? 'navigation-hide' : '',
            (paginationEnabled == 'hide') ? 'pagination-hide' : '',
            addClass
        ]">

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
            'loop',
            'timeout',
            'autoplay',
            'addClass',
            'slidesCount',
            'slidesPerPage',
            'sliderDirection',
            'navigationEnabled',
            'paginationEnabled',
        ],

        data: function () {
            return {}
        },

        methods: {
            slideClicked: function () {
                debugger
            }
        }
    }
</script>