<template>
    <div class="magnifier">
        <img
            :src="src"
            :data-zoom-image="src"
            class="main-product-image"
        />
    </div>
</template>

<style lang="scss">
    .magnifier {
        > img {
            max-width: 100%;
            min-height: 450px;
            max-height: 450px;
        }
    }
</style>

<script type="text/javascript">
    export default {
        props: ['src'],

        data: function () {
            return {
                'activeImage': null
            }
        },

        mounted: function () {
            /* jQuery object */
            this.activeImage = $('.main-product-image');

            /* initialise zoom */
            this.elevateZoom();

            this.$root.$on('changeMagnifiedImage', ({smallImageUrl, largeImageUrl}) => {
                /* removed old instance */
                $('.zoomContainer').remove();
                this.activeImage.removeData('elevateZoom');

                /* update source for images */
                this.activeImage.attr('src', smallImageUrl);
                this.activeImage.data('zoom-image', largeImageUrl);

                /* reinitialize zoom */
                this.elevateZoom();
            });
        },

        methods: {
            'elevateZoom': function () {
                this.activeImage.ezPlus({
                    zoomLevel: 0.5,
                    cursor: 'pointer',
                    scrollZoom: true,
                    zoomWindowWidth: 250,
                    zoomWindowHeight: 250,
                });
            },
        }
    }
</script>