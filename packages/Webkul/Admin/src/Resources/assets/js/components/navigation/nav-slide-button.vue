<template>
    <span class="toggle-aside-nav" @click="toggle">
        <i class="icon" :class="iconClass"></i>
    </span>
</template>

<style scoped>
    .toggle-aside-nav {
        position: absolute;
        top: 50px;
        right: -12px;
    }
</style>

<script>
export default {
    props: [
        'iconClass',
    ],

    methods: {
        toggle: function () {
            if ($('.aside-nav').is(':visible')) {
                this.hide();
            } else {
                this.show();
            }
        },

        hide: function () {
            let self = this;

            $('.aside-nav').hide(function () {
                $('.content-wrapper').css({
                    marginLeft: 'unset'
                });

                $('#nav-expand-button').show();
            });
        },

        show: function () {
            let self = this;

            $('#nav-expand-button').hide();

            $('.aside-nav').show(function () {
                $('.content-wrapper').css({
                    marginLeft: '280px'
                });
            });
        }
    }
}
</script>