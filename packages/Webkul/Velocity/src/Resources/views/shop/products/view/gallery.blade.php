@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@php
    $images = $productImageHelper->getGalleryImages($product);
@endphp

{!! view_render_event('bagisto.shop.products.view.gallery.before', ['product' => $product]) !!}

    <div class="product-image-group">
        <div class="row col-12">
            <magnify-image src="{{ $images[0]['large_image_url'] }}" v-if="!isMobile()">
            </magnify-image>

            <img
                v-else
                class="vc-small-product-image"
                src="{{ $images[0]['large_image_url'] }}" />
        </div>

        <div class="row col-12">
            <product-gallery></product-gallery>
        </div>

    </div>

{!! view_render_event('bagisto.shop.products.view.gallery.after', ['product' => $product]) !!}

<script type="text/x-template" id="product-gallery-template">
    <ul class="thumb-list col-12 row" type="none">
        @if (sizeof($images) > 4)
            <li class="arrow left" @click="scroll('prev')">
                <i class="rango-arrow-left fs24"></i>
            </li>
        @endif

        <carousel-component
            slides-per-page="4"
            :id="galleryCarouselId"
            pagination-enabled="hide"
            navigation-enabled="hide"
            add-class="product-gallary"
            :slides-count="{{ sizeof($images) }}">

            @foreach ($images as $index => $thumb)
                <slide slot="slide-{{ $index }}">
                    <li
                        @click="changeImage({
                            largeImageUrl: '{{ $thumb['large_image_url'] }}',
                            originalImageUrl: '{{ $thumb['original_image_url'] }}',
                        })"
                        :class="[
                            'thumb-frame',
                            '{{ $index + 1 == 4  ? "" : "mr5"}}',
                            '{{ $thumb['large_image_url'] }}' == currentLargeImageUrl ? 'active' : '',
                        ]">

                        <div
                            class="bg-image"
                            style="background-image: url( {{ $thumb['small_image_url'] }} )">
                        </div>

                    </li>
                </slide>
            @endforeach
        </carousel-component>

        @if (sizeof($images) > 4)
            <li class="arrow right" @click="scroll('next')">
                <i class="rango-arrow-right fs24"></i>
            </li>
        @endif
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
                        counter: {
                            up: 0,
                            down: 0,
                        }
                    }
                },

                watch: {
                    'images': function(newVal, oldVal) {
                        this.changeImage({
                            largeImageUrl: this.images[0]['large_image_url'],
                            originalImageUrl: this.images[0]['original_image_url'],
                        })

                        this.prepareThumbs()
                    }
                },

                created: function() {
                    this.changeImage({
                        largeImageUrl: this.images[0]['large_image_url'],
                        originalImageUrl: this.images[0]['original_image_url'],
                    })

                    this.prepareThumbs()
                },

                methods: {
                    prepareThumbs: function() {
                        this.thumbs = [];

                        this.images.forEach(image => {
                            this.thumbs.push(image);
                        });
                    },

                    changeImage: function({largeImageUrl, originalImageUrl}) {
                        this.currentLargeImageUrl = largeImageUrl;

                        this.currentOriginalImageUrl = originalImageUrl;

                        this.$root.$emit('changeMagnifiedImage', {
                            smallImageUrl: this.currentOriginalImageUrl
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