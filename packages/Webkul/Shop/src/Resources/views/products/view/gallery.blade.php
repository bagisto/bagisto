@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
<?php $images = $productImageHelper->getGalleryImages($product); ?>

{!! view_render_event('bagisto.shop.products.view.gallery.before', ['product' => $product]) !!}

<div class="product-image-group">

    <div class="cp-spinner cp-round" id="loader">
    </div>

    <product-gallery></product-gallery>

    @include ('shop::products.view.product-add')

</div>

{!! view_render_event('bagisto.shop.products.view.gallery.after', ['product' => $product]) !!}

@push('scripts')

    <script type="text/x-template" id="product-gallery-template">
        <div>

            <ul class="thumb-list">
                <li class="gallery-control top" @click="moveThumbs('top')" v-if="(thumbs.length > 4) && this.is_move.up">
                    <span class="overlay"></span>
                    <i class="icon arrow-up-white-icon"></i>
                </li>

                <li class="thumb-frame" v-for='(thumb, index) in thumbs' @mouseover="changeImage(thumb)" :class="[thumb.large_image_url == currentLargeImageUrl ? 'active' : '']" id="thumb-frame">
                    <img :src="thumb.small_image_url" :data-image="thumb.large_image_url" :data-zoom-image="thumb.original_image_url"/>
                </li>

                <li class="gallery-control bottom" @click="moveThumbs('bottom')" v-if="(thumbs.length > 4) && this.is_move.down">
                    <span class="overlay"></span>
                    <i class="icon arrow-down-white-icon"></i>
                </li>
            </ul>

            <div class="product-hero-image" id="product-hero-image">
                <img :src="currentLargeImageUrl" id="pro-img"/>

                {{-- Uncomment the line below for activating share links --}}
                {{-- @include('shop::products.sharelinks') --}}

                @auth('customer')
                    <a class="add-to-wishlist" href="{{ route('customer.wishlist.add', $product->id) }}">
                    </a>
                @endauth
            </div>

        </div>
    </script>

    <script>
        var galleryImages = @json($images);

        Vue.component('product-gallery', {

            template: '#product-gallery-template',

            data: () => ({
                images: galleryImages,

                thumbs: [],

                currentLargeImageUrl: '',

                counter: {
                    up: 0,
                    down: 0,
                },

                is_move: {
                    up: true,
                    down: true,
                }
            }),

            watch: {
                'images': function(newVal, oldVal) {
                    this.changeImage(this.images[0])

                    this.prepareThumbs()
                }
            },

            created () {
                this.changeImage(this.images[0])

                this.prepareThumbs()
            },

            methods: {
                prepareThumbs () {
                    var this_this = this;

                    this_this.thumbs = [];

                    this.images.forEach(function(image) {
                        this_this.thumbs.push(image);
                    });
                },

                changeImage (image) {
                    this.currentLargeImageUrl = image.large_image_url;
                },

                moveThumbs(direction) {
                    let len = this.thumbs.length;

                    if (direction === "top") {
                        const moveThumb = this.thumbs.splice(len - 1, 1);

                        this.thumbs = [moveThumb[0], ...this.thumbs];

                        this.counter.up = this.counter.up+1;

                        this.counter.down = this.counter.down-1;

                    } else {
                        const moveThumb = this.thumbs.splice(0, 1);

                        this.thumbs = [...this.thumbs, moveThumb[0]];

                        this.counter.down = this.counter.down+1;

                        this.counter.up = this.counter.up-1;
                    }

                    if ((len-4) == this.counter.down) {
                        this.is_move.down = false;
                    } else {
                        this.is_move.down = true;
                    }

                    if ((len-4) == this.counter.up) {
                        this.is_move.up = false;
                    } else {
                        this.is_move.up = true;
                    }
                },
            }
        });

    </script>

    <script>
        $(document).ready(function() {
            var image = $('#thumb-frame img');
            var zoomImage = $('img#pro-img');

            zoomImage.ezPlus();

            image.mouseover( function(){
                $('.zoomContainer').remove();
                zoomImage.removeData('elevateZoom');
                zoomImage.attr('src', $(this).data('image'));
                zoomImage.data('zoom-image', $(this).data('zoom-image'));
                zoomImage.ezPlus();
            });

            $(document).mousemove(function(event) {
                if ( (event.pageX - $('.product-hero-image').offset().left > 440 ) && (event.pageX - $('.product-hero-image').offset().left < 465) && (event.pageY - $('.product-hero-image').offset().top > 16) && (event.pageY - $('.product-hero-image').offset().top < 38))  {

                    $('.zoomContainer').attr('style', 'z-index: -1 !important');

                } else {
                    $('.zoomContainer').css({"position": "absolute", "top": "143px", "left"
                    : "249px", "height": "480px", "width": "480px", "z-index": "999" });
                }
            });
        })
    </script>

@endpush