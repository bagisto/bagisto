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

.rtl .toggle-aside-nav {
    left: -12px;
    right: unset;
}
</style>

<script>
export default {
    props: [
        'iconClass',
    ],

    methods: {
        toggle: function () {
            if ($('.aside-nav').hasClass('active')) {
                this.hide();
            } else {
                this.show();
            }
        },

        hide: function () {
            $('#nav-expand-button').show();
            $('.aside-nav').hide(function () {
                $(this).removeClass('active');
            });
        },

        show: function () {
            $('#nav-expand-button').hide();
            $('.aside-nav').show(function () {
                $(this).addClass('active');
            });
        }
    }
}
</script>