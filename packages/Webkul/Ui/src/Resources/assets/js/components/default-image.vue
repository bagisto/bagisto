<template>
    <img :src="this.$root.isDarkMode ? darkThemeImageUrl: lightThemeImageUrl"/>
</template>

<script>

export default {
    props: [
        'lightThemeImageUrl',
        'darkThemeImageUrl'
    ],
};
</script>
