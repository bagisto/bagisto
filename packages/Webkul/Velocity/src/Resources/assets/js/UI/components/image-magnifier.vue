<template>
    <div class="outer-assets-container">
        <div class="video-container" v-if="currentType == 'video' || currentType == 'videos'">
            <video :key="activeImageVideoURL" width="100%" controls>
                <source :src="activeImageVideoURL" type="video/mp4" />
            </video>
        </div>

        <div class="image-container" v-else>
            <div class="magnifier">
                <img
                    :src="activeImageVideoURL"
                    :data-zoom-image="activeOriginalImage"
                    :class="[
                        ! isMobile()
                            ? 'main-product-image'
                            : 'vc-small-product-image',
                    ]"
                />
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.outer-assets-container {
    .image-container {
        .magnifier {
            > img {
                width: 100%;
            }
        }
    }

    @media only screen and (max-width: 992px) {
        .image-container {
            margin: 0 auto;
        }
    }

    .video-container {
        position: relative;
        min-height: 420px;
        max-height: 420px;

        video {
            max-height: 420px;
        }
    }
}
</style>

<script type="text/javascript">
export default {
    props: [
        'src',
        'zoomSrc',
        'type'
    ],

    data: function () {
        return {
            activeImage: null,
            activeImageVideoURL: this.src,
            activeOriginalImage: this.zoomSrc,
            currentType: this.type,
        };
    },

    mounted: function () {
        /* binding should be with class as ezplus is having bug of creating multiple containers */
        this.activeImage = $('.main-product-image');
        this.activeImage.attr('src', this.activeImageVideoURL);
        this.activeImage.data('zoom-image', this.activeOriginalImage);

        /* initialise zoom */
        this.elevateZoom();

        this.$root.$on(
            'changeMagnifiedImage',
            ({ largeImageUrl, originalImageUrl, currentType }) => {
                /* removed old instance */
                $('.zoomContainer').remove();
                this.activeImage.removeData('elevateZoom');

                /* getting url */
                this.activeImageVideoURL = largeImageUrl;

                /* getting url */
                this.activeOriginalImage = originalImageUrl;

                /* type checking for media type */
                this.currentType = currentType;

                /* waiting added for image because image element takes time load when switching from video  */
                this.waitForElement('.main-product-image', () => {
                    console.log(originalImageUrl)
                    /* update source for images */
                    this.activeImage = $('.main-product-image');
                    this.activeImage.attr('src', largeImageUrl);
                    this.activeImage.data('zoom-image', originalImageUrl);

                    /* reinitialize zoom */
                    this.elevateZoom();
                });
            }
        );
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
    },
};
</script>
