<template>
    <div
        v-show="subCategories"
        class="submenu-container"
        id="sub-categories">

        <div class="submenu-content-container">
            <sidebar-component
                :url="url"
                :categories="subCategories"
            ></sidebar-component>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['url', 'subCategories'],

        data: function () {
            return {}
        }
    }
</script>