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
                                    <span class="main-price">
                                        {{ $item->price }}
                                    </span>
                                    <span class="real-price">
                                        $25.00
                                    </span>
                                    <span class="discount">
                                        10% Off
                                    </span>
                                </div>

                                <div class="summary" >
                                    Color : Gray, Size : S
                                </div>

                                <div class="misc">
                                    <div class="qty-text">Quantity</div>
                                    <div class="box">{{ $item->quantity }}</div>
                                    <span class="remove">Remove</span>
                                    <span class="towishlist">Move to Wishlist</span>
                                </div>
                            </div>

                        </div>
                    @endforeach

                    <div class="misc-controls">
                        <span>Continue Shopping</span>
                        <button class="btn btn-lg btn-primary">PROCEED TO CHECKOUT</button>
                    </div>
                </div>

                <div class="right-side">
                    <div class="price-section">
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

                    </div>

                </div>

            </div>
        
        @else

            <div class="title">
                {{ __('shop::app.checkout.cart.empty') }}
            </div>

        @endif
    </section>
    
@endsection