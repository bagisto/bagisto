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

                    <div class="cart-item-list" style="margin-top: 0">
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
                        
                                        <div class="summary" >
                                            @foreach (cart()->getItemAttributeOptionDetails($item) as $key => $option)

                                                {{ (!$key ? '' : ' , ') . $option['attribute_name'] . ' : ' . $option['option_label'] }}
                                            
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="misc">
                                        <div class="qty-text">Quantity</div>
                                        <div class="box">{{ $item->quantity }}</div>
                                        <span class="remove">Remove</span>
                                        <span class="towishlist">Move to Wishlist</span>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>
                    

                    <div class="misc-controls">
                        <a href="{{ route('shop.home.index') }}" class="link">{{ __('shop::app.checkout.cart.continue-shopping') }}</a>

                        <a href="{{ route('shop.checkout.onepage.index') }}" class="btn btn-lg btn-primary">
                            {{ __('shop::app.checkout.cart.proceed-to-checkout') }}
                        </a>
                    </div>
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