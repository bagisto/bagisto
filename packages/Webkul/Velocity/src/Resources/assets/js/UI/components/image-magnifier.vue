<template>
    <div class="magnifier">
        <img
            :src="src"
            :data-zoom-image="src"
            ref="activeProductImage"
            id="active-product-image"
            class="main-product-image"
            alt=""
        />
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['src'],

        data: function () {
            return {
                'activeImage': null,
                'activeImageElement': null,
            }
        },

        mounted: function () {
            /* store image related info in global variables */
            this.activeImageElement = this.$refs.activeProductImage;

            /* convert into jQuery object */
            this.activeImage = new jQuery.fn.init(this.activeImageElement);

            /* initialise zoom */
            this.elevateZoom();

            this.$root.$on('changeMagnifiedImage', ({smallImageUrl, largeImageUrl}) => {
                /* removed old instance */
                $('.zoomContainer').remove();
                this.activeImage.removeData('elevateZoom');

                /* update source for images */
                this.activeImageElement.src = smallImageUrl;
                this.activeImage.data('zoom-image', (largeImageUrl ? largeImageUrl : smallImageUrl));

                /* reinitialize zoom */
                this.elevateZoom();
            });
        },

        methods: {
            'elevateZoom': function () {
                this.activeImage.ezPlus({
                    cursor: 'pointer',
                    scrollZoom: true,
                    zoomWindowWidth: 400,
                    zoomWindowHeight: 400,
                });
            },
        }
    }
</script>