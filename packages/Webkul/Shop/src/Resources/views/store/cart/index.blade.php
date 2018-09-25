@extends('shop::layouts.master')
@section('content-wrapper')
    <section class="cart">
        <div class="title">
            Shopping Cart
        </div>

        <div class="cart-content">
            {{-- {{ dd($products) }} --}}

            <div class="left-side">

                @foreach($products as $product)
                    <div class="item">
                        <div style="margin-right: 15px;">
                            <img class="item-image" src="{{ bagisto_asset("$product[2]") }}" />
                        </div>

                        <div class="item-details">

                            <div class="item-title">
                                {{$product[0]}}
                            </div>

                            <div class="price">
                                <span class="main-price">
                                    {{$product[1]}}
                                </span>
                                <span class="real-price">
                                    $25.00
                                </span>
                                <span class="discount">
                                    10% Off
                                </span>
                            </div>

                            <div class="summary" >
                                Color : Gray, Size : S, Sleeve type : Puffed Sleeves, Occasion : Birthday,  Marriage Anniversary
                            </div>

                            <div class="misc">
                                <div class="qty-text">Quantity</div>
                                <div class="box">{{ $product[3] }}</div>
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
                        @foreach($products as $product)
                            <div class="item-details">
                                <span class="name">{{ $product[0] }}</span>
                                <span class="price">$ {{ $product[1] }}</span>
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
    </section>

    <div class="related-products-wrapper">

        <div class="title">
            We found other products you might like!
        </div>

        <div class="horizontal-rule"></div>

        <div class="related-products">

            <div class="product-card">
                <div class="product-image">
                    <img src="{{ bagisto_asset('images/grid.png') }}" />
                </div>
                <div class="product-name">
                    <span>Red Black Tees</span>
                </div>
                <div class="product-price">
                    <span>$65.00</span>
                </div>
                <div class="product-ratings">
                    <span>
                        <img src="{{ bagisto_asset('images/5star.svg') }}" />
                    </span>
                </div>
                <div class="cart-fav-seg">
                    <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                    <span><img src="{{ bagisto_asset('images/wishadd.svg') }}" /></span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ bagisto_asset('images/grid.png') }}" />
                </div>
                <div class="product-name">
                    <span>Red Black Tees</span>
                </div>
                <div class="product-price">
                    <span>$65.00</span>
                </div>
                <div class="product-ratings">
                    <span>
                        <img src="{{ bagisto_asset('images/5star.svg') }}" />
                    </span>
                </div>
                <div class="cart-fav-seg">
                    <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                    <span><img src="{{ bagisto_asset('images/wishadd.svg') }}" /></span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ bagisto_asset('images/grid.png') }}" />
                </div>
                <div class="product-name">
                    <span>Red Black Tees</span>
                </div>
                <div class="product-price">
                    <span>$65.00</span>
                </div>
                <div class="product-ratings">
                    <span>
                        <img src="{{ bagisto_asset('images/5star.svg') }}" />
                    </span>
                </div>
                <div class="cart-fav-seg">
                    <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                    <span><img src="{{ bagisto_asset('images/wishadd.svg') }}" /></span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ bagisto_asset('images/grid.png') }}" />
                </div>
                <div class="product-name">
                    <span>Red Black Tees</span>
                </div>
                <div class="product-price">
                    <span>$65.00</span>
                </div>
                <div class="product-ratings">
                    <span>
                        <img src="{{ bagisto_asset('images/5star.svg') }}" />
                    </span>
                </div>
                <div class="cart-fav-seg">
                    <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                    <span><img src="{{ bagisto_asset('images/wishadd.svg') }}" /></span>
                </div>
            </div>
        </div>
    </div>
@endsection