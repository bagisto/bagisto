@extends('shop::layouts.master')

@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    @php
        $images = product_image()->getGalleryImages($product);
        
        $productImages = [];

        foreach ($images as $key => $image) {
            array_push($productImages, $image['medium_image_url']);
        }

        $productBaseImage = product_image()->getProductBaseImage($product, $images);
    @endphp

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{{ $product->description }}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{{ $product->description }}" />

    <meta property="og:url" content="{{ route('shop.productOrCategory.index', $product->url_key) }}" />
@stop

@push('css')
    <style type="text/css">
        .related-products {
            width: 100%;
        }

        .recently-viewed {
            margin-top: 20px;
        }

        .store-meta-images > .recently-viewed:first-child {
            margin-top: 0px;
        }

        .main-content-wrapper {
            margin-bottom: 0px;
        }

        .buynow {
            height: 40px;
            text-transform: uppercase;
        }
    </style>
@endpush

@section('full-content-wrapper')
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <div class="row no-margin">
        <section class="col-12 product-detail">
            <div class="layouter">
                <product-view>
                    <div class="form-container">
                        @csrf()

                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="row">
                            {{-- product-gallery --}}
                            <div class="left col-lg-5 col-md-6">
                                @include ('shop::products.view.gallery')
                            </div>

                            {{-- right-section --}}
                            <div class="right col-lg-7 col-md-6">
                                {{-- product-info-section --}}
                                <div class="info">
                                    <h2 class="col-12">{{ $product->name }}</h2>

                                    @if ($total = $reviewHelper->getTotalReviews($product))
                                        <div class="reviews col-lg-12">
                                            <star-ratings
                                                push-class="mr5"
                                                :ratings="{{ round($reviewHelper->getAverageRating($product)) }}"
                                            ></star-ratings>

                                            <div class="reviews">
                                                <span>
                                                    {{ __('shop::app.reviews.ratingreviews', [
                                                        'rating' => round($reviewHelper->getAverageRating($product)),
                                                        'review' => $total])
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    @include ('shop::products.view.stock', ['product' => $product])

                                    <div class="col-12 price">
                                        @include ('shop::products.price', ['product' => $product])

                                        @if (
                                            Webkul\Tax\Helpers\Tax::isTaxInclusive()
                                            && $product->getTypeInstance()->getTaxCategory()
                                        )
                                            <span>
                                                {{ __('velocity::app.products.tax-inclusive') }}
                                            </span>
                                        @endif
                                    </div>

                                    @if (count($offers = $product->getTypeInstance()->getCustomerGroupPricingOffers()) > 0)
                                        <div class="col-12">
                                            @foreach ($offers as $offer)
                                                {{ $offer }} </br>
                                            @endforeach
                                        </div>
                                    @endif

                                    @include ('shop::products.view.configurable-options')

                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                    @if ($product->getTypeInstance()->showQuantityBox())
                                        <div class="col-12">
                                            <quantity-changer quantity-text="{{ __('shop::app.products.quantity') }}"></quantity-changer>
                                        </div>
                                    @else
                                        <input type="hidden" name="quantity" value="1">
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                    @include ('shop::products.view.downloadable')

                                    @include ('shop::products.view.grouped-products')

                                    @include ('shop::products.view.bundle-options')

                                    <div class="col-12 product-actions">
                                        @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                            @include ('shop::products.buy-now', [
                                                'product' => $product,
                                            ])
                                        @endif

                                        @include ('shop::products.add-to-cart', [
                                            'form' => false,
                                            'product' => $product,
                                            'showCartIcon' => false,
                                            'showCompare' => (bool) core()->getConfigData('general.content.shop.compare_option'),
                                        ])
                                    </div>
                                </div>

                                @include ('shop::products.view.short-description')

                                @include ('shop::products.view.attributes', [
                                    'active' => true
                                ])

                                {{-- product long description --}}
                                @include ('shop::products.view.description')

                                {{-- reviews count --}}
                                @include ('shop::products.view.reviews', ['accordian' => true])
                            </div>
                        </div>
                    </div>
                </product-view>
            </div>
        </section>

        <div class="related-products">
            @include('shop::products.view.related-products')
            
            @include('shop::products.view.up-sells')
        </div>
    </div>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

    <script type="text/javascript" src="{{ asset('themes/velocity/assets/js/jquery-ez-plus.js') }}"></script>

    <script type='text/javascript' src='https://unpkg.com/spritespin@4.1.0/release/spritespin.js'></script>

    <script type="text/x-template" id="product-view-template">
        <form
            method="POST"
            id="product-form"
            @click="onSubmit($event)"
            @submit.enter.prevent="onSubmit($event)"
            action="{{ route('shop.cart.add', $product->id) }}"
        >
            <input type="hidden" name="is_buy_now" v-model="is_buy_now">

            <slot v-if="slot"></slot>

            <div v-else>
                <div class="spritespin"></div>
            </div>
        </form>
    </script>

    <script>
        Vue.component('product-view', {
            inject: ['$validator'],
            template: '#product-view-template',
            data: function () {
                return {
                    slot: true,
                    is_buy_now: 0,
                }
            },

            mounted: function () {
                let currentProductId = '{{ $product->url_key }}';
                let existingViewed = window.localStorage.getItem('recentlyViewed');

                if (! existingViewed) {
                    existingViewed = [];
                } else {
                    existingViewed = JSON.parse(existingViewed);
                }

                if (existingViewed.indexOf(currentProductId) == -1) {
                    existingViewed.push(currentProductId);

                    if (existingViewed.length > 3)
                        existingViewed = existingViewed.slice(Math.max(existingViewed.length - 4, 1));

                    window.localStorage.setItem('recentlyViewed', JSON.stringify(existingViewed));
                } else {
                    var uniqueNames = [];

                    $.each(existingViewed, function(i, el){
                        if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
                    });

                    uniqueNames.push(currentProductId);

                    uniqueNames.splice(uniqueNames.indexOf(currentProductId), 1);

                    window.localStorage.setItem('recentlyViewed', JSON.stringify(uniqueNames));
                }
            },

            methods: {
                onSubmit: function(event) {
                    if (event.target.getAttribute('type') != 'submit')
                        return;

                    event.preventDefault();

                    this.$validator.validateAll().then(result => {
                        if (result) {
                            this.is_buy_now = event.target.classList.contains('buynow') ? 1 : 0;

                            setTimeout(function() {
                                document.getElementById('product-form').submit();
                            }, 0);
                        } else {
                            this.activateAutoScroll();
                        }
                    });
                },

                activateAutoScroll: function(event) {
                    
                    /**
                     * This is normal Element
                     */
                    const normalElement = document.querySelector(
                        '.control-error:first-of-type'
                    );

                    /**
                     * Scroll Config
                     */
                    const scrollConfig = {
                        behavior: 'smooth',
                        block: 'end',
                        inline: 'nearest',
                    }

                    if (normalElement) {
                        normalElement.scrollIntoView(scrollConfig);
                        return;
                    }
                }
            }
        });

        window.onload = function() {
            var thumbList = document.getElementsByClassName('thumb-list')[0];
            var thumbFrame = document.getElementsByClassName('thumb-frame');
            var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

            if (thumbList && productHeroImage) {
                for (let i=0; i < thumbFrame.length ; i++) {
                    thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                    thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                }

                if (screen.width > 720) {
                    thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.height = productHeroImage.offsetHeight + "px";
                }
            }

            window.onresize = function() {
                if (thumbList && productHeroImage) {

                    for(let i=0; i < thumbFrame.length; i++) {
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                        thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                    }

                    if (screen.width > 720) {
                        thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.height = productHeroImage.offsetHeight + "px";
                    }
                }
            }
        };
    </script>
@endpush