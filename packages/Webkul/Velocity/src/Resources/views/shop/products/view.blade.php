@extends('shop::layouts.master')

@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')

@php
    $total = $reviewHelper->getTotalReviews($product);

    $avgRatings = $reviewHelper->getAverageRating($product);
    $avgStarRating = ceil($avgRatings);
@endphp

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : str_limit(strip_tags($product->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $product->meta_keywords }}"/>
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

                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                            {{-- product-gallery --}}
                            <div class="left col-lg-5">
                                @include ('shop::products.view.gallery')
                            </div>

                            {{-- right-section --}}
                            <div class="right col-lg-7">
                                {{-- product-info-section --}}
                                <div class="row info">
                                    <h2 class="col-lg-12">{{ $product->name }}</h2>

                                    @if ($total)
                                        <div class="reviews col-lg-12">
                                            <star-ratings
                                                :ratings="{{ $avgStarRating }}"
                                                push-class="mr5"
                                            ></star-ratings>

                                            <div class="reviews">
                                                <span>
                                                    {{ __('shop::app.reviews.ratingreviews', [
                                                        'rating' => $avgRatings,
                                                        'review' => $total])
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    @include ('shop::products.view.stock', ['product' => $product])

                                    <div class="col-12 price">
                                        @include ('shop::products.price', ['product' => $product])
                                    </div>

                                    @include ('shop::products.add-to-cart', [
                                        'form' => false,
                                        'product' => $product,
                                        'showCompare' => true,
                                        'showCartIcon' => false,
                                    ])
                                </div>

                                {!! view_render_event('bagisto.shop.products.view.short_description.before', ['product' => $product]) !!}

                                @if ($product->short_description)
                                    <div class="description">
                                        <h3 class="col-lg-12">{{ __('velocity::app.products.short-description') }}</h3>

                                        {!! $product->short_description !!}
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.short_description.after', ['product' => $product]) !!}


                                {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                @if ($product->getTypeInstance()->showQuantityBox())
                                    <div>
                                        <quantity-changer></quantity-changer>
                                    </div>
                                @else
                                    <input type="hidden" name="quantity" value="1">
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                @include ('shop::products.view.configurable-options')

                                @include ('shop::products.view.downloadable')

                                @include ('shop::products.view.grouped-products')

                                @include ('shop::products.view.bundle-options')

                                @include ('shop::products.view.attributes', [
                                    'active' => true
                                ])

                                {{-- product long description --}}
                                @include ('shop::products.view.description')

                                {{-- reviews count --}}
                                @include ('shop::products.view.reviews', ['accordian' => true])
                            </div>
                        </div>
                    </product-view>
                </div>
            </section>

            <div class="related-products">
                @include('shop::products.view.related-products')
                @include('shop::products.view.up-sells')
            </div>

            <div class="store-meta-images col-3">
                @if(
                    isset($velocityMetaData['product_view_images'])
                    && $velocityMetaData['product_view_images']
                )
                    @foreach (json_decode($velocityMetaData['product_view_images'], true) as $image)
                        @if ($image && $image !== '')
                            <img src="{{ url()->to('/') }}/storage/{{ $image }}" />
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
@endsection

@push('scripts')
    <script type="text/x-template" id="product-view-template">
        <form method="POST" id="product-form" action="{{ route('cart.add', $product->product_id) }}" @click="onSubmit($event)">
            <input type="hidden" name="is_buy_now" v-model="is_buy_now">
            <slot></slot>
        </form>
    </script>

    <script type="text/javascript">
        Vue.component('product-view', {
            inject: ['$validator'],
            template: '#product-view-template',
            data: function() {
                return {
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
                onSubmit: function(e) {
                    if (e.target.getAttribute('type') != 'submit')
                        return;

                    e.preventDefault();

                    var this_this = this;

                    this.$validator.validateAll().then(function (result) {
                        if (result) {
                            this_this.is_buy_now = e.target.classList.contains('buynow') ? 1 : 0;

                            setTimeout(function() {
                                document.getElementById('product-form').submit();
                            }, 0);
                        }
                    });
                }
            }
        });

        $(document).ready(function() {
            var addTOButton = document.getElementsByClassName('add-to-buttons')[0];
            // document.getElementById('loader').style.display="none";
            // addTOButton.style.display="flex";
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