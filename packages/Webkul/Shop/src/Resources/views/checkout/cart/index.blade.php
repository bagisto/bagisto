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

                    <!--<div class="price-section">
                        <div class="title">
                            Price Detail
                        </div>
                        <div class="all-item-details">
                            @foreach($cart->items as $item)
                                <div class="item-details">
                                    <span class="name">{{ $item->product->name }}</span>
                                    <span class="price">$ {{ $item->price }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="horizontal-rule"></div>

                        <div class="total-details">
                            <span class="name">Amount Payable</span>
                            <span class="amount">$75.00</span>
                        </div>

                    </div>

                    <div class="coupon-section">

                        <span class="title">Apply Coupon</span>

                        <div class="control-group">
                            <input type="text" class="control coupon-input" placeholder="Coupon Code" />
                        </div>

                        <button class="btn btn-md btn-primary">Apply</button>

                        <div class="coupon-details">
                            <div class="title">Coupon Used</div>
                            <div class="coupon">
                                <span class="name">Coupon 1</span>
                                <span class="discount">$15</span>
                            </div>
                            <div class="coupon">
                                <span class="name">Coupon 2</span>
                                <span class="discount">$5</span>
                            </div>
                        </div>

                        <div class="horizontal-rule"></div>

                        <div class="after-coupon-amount">
                            <span class="name">Amount Payable</span>
                            <span class="amount">$75.00</span>
                        </div>

                    </div>-->

                </div>

            </div>
        
        @else

            <div class="title">
                {{ __('shop::app.checkout.cart.empty') }}
            </div>

        @endif
    </section>
    
@endsection