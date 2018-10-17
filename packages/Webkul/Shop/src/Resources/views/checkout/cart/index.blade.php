@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@section('content-wrapper')

    @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

    <section class="cart">

        @if ($cart)

            <div class="title">
                {{ __('shop::app.checkout.cart.title') }}
            </div>

            <div class="cart-content">

                <div class="left-side">
                    <form action="{{ route('shop.checkout.cart.update') }}" method="POST" @submit.prevent="onSubmit">

                        <div class="cart-item-list" style="margin-top: 0">
                            @csrf
                            @foreach($cart->items as $item)

                                <?php
                                    $product = $item->product;
                                    $productBaseImage = $productImageHelper->getProductBaseImage($product);
                                ?>

                                <div class="item">
                                    <div style="margin-right: 15px;">
                                        <a href="{{ url()->to('/').'/products/'.$product->url_key }}"><img class="item-image" src="{{ $productBaseImage['medium_image_url'] }}" /></a>
                                    </div>

                                    <div class="item-details">

                                        <div class="item-title">
                                            <a href="{{ url()->to('/').'/products/'.$product->url_key }}">{{ $product->name }}</a>
                                        </div>

                                        <div class="price">
                                            {{ core()->currency($item->base_price) }}
                                        </div>

                                        @if ($product->type == 'configurable')

                                            <div class="summary">

                                                {{ Cart::getItemAttributeOptionDetails($item)['html'] }}

                                            </div>
                                        @endif

                                        <div class="misc">
                                            <div class="qty-text" :class="[errors.has('qty') ? 'has-error' : '']">{{ __('shop::app.checkout.cart.quantity.quantity') }}</div>

                                            <input class="box" type="text" class="control" v-validate="'required|numeric|min_value:1'" name="qty[{{$item->id}}]" value="{{ $item->quantity }}">

                                            <span class="control-error" v-if="errors.has('qty[{{$item->id}}]')">@{{ errors.first('qty') }}</span>

                                            <span class="remove"><a href="{{ route('shop.checkout.cart.remove', $item->id) }}">{{ __('shop::app.checkout.cart.remove-link') }}</a></span>

                                            <span class="towishlist">{{ __('shop::app.checkout.cart.move-to-wishlist') }}</span>
                                        </div>

                                        @if (!cart()->isItemHaveQuantity($item))
                                            <div class="error-message">
                                                {{ __('shop::app.checkout.cart.quantity-error') }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>
                        <div class="misc-controls">
                            <a href="{{ route('shop.home.index') }}" class="link">{{ __('shop::app.checkout.cart.continue-shopping') }}</a>

                            <div>
                                <input type="submit" class="btn btn-lg btn-primary" value="Update Cart" />

                                @if (!cart()->hasError())
                                    <a href="{{ route('shop.checkout.onepage.index') }}" class="btn btn-lg btn-primary">
                                        {{ __('shop::app.checkout.cart.proceed-to-checkout') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="right-side">
                    @include('shop::checkout.total.summary', ['cart' => $cart])
                </div>
            </div>
        @else
            <div class="title">
                {{ __('shop::app.checkout.cart.empty') }}
            </div>

        @endif
    </section>

@endsection