<template>
    <div class="modal-parent" v-if="$root.loading">
        <div class="overlay-loader">
            <div class="cp-spinner cp-round"></div>
            <div class="message-block" v-text="__('shop.overlay-loader.message')"></div>
        </div>
    </div>
</template>

<style scoped>
    .message-block {
        position: relative;
        top: 60px;
        color: #fff;
        font-size: 16px;
    }
</style>

<script>
    export default {};
</script>
