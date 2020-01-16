@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@section('content-wrapper')
    <div class="container">
        <section class="cart-details row offset-1">
            <h1 class="fw6 row col-12">{{ __('shop::app.checkout.cart.title') }}</h1>

            <div class="cart-details-header row col-6">
                {{-- TODO:- USE table insted of column --}}
                @if ($cart)
                    <div class="row cart-header col-12 no-padding">
                        <span class="col-8 fw6 fs16 pr0">
                            {{ __('velocity::app.checkout.items') }}
                        </span>

                        <span class="col-2 fw6 fs16 no-padding text-center">
                            {{ __('velocity::app.checkout.qty') }}
                        </span>

                        <span class="col-2 fw6 fs16 text-right pr0">
                            {{ __('velocity::app.checkout.subtotal') }}
                        </span>
                    </div>
                @endif

                @if ($cart)
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

                                    <div class="row col-12">
                                        <a
                                            href="{{ route('shop.productOrCategory.index', ['slug' => $product->url_key]) }}"
                                            title="{{ $product->name }}"
                                            class="product-image-container col-2">

                                            <img
                                                src="{{ $productBaseImage['medium_image_url'] }}"
                                                class="card-img-top"
                                                alt="{{ $product->name }}">
                                        </a>

                                        <div class="product-details-content col-6">
                                            <div class="row">
                                                <a
                                                    href="{{ route('shop.productOrCategory.index', ['slug' => $product->url_key]) }}"
                                                    title="{{ $product->name }}"
                                                    class="unset col-12">

                                                    <span class="fs20 fw6 link-color">{{ $product->name }}</span>
                                                </a>
                                            </div>

                                            <div class="row col-12">
                                                @include ('shop::products.price', ['product' => $product])
                                            </div>

                                            <div class="row col-12 cursor-pointer">
                                                <a href="{{ route('shop.checkout.cart.remove', ['id' => $item->id]) }}" class="unset">
                                                    <span class="rango-delete fs24"></span>
                                                    <span class="align-vertical-top">{{ __('shop::app.checkout.cart.remove') }}</span>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="product-quantity col-3 no-padding">
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
                @endif
            </div>

            {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

                @if ($cart)
                    <div class="col-4 offset-1 row order-summary-container">
                        @include('shop::checkout.total.summary', ['cart' => $cart])

                        <coupon-component></coupon-component>
                    </div>
                @else
                    <div class="fs16 row col-12">
                        {{ __('shop::app.checkout.cart.empty') }}
                    </div>

                    <a
                        class="fs16 mt15 col-12 row remove-decoration"
                        href="{{ route('shop.home.index') }}">

                        <button type="button" class="theme-btn remove-decoration">
                            {{ __('shop::app.checkout.cart.continue-shopping') }}
                        </button>
                    </a>
                @endif

            {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

        </section>
    </div>
@endsection

@push('scripts')
    @include('shop::checkout.cart.coupon')

    <script type="text/javascript">
        function removeLink(message) {
            if (!confirm(message))
            event.preventDefault();
        }

        function updateCartQunatity(operation, index) {
            var quantity = document.getElementById('cart-quantity'+index).value;

            if (operation == 'add') {
                quantity = parseInt(quantity) + 1;
            } else if (operation == 'remove') {
                if (quantity > 1) {
                    quantity = parseInt(quantity) - 1;
                } else {
                    alert('{{ __('shop::app.products.less-quantity') }}');
                }
            }
            document.getElementById('cart-quantity'+index).value = quantity;
            event.preventDefault();
        }
    </script>
@endpush