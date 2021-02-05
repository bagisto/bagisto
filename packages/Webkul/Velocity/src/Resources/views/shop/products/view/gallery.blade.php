@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@php
    $images = productimage()->getGalleryImages($product);
    $videos = productvideo()->getVideos($product);

    $videoData = $imageData = [];

    foreach ($videos as $key => $video) {
        $videoData[$key]['type'] = $video['type'];
        $videoData[$key]['large_image_url'] = $videoData[$key]['small_image_url']= $videoData[$key]['medium_image_url']= $videoData[$key]['original_image_url'] = $video['video_url'];
    }

    foreach ($images as $key => $image) {
        $imageData[$key]['type'] = '';
        $imageData[$key]['large_image_url']    = $image['large_image_url'];
        $imageData[$key]['small_image_url']    = $image['small_image_url'];
        $imageData[$key]['medium_image_url']   = $image['medium_image_url'];
        $imageData[$key]['original_image_url'] = $image['original_image_url'];
    }

    $images = array_merge($imageData, $videoData);
@endphp

{!! view_render_event('bagisto.shop.products.view.gallery.before', ['product' => $product]) !!}

    <div class="product-image-group">
        <div class="row col-12">
            <magnify-image src="{{ $images[0]['large_image_url'] }}" type="{{ $images[0]['type'] }}">
            </magnify-image>
        </div>

        <div class="row col-12">
            <product-gallery></product-gallery>
        </div>

    </div>

{!! view_render_event('bagisto.shop.products.view.gallery.after', ['product' => $product]) !!}

<script type="text/x-template" id="product-gallery-template">
    <ul class="thumb-list col-12 row ltr" type="none">
        <li class="arrow left" @click="scroll('prev')" v-if="thumbs.length > 4">
            <i class="rango-arrow-left fs24"></i>
        </li>

        <carousel-component
            slides-per-page="4"
            :id="galleryCarouselId"
            pagination-enabled="hide"
            navigation-enabled="hide"
            add-class="product-gallery"
            :slides-count="thumbs.length">

            <slide :slot="`slide-${index}`" v-for="(thumb, index) in thumbs">
                <li
                    @mouseover="changeImage({
                        largeImageUrl: thumb.large_image_url,
                        originalImageUrl: thumb.original_image_url,
                        currentType: thumb.type
                    })"
                    :class="`thumb-frame ${index + 1 == 4 ? '' : 'mr5'} ${thumb.large_image_url == currentLargeImageUrl ? 'active' : ''}`"
                    >

                    <video v-if="thumb.type == 'video'" width="110" height="110" controls>
                        <source :src="thumb.small_image_url" type="video/mp4">
                        {{ __('admin::app.catalog.products.not-support-video') }}
                    </video>

                    <div v-else
                        class="bg-image"
                        :style="`background-image: url(${thumb.small_image_url})`">
                    </div>
                </li>
            </slide>
        </carousel-component>

        <li class="arrow right" @click="scroll('next')" v-if="thumbs.length > 4">
            <i class="rango-arrow-right fs24"></i>
        </li>
    </ul>
</script>

@push('scripts')
    <script type="text/javascript">
        (() => {
            var galleryImages = @json($images);

            Vue.component('product-gallery', {
                template: '#product-gallery-template',
                data: function() {
                    return {
                        images: galleryImages,
                        thumbs: [],
                        galleryCarouselId: 'product-gallery-carousel',
                        currentLargeImageUrl: '',
                        currentOriginalImageUrl: '',
                        currentType: '',
                        counter: {
                            up: 0,
                            down: 0,
                        }
                    }
                },

                watch: {
                    'images': function(newVal, oldVal) {
                        if (this.images[0]) {
                            this.changeImage({
                                largeImageUrl: this.images[0]['large_image_url'],
                                originalImageUrl: this.images[0]['original_image_url'],
                                currentType: this.images[0]['type']
                            })
                        }

                        this.prepareThumbs()
                    }
                },

                created: function() {
                    this.changeImage({
                        largeImageUrl: this.images[0]['large_image_url'],
                        originalImageUrl: this.images[0]['original_image_url'],
                        currentType: this.images[0]['type']
                    });

                    eventBus.$on('configurable-variant-update-images-event', this.updateImages);

                    this.prepareThumbs();
                },

                methods: {
                    updateImages: function (galleryImages) {
                        this.images = galleryImages;
                    },

                    prepareThumbs: function() {
                        this.thumbs = [];

                        this.images.forEach(image => {
                            this.thumbs.push(image);
                        });
                    },

                    changeImage: function({largeImageUrl, originalImageUrl, currentType}) {
                        this.currentLargeImageUrl = largeImageUrl;

                        this.currentOriginalImageUrl = originalImageUrl;

                        this.currentType = currentType;

                        this.$root.$emit('changeMagnifiedImage', {
                            smallImageUrl: this.currentOriginalImageUrl,
                            largeImageUrl: this.currentLargeImageUrl,
                            currentType  : this.currentType
                        });

                        let productImage = $('.vc-small-product-image');
                        if (productImage && productImage[0]) {
                            productImage = productImage[0];

                            productImage.src = this.currentOriginalImageUrl;
                        }
                    },

                    scroll: function (navigateTo) {
                        let navigation = $(`#${this.galleryCarouselId} .VueCarousel-navigation .VueCarousel-navigation-${navigateTo}`);

                        if (navigation && (navigation = navigation[0])) {
                            navigation.click();
                        }
                    },
                }
            });
        })()
    </script>
@endpush