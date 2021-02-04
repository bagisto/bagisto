@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

<?php
    $images = productimage()->getGalleryImages($product);

    $videos = productvideo()->getVideos($product);

    $images = array_merge($images, $videos);
?>


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
                    <video v-if="thumb.type == 'video'" width="100%" height="100%" onclick="this.paused ? this.play() : this.pause();">
                        <source :src="thumb.video_url" type="video/mp4">
                        {{ __('admin::app.catalog.products.not-support-video') }}
                    </video>

                    <img v-else  :src="thumb.small_image_url" alt=""/>
                </li>

                <li class="gallery-control bottom" @click="moveThumbs('bottom')" v-if="(thumbs.length > 4) && this.is_move.down">
                    <span class="overlay"></span>
                    <i class="icon arrow-down-white-icon"></i>
                </li>
            </ul>

            <div class="product-hero-image" id="product-hero-image">
                <video :key="currentVideoUrl" v-if="currentType == 'video'" width="100%" height="100%" controls>
                    <source :src="currentVideoUrl" :data-image="currentOriginalImageUrl"  type="video/mp4">
                        {{ __('admin::app.catalog.products.not-support-video') }}
                </video>

                <img v-else :src="currentLargeImageUrl" id="pro-img" :data-image="currentOriginalImageUrl" alt=""/>

                @auth('customer')
                    @php
                        $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
                    @endphp

                    @if ($showWishlist)
                        <a @if ($wishListHelper->getWishlistProduct($product)) class="add-to-wishlist already" @else class="add-to-wishlist" @endif href="{{ route('customer.wishlist.add', $product->product_id) }}">
                        </a>
                    @endif
                @endauth
            </div>

        </div>
    </script>

    <script>
        var galleryImages = @json($images);

        Vue.component('product-gallery', {

            template: '#product-gallery-template',

            data: function() {
                return {
                    images: galleryImages,

                    thumbs: [],

                    currentLargeImageUrl: '',

                    currentOriginalImageUrl: '',

                    currentVideoUrl: '',

                    currentType: '',

                    counter: {
                        up: 0,
                        down: 0,
                    },

                    is_move: {
                        up: true,
                        down: true,
                    }
                }
            },

            watch: {
                'images': function(newVal, oldVal) {
                    this.changeImage(this.images[0])

                    this.prepareThumbs()
                }
            },

            created: function() {
                this.changeImage(this.images[0])

                this.prepareThumbs()
            },

            methods: {
                prepareThumbs: function() {
                    var this_this = this;

                    this_this.thumbs = [];

                    this.images.forEach(function(image) {
                        this_this.thumbs.push(image);
                    });
                },

                changeImage: function(image) {
                    this.currentType = image.type;

                    if (image.type == 'video') {
                        this.currentVideoUrl = image.video_url;

                        this.currentLargeImageUrl = image.large_image_url = image.video_url;
                    } else {
                        this.currentLargeImageUrl = image.large_image_url;

                        this.currentOriginalImageUrl = image.original_image_url;
                    }

                    if ($(window).width() > 580 && image.original_image_url) {
                        $('img#pro-img').data('zoom-image', image.original_image_url).ezPlus();
                    }
                },

                moveThumbs: function(direction) {
                    let len = this.thumbs.length;

                    if (direction === "top") {
                        const moveThumb = this.thumbs.splice(len - 1, 1);

                        this.thumbs = [moveThumb[0]].concat((this.thumbs));

                        this.counter.up = this.counter.up+1;

                        this.counter.down = this.counter.down-1;

                    } else {
                        const moveThumb = this.thumbs.splice(0, 1);

                        this.thumbs = [].concat((this.thumbs), [moveThumb[0]]);

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
            if ($(window).width() > 580) {
                $('img#pro-img').data('zoom-image', $('img#pro-img').data('image')).ezPlus();
            }

            var wishlist = " <?php echo $wishListHelper->getWishlistProduct($product);  ?> ";

            $(document).mousemove(function(event) {
                if ($('.add-to-wishlist').length || wishlist != 0) {
                    if (event.pageX > $('.add-to-wishlist').offset().left && event.pageX < $('.add-to-wishlist').offset().left+32 && event.pageY > $('.add-to-wishlist').offset().top && event.pageY < $('.add-to-wishlist').offset().top+32) {

                        $(".zoomContainer").addClass("show-wishlist");

                    } else {
                        $(".zoomContainer").removeClass("show-wishlist");
                    }
                };

                if ($("body").hasClass("rtl")) {
                    $(".zoomWindow").addClass("zoom-image-direction");
                } else {
                    $(".zoomWindow").removeClass("zoom-image-direction");
                }
            });
        })
    </script>

@endpush