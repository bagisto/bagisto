<template>
    <div class="magnifier col-12 text-center no-padding">
        <img
            :src="src"
            :data-zoom-image="src"
            ref="activeProductImage"
            id="active-product-image"
            class="main-product-image"
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
            // store image related info in global variables
            this.activeImageElement = this.$refs.activeProductImage;

            // convert into jQuery object
            this.activeImage = new jQuery.fn.init(this.activeImageElement);

            this.elevateZoom();

            this.$root.$on('changeMagnifiedImage', ({smallImageUrl, largeImageUrl}) => {
                this.activeImageElement.src = smallImageUrl;

                this.activeImage.data('zoom-image', (largeImageUrl ? largeImageUrl : smallImageUrl));

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