<template>
    <div :class="currentType == 'video' ? '' : 'magnifier'">
        <video :key="activeImageVideoURL" v-if="currentType == 'video'" width="100%" height="100%" controls>
            <source :src="activeImageVideoURL" type="video/mp4">
        </video>

        <img v-else
            :src="activeImageVideoURL"
            :data-zoom-image="activeImageVideoURL"
            class="main-product-image">
    </div>
</template>

<style lang="scss">
    .magnifier {
        > img {
            max-width: 100%;
            min-height: 530px;
            max-height: 530px;
        }
    }
</style>

<script type="text/javascript">
    export default {
        props: ['src', 'type'],

        data: function () {
            return {
                activeImage: null,
                activeImageVideoURL: '',
                currentType: '',
            }
        },

        mounted: function () {
            /* type checking for media type */
            this.currentType = this.type;

            /* getting url */
            this.activeImageVideoURL = this.src;

            /* binding should be with class as ezplus is having bug of creating multiple containers */
            this.activeImage = $('.main-product-image');
            this.activeImage.attr('src', this.activeImageVideoURL);
            this.activeImage.data('zoom-image', this.activeImageVideoURL);

            /* initialise zoom */
            this.elevateZoom();

            this.$root.$on('changeMagnifiedImage', ({smallImageUrl, largeImageUrl, currentType}) => {
                /* removed old instance */
                $('.zoomContainer').remove();
                this.activeImage.removeData('elevateZoom');

                /* type checking for media type */
                this.currentType = currentType;

                /* getting url */
                this.activeImageVideoURL = largeImageUrl;

                /* update source for images */
                this.activeImage.attr('src', smallImageUrl);
                this.activeImage.data('zoom-image', largeImageUrl);

                /* reinitialize zoom */
                this.elevateZoom();
            });
        },

        methods: {
            elevateZoom: function () {
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