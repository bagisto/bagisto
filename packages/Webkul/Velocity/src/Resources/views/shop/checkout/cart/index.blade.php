@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@section('content-wrapper')
    <cart-component></cart-component>
@endsection

@push('css')
    <style type="text/css">
        @media only screen and (max-width: 600px) {
            .rango-delete {
                margin-left: -10px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    @include('shop::checkout.cart.coupon')

    <script type="text/x-template" id="cart-template">
        <div class="container">
            <section class="cart-details row no-margin col-12">
                <h2 class="fw6 col-12">{{ __('shop::app.checkout.cart.title') }}</h2>
                @if ($cart)
                    <div class="cart-details-header col-lg-7 col-md-12">
                        <div class="row cart-header col-12 no-padding">
                            <span class="col-8 fw6 fs16 pr0">
                                {{ __('velocity::app.checkout.items') }}
                            </span>

                            <span class="col-2 fw6 fs16 no-padding text-right">
                                {{ __('velocity::app.checkout.qty') }}
                            </span>

                            <span class="col-2 fw6 fs16 text-right pr0">
                                {{ __('velocity::app.checkout.subtotal') }}
                            </span>
                        </div>

                        <div class="cart-content col-12">
                            <form
                                method="POST"
                                @submit.prevent="onSubmit"
                                action="{{ route('shop.checkout.cart.update') }}">

                                <div class="cart-item-list">
                                    @csrf

                                    @php
                                        $showUpdateCartButton = false;
                                    @endphp

                                    @foreach ($cart->items as $key => $item)
                                        @php
                                            $productBaseImage = $item->product->getTypeInstance()->getBaseImage($item);

                                            $product = $item->product;

                                            $productPrice = $product->getTypeInstance()->getProductPrices();

                                            if ($product->getTypeInstance()->showQuantityBox()) {
                                                $showUpdateCartButton = true;
                                            }
                                                
                                            if (is_null ($product->url_key)) {
                                                if (! is_null($product->parent)) {
                                                    $url_key = $product->parent->url_key;
                                                }
                                            } else {
                                                $url_key = $product->url_key;
                                            }
                                        @endphp

                                        <div class="row col-12">
                                            <a
                                                title="{{ $product->name }}"
                                                class="product-image-container col-2"
                                                href="{{ route('shop.productOrCategory.index', $url_key) }}">

                                                <img
                                                    class="card-img-top"
                                                    alt="{{ $product->name }}"
                                                    src="{{ $productBaseImage['large_image_url'] }}"
                                                    :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`">
                                            </a>

                                            <div class="product-details-content col-6 pr0">
                                                <div class="row item-title no-margin">
                                                    <a
                                                        href="{{ route('shop.productOrCategory.index', $url_key) }}"
                                                        title="{{ $product->name }}"
                                                        class="unset col-12 no-padding">

                                                        <span class="fs20 fw6 link-color">{{ $product->name }}</span>
                                                    </a>
                                                </div>

                                                @if (isset($item->additional['attributes']))
                                                    @foreach ($item->additional['attributes'] as $attribute)
                                                        <div class="row col-12 no-padding no-margin display-block item-attribute">
                                                            <label class="no-margin">
                                                                {{ $attribute['attribute_name'] }}:
                                                            </label>
                                                            <span>
                                                                {{ $attribute['option_label'] }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <div class="row col-12 no-padding no-margin item-price">
                                                    <div class="product-price">
                                                        <span>{{ core()->currency($item->base_price) }}</span>
                                                    </div>
                                                </div>

                                                @php
                                                    $moveToWishlist = trans('shop::app.checkout.cart.move-to-wishlist');
                                                @endphp

                                                <div class="no-padding cursor-pointer fs16 item-actions">
                                                    @auth('customer')
                                                        @if ((bool) core()->getConfigData('general.content.shop.wishlist_option'))
                                                            @if (
                                                                $item->parent_id != 'null'
                                                                || $item->parent_id != null
                                                            )
                                                                <div class="d-inline-block">
                                                                    @include('shop::products.wishlist', [
                                                                        'route' => route('shop.move_to_wishlist', $item->id),
                                                                        'text' => "<span class='align-vertical-super'>$moveToWishlist</span>"
                                                                    ])
                                                                </div>
                                                            @else
                                                                <div class="d-inline-block">
                                                                    @include('shop::products.wishlist', [
                                                                        'route' => route('shop.move_to_wishlist', $item->child->id),
                                                                        'text' => "<span class='align-vertical-super'>$moveToWishlist</span>"
                                                                    ])
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endauth

                                                    <div class="d-inline-block">
                                                        <a
                                                            class="unset"
                                                            href="{{ route('shop.checkout.cart.remove', ['id' => $item->id]) }}"
                                                            @click="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')">

                                                            <span class="rango-delete mt-0 fs24"></span>
                                                            <span class="align-vertical-super">{{ __('shop::app.checkout.cart.remove') }}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-quantity col-3 no-padding">
                                                @if ($item->product->getTypeInstance()->showQuantityBox() === true)
                                                    <quantity-changer
                                                        :control-name="'qty[{{$item->id}}]'"
                                                        quantity="{{ $item->quantity }}"
                                                        quantity-text="{{ __('shop::app.products.quantity') }}">
                                                    </quantity-changer>
                                                @else
                                                    <p class="fw6 fs16 no-padding text-center ml15">--</p>
                                                @endif
                                            </div>

                                            <div class="product-price fs18 col-1">
                                                <span class="card-current-price fw6 mr10">
                                                    {{ core()->currency( $item->base_total) }}
                                                </span>
                                            </div>

                                            @if (! cart()->isItemHaveQuantity($item))
                                                <div class="control-error mt-4 fs16 fw6">
                                                    * {{ __('shop::app.checkout.cart.quantity-error') }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}
                                <div class="misc">
                                    <a
                                        class="theme-btn light fs16 text-center"
                                        href="{{ route('shop.home.index') }}">
                                        {{ __('shop::app.checkout.cart.continue-shopping') }}
                                    </a>

                                    <form
                                        method="POST"
                                        @submit.prevent="onSubmit"
                                        action="{{ route('velocity.cart.remove.all.items') }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            onclick="return confirm('{{ __('shop::app.checkout.cart.confirm-action') }}')"
                                            class="theme-btn light unset">

                                            {{ __('shop::app.checkout.cart.remove-all-items') }}
                                        </button>
                                    </form>

                                    @if ($showUpdateCartButton)
                                        <button
                                            type="submit"
                                            class="theme-btn light unset">

                                            {{ __('shop::app.checkout.cart.update-cart') }}
                                        </button>
                                    @endif
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}
                            </form>
                        </div>

                        <wishlist-recent-orders></wishlist-recent-orders>

                        @include ('shop::products.view.cross-sells')
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

                    @if ($cart)
                        <div class="col-lg-4 col-md-12 offset-lg-1 row order-summary-container">
                            @include('shop::checkout.total.summary', ['cart' => $cart])
                            
                            <coupon-component></coupon-component>

                            @if ($cart->items->first()->product->categories->first())
                                @php $categoryId = $cart->items->random(1)->first()->product->categories->first()->id??2 @endphp

                            @elseif ($wishlistItems && ! empty($wishlistItems->first()))
                                @php $categoryId = $wishlistItems->random(1)->first()->product->categories->first()->id??2 @endphp

                            @elseif (! empty($orderItems->first()))
                                @php $categoryId = $orderItems->random(1)->first()->product->categories->first()->id??2 @endphp
                            
                            @elseif (! empty($topSellingProducts->first()))
                                @php $categoryId =$topSellingProducts->random(1)->first()->product->categories->first()->id??2 @endphp
                            @endif

                            <related-products category-id='{{ $categoryId??2 }}'></related-products>
                        </div>
                    @else
                        <div class="fs16 col-12 empty-cart-message">
                            {{ __('shop::app.checkout.cart.empty') }}
                        </div>

                        <a
                            class="fs16 mt15 col-12 remove-decoration continue-shopping"
                            href="{{ route('shop.home.index') }}">

                            <button type="button" class="theme-btn remove-decoration">
                                {{ __('shop::app.checkout.cart.continue-shopping') }}
                            </button>
                        </a>
                    @endif

                {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}
            </section>
        </div>
    </script>

    <script type="text/javascript" id="cart-template">
        (() => {
            Vue.component('cart-component', {
                template: '#cart-template',

                data: function () {
                    return {
                        isMobileDevice: this.isMobile(),
                    }
                },

                methods: {
                    removeLink(message) {
                        if (! confirm(message)) {
                            event.preventDefault();
                        }
                    }
                }
            })
        })();
    </script>

    <script type="text/x-template" id='wishlist-recent-orders-template'>
        <div>
            <div class="carousel-products" v-if='{{count($wishlistItems)}} != 0'>
                <div class="customer-orders">
                    <h2 class="fs20 fw6">{{ __('shop::app.home.wishlist') }}</h2>
                </div>

                <carousel-component
                    :slides-per-page="slidesPerPage"
                    navigation-enabled="show"
                    paginationEnabled="hide"
                    :slides-count="{{count($wishlistItems)}}">

                    @foreach($wishlistItems as $key => $productItem)
                        <slide slot="slide-{{ $key }}">
                            @include ('shop::products.list.card', [
                                'product' => $productItem->product,
                                'addToCartBtnClass' => 'small-padding',
                            ])
                        </slide>
                    @endforeach
                </carousel-component>
            </div>

            <div class="carousel-products recent-history" v-if='{{count($orderItems)}} != 0'>
                <div class="customer-orders">
                    <h2 class="fs20 fw6">{{ __('shop::app.home.recent-item') }}</h2>
                </div>

                <carousel-component
                    :slides-per-page="slidesPerPage"
                    navigation-enabled="show"
                    paginationEnabled="hide"
                    :slides-count="{{count($orderItems)}}">

                    @foreach($orderItems as $key => $orderItem)
                        <slide slot="slide-{{ $key }}">
                            @include ('shop::products.list.card', [
                                'product' => $orderItem->product,
                                'addToCartBtnClass' => 'small-padding',
                            ])
                        </slide>
                    @endforeach
                </carousel-component>
            </div>

            <div class="carousel-products recent-history" v-if='{{count($topSellingProducts)}} != 0'>
                <div class="customer-orders">
                    <h2 class="fs20 fw6">{{ __('shop::app.home.top-selling-product') }}</h2>
                </div>

                <carousel-component
                    :slides-per-page="slidesPerPage"
                    navigation-enabled="show"
                    paginationEnabled="hide"
                    :slides-count="{{count($topSellingProducts)}}">

                    @foreach($topSellingProducts as $key => $topSellingProduct)
                        <slide slot="slide-{{ $key }}">
                            @include ('shop::products.list.card', [
                                'product' => $topSellingProduct->product,
                                'addToCartBtnClass' => 'small-padding',
                            ])
                        </slide>
                    @endforeach
                </carousel-component>
            </div>
        </div>
    </script>

    <script>
        Vue.component('wishlist-recent-orders', {
            template: "#wishlist-recent-orders-template",

            data: function () {
                return {
                    'currentScreen': window.innerWidth,
                    'slidesPerPage': 5,
                }
            },

            created: function () {
                this.setSlidesPerPage(this.currentScreen);
            },

            methods: {
                setSlidesPerPage: function (width) {
                    if (width >= 1200) {
                        this.slidesPerPage = 3;
                    } else if (width < 1200 && width >= 770) {
                        this.slidesPerPage = 2;
                    } else if (width < 770 && width >= 500) {
                        this.slidesPerPage = 3;
                    } else {
                        this.slidesPerPage = 1;
                    }
                }
            }
        })
    </script>

    <script type="text/x-template" id='related-products-template'>
        <div>
            <div v-if='this.$root.products.length'>
                <div class="row remove-padding-margin">`
                    <div class="col-12 no-padding">
                        <h2 class="fs20 fw6 mb15 mt-5">
                            {{ __('shop::app.checkout.cart.product-related') }}
                        </h2>
                    </div>
                </div>

                <div :class="`recently-viewed-products-wrapper`">
                    <div class="row small-card-container related-product" v-for='(product, index) in this.$root.products' v-if='index < 5'>
                        <div class="col-2 product-image-container mr15">
                            <a :href="`${baseUrl}/${product.slug}`" class="unset">
                                <div class="product-image" :style="`background-image: url(${product.image})`">
                                </div>
                            </a>
                        </div>
                        <div class="col-10 no-padding card-body align-vertical-top">
                            <a :href="`${baseUrl}/${product.slug}`" class="unset no-padding">
                                <div class="product-name">
                                    <span class="fs16 text-nowrap" v-text='product.name'></span>
                                </div>

                                <div
                                    v-html="product.priceHTML"
                                    class="fs18 card-current-price fw6">
                                </div>

                                <star-ratings v-if="product.avgRating > 0"
                                    push-class="display-inbl"
                                    :ratings="product.avgRating">
                                </star-ratings>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('related-products', {
            template: "#related-products-template",

            props: ['categoryId'],

            mounted() {
                this.$root.getCategoryProducts(this.categoryId);
            }
        })
    </script>
@endpush
