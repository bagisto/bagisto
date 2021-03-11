<template>
    <span class="toggle-aside-nav" @click="toggle">
        <i class="icon" :class="iconClass"></i>
    </span>
</template>

<style scoped>
    .toggle-aside-nav {
        position: absolute;
        top: 50px;
        left: 78px;
    }
</style>

<script>
export default {
    data: function() {
        return {
            iconClass: 'accordian-right-icon'
        };
    },

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
                self.iconClass = 'accordian-right-icon';

                $('.content-wrapper').css({
                    marginLeft: 'unset'
                });
            });
        },

        show: function () {
            let self = this;

            $('.aside-nav').show(function () {
                self.iconClass = 'accordian-left-icon';

                $('.content-wrapper').css({
                    marginLeft: '280px'
                });
            });
        }
    }
}
</script>