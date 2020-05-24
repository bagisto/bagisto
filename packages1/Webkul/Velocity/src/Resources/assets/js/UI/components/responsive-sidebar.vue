<template>
    <div
        :key="key"
        class="nav-container scrollable"
        v-if="isMobile() && navContainer">
        <slot name="sidebar-body"></slot>
    </div>
</template>

<script type="text/javascript">
    export default {
        data: function() {
            return {
                templateRender: null
            }
        },

        staticRenderFns: [],

        created: function () {
            debugger
        },

        computed: {
            'key': function () {
                debugger
                return this.$root.responsiveSidebarKey;
            }
        },

        render: function (h) {
            debugger
        }
    }
</script>