@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@section('content-wrapper')

    @inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

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
                                        <img class="item-image" src="{{ $productBaseImage['medium_image_url'] }}" />
                                    </div>

                                    <div class="item-details">

                                        <div class="item-title">
                                            {{ $product->name }}
                                        </div>

                                        <div class="price">
                                            {{ core()->currency($item->base_price) }}
                                        </div>

                                        @if ($product->type == 'configurable')

                                            <div class="summary">

                                                {{ Cart::getItemAttributeOptionDetails($item)['html'] }}

                                            </div>
                                        @endif

                                        <div class="misc" :class="[errors.has('quantity') ? 'has-error' : '']">
                                            <div class="qty-text">{{ __('shop::app.checkout.cart.quantity') }}</div>
                                            {{-- <div class="box">{{ $item->quantity }}</div> --}}
                                            <input class="box" type="text" v-validate="'required|numeric|min_value:1'" name="qty[{{$item->id}}]" value="{{ $item->quantity }}">
                                            <span class="control-error" v-if="errors.has('qty[{{$item->qty}}]')">@{{ errors.first('quantity') }}</span>
                                            {{-- @if($product->type == 'configurable')
                                                <span class="remove"><a href="{{ route('shop.checkout.cart.remove', $item->child->id) }}">{{ __('shop::app.checkout.cart.remove') }}</a></span>
                                            @else --}}
                                                <span class="remove"><a href="{{ route('shop.checkout.cart.remove', $item->id) }}">{{ __('shop::app.checkout.cart.remove') }}</a></span>
                                            {{-- @endif --}}
                                            <span class="towishlist">{{ __('shop::app.checkout.cart.move-to-wishlist') }}</span>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                        <div class="misc-controls">
                            <a href="{{ route('shop.home.index') }}" class="link">{{ __('shop::app.checkout.cart.continue-shopping') }}</a>

                            <div>
                                <input type="submit" class="btn btn-lg btn-primary" value="Update Cart" />
                                <a href="{{ route('shop.checkout.onepage.index') }}" class="btn btn-lg btn-primary">
                                    {{ __('shop::app.checkout.cart.proceed-to-checkout') }}
                                </a>
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