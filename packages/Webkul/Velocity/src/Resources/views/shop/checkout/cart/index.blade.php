@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@section('content-wrapper')
    <cart-component></cart-component>
@endsection

@push('css')
    <style type="text/css">
        .quantity {
            width: unset;
            float: right;
        }
    </style>
@endpush

@push('scripts')
    @include('shop::checkout.cart.coupon')

    <script type="text/x-template" id="cart-template">
        <div class="container">
            <section class="cart-details row no-margin col-12">
                <h1 class="fw6 col-12">{{ __('shop::app.checkout.cart.title') }}</h1>

                @if ($cart)
                    <div class="cart-details-header col-lg-6 col-md-12">
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
                                action="{{ route('shop.checkout.cart.update') }}"
                                method="POST"
                                @submit.prevent="onSubmit">

                                <div class="cart-item-list">
                                    @csrf

                                    @foreach ($cart->items as $key => $item)

                                        @php
                                            $productBaseImage = $item->product->getTypeInstance()->getBaseImage($item);
                                            $product = $item->product;

                                            $productPrice = $product->getTypeInstance()->getProductPrices();

                                        @endphp

                                        <div class="row col-12" v-if="!isMobileDevice">
                                            <a
                                                title="{{ $product->name }}"
                                                class="product-image-container col-2"
                                                href="{{ route('shop.productOrCategory.index', $product->url_key) }}">

                                                <img
                                                    class="card-img-top"
                                                    alt="{{ $product->name }}"
                                                    src="{{ $productBaseImage['large_image_url'] }}"
                                                    :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`">
                                            </a>

                                            <div class="product-details-content col-7 pr0">
                                                <div class="row item-title no-margin">
                                                    <a
                                                        href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
                                                        title="{{ $product->name }}"
                                                        class="unset col-12 no-padding">

                                                        <span class="fs20 fw6 link-color">{{ $product->name }}</span>
                                                    </a>
                                                </div>

                                                @if (isset($item->additional['attributes']))
                                                    @foreach ($item->additional['attributes'] as $attribute)
                                                        <div class="row col-12 no-padding no-margin display-block">
                                                            <label class="no-margin">
                                                                {{ $attribute['attribute_name'] }} :
                                                            </label>
                                                            <span>
                                                                {{ $attribute['option_label'] }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <div class="row col-12 no-padding no-margin">
                                                    @include ('shop::products.price', ['product' => $product])
                                                </div>

                                                @php
                                                    $moveToWishlist = trans('shop::app.checkout.cart.move-to-wishlist');
                                                @endphp

                                                <div class="no-padding col-12 cursor-pointer fs16">
                                                    @auth('customer')
                                                        @if ($item->parent_id != 'null' ||$item->parent_id != null)
                                                            @include('shop::products.wishlist', [
                                                                'route' => route('shop.movetowishlist', $item->id),
                                                                'text' => "<span class='align-vertical-super'>$moveToWishlist</span>"
                                                            ])
                                                        @else
                                                            @include('shop::products.wishlist', [
                                                                'route' => route('shop.movetowishlist', $item->child->id),
                                                                'text' => "<span class='align-vertical-super'>$moveToWishlist</span>"
                                                            ])
                                                        @endif
                                                    @endauth

                                                    @guest('customer')
                                                        @include('shop::products.wishlist', [
                                                            'isMoveToWishlist' => route('shop.checkout.cart.remove', ['id' => $item->id]),
                                                            'text' => "<span class='align-vertical-top'>$moveToWishlist</span>"
                                                        ])
                                                    @endguest

                                                    <a
                                                        class="unset
                                                            @auth('customer')
                                                                ml10
                                                            @endauth
                                                        "
                                                        href="{{ route('shop.checkout.cart.remove', ['id' => $item->id]) }}"
                                                        onclick="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')">

                                                        <span class="rango-delete fs24"></span>
                                                        <span class="align-vertical-top">{{ __('shop::app.checkout.cart.remove') }}</span>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="product-quantity col-2 no-padding">
                                                <quantity-changer
                                                    :control-name="'qty[{{$item->id}}]'"
                                                    quantity="{{ $item->quantity }}">
                                                </quantity-changer>
                                            </div>

                                            <div class="product-price fs18 col-1">
                                                <span class="card-current-price fw6 mr10">
                                                    {{ core()->currency( $item->base_total) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row col-12" v-else>
                                            <a
                                                title="{{ $product->name }}"
                                                class="product-image-container col-2"
                                                href="{{ route('shop.productOrCategory.index', $product->url_key) }}">

                                                <img
                                                    src="{{ $productBaseImage['medium_image_url'] }}"
                                                    class="card-img-top"
                                                    alt="{{ $product->name }}">
                                            </a>

                                            <div class="col-10 pr0 item-title">
                                                <a
                                                    href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
                                                    title="{{ $product->name }}"
                                                    class="unset col-12 no-padding">

                                                    <span class="fs20 fw6 link-color">{{ $product->name }}</span>
                                                </a>

                                                @if (isset($item->additional['attributes']))
                                                    <div class="row col-12 no-padding no-margin">

                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                        @endforeach

                                                    </div>
                                                @endif

                                                <div class="col-12 no-padding">
                                                    @include ('shop::products.price', ['product' => $product])
                                                </div>

                                                <div class="row col-12 remove-padding-margin actions">
                                                    <div class="product-quantity col-lg-4 col-6 no-padding">
                                                        <quantity-changer
                                                            :control-name="'qty[{{$item->id}}]'"
                                                            quantity="{{ $item->quantity }}">
                                                        </quantity-changer>
                                                    </div>

                                                    <div class="col-4 cursor-pointer text-down-4">
                                                        <a href="{{ route('shop.checkout.cart.remove', ['id' => $item->id]) }}" class="unset">
                                                            <i class="material-icons fs24">delete</i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    @endforeach
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}
                                    <a
                                        class="col-12 link-color remove-decoration fs16 no-padding"
                                        href="{{ route('shop.home.index') }}">
                                        {{ __('shop::app.checkout.cart.continue-shopping') }}
                                    </a>

                                    <button
                                        type="submit"
                                        class="theme-btn light mr15 pull-right unset">

                                        {{ __('shop::app.checkout.cart.update-cart') }}
                                    </button>
                                {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}
                            </form>
                        </div>

                        @include ('shop::products.view.cross-sells')
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

                    @if ($cart)
                        <div class="col-lg-4 col-md-12 offset-lg-2 row order-summary-container">
                            @include('shop::checkout.total.summary', ['cart' => $cart])

                            <coupon-component></coupon-component>
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
                }
            })

            function removeLink(message) {
                if (!confirm(message))
                event.preventDefault();
            }
        })()
    </script>
@endpush