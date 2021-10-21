<template>
    <a
        :class="`left ${addClass}`"
        :href="redirectSrc"
        aria-label="Logo"
    >
        <img class="logo" :src="imgSrc" alt="" />
    </a>
</template>

<script>
export default {
    template: '#logo-template',
    props: ['addClass', 'imgSrc', 'redirectSrc']
};
</script>
