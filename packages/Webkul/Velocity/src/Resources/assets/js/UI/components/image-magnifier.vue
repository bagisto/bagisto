<template>
    <div class="video-container" v-if="currentType == 'video'">
        <video :key="activeImageVideoURL" width="100%" controls>
            <source :src="activeImageVideoURL" type="video/mp4">
        </video>
    </div>
    <div class="image-container" v-else>
        <div class="magnifier">
            <img :src="activeImageVideoURL" :data-zoom-image="activeImageVideoURL"
                :class="[!isMobile() ? 'main-product-image' : 'vc-small-product-image']">
        </div>
    </div>
</template>

<style lang="scss">
    .image-container {
        .magnifier {
            > img {
                max-width: 100%;
                min-height: 530px;
                max-height: 530px;
            }
        }
    }

    @media only screen and (max-width: 992px) {
        .image-container {
            .magnifier {
                > img {
                    height: 100%;
                    min-height: unset;
                    max-height: unset;
                }
            }
        }
    }

    .video-container {
        min-height: 530px;
        max-height: 530px;

        video {
            top: 50%;
            position: relative;
            transform: translateY(-50%);
        }
    }
</style>

<script type="text/javascript">
    export default {
        props: ['src', 'type'],

        data: function () {
            return {
                activeImage: null,
                activeImageVideoURL: this.src,
                currentType: this.type,
            }
        },

        mounted: function () {
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

                /* getting url */
                this.activeImageVideoURL = largeImageUrl;

                /* type checking for media type */
                this.currentType = currentType;

                /* waiting added for image because image element takes time load when switching from video  */
                this.waitForElement('.main-product-image', () => {
                    /* update source for images */
                    this.activeImage = $('.main-product-image');
                    this.activeImage.attr('src', smallImageUrl);
                    this.activeImage.data('zoom-image', largeImageUrl);

                    /* reinitialize zoom */
                    this.elevateZoom();
                });
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

            waitForElement: function (selector, callback) {
                if (jQuery(selector).length) {
                    callback();
                } else {
                    setTimeout(() => {
                        this.waitForElement(selector, callback);
                    }, 100);
                }
            },
        }
    }
</script>