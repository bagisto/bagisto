<template>
    <div :class="[!isMobile() ? 'magnifier' : '']">
        <video :key=this.activeImageVideoURL v-if="currentType == 'video'" width="100%" height="100%" controls>
            <source :src=this.activeImageVideoURL ref="activeProductImage"
            id="active-product-image"
            class="main-product-image" type="video/mp4">
        </video>

        <img v-else
            :src=this.activeImageVideoURL
            :data-zoom-image="[!isMobile() ? 'src' : '']"
            :class="[!isMobile() ? 'main-product-image' : 'vc-small-product-image']"/>
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
                'activeImage': null,
                'activeImageElement': null,
                activeImageVideoURL: '',
                currentType: '',
            }
        },

        mounted: function () {
            /* store image related info in global variables */
            this.activeImageElement = this.$refs.activeProductImage;

            this.currentType = this.type;
            this.activeImageVideoURL = this.src;

            /* convert into jQuery object */
            this.activeImage = new jQuery.fn.init(this.activeImageElement);

            /* initialise zoom */
            this.elevateZoom();

            this.$root.$on('changeMagnifiedImage', ({smallImageUrl, largeImageUrl, currentType}) => {
                /* removed old instance */
                $('.zoomContainer').remove();
                this.activeImage.removeData('elevateZoom');

                this.currentType = currentType;

                this.activeImageVideoURL = largeImageUrl;

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