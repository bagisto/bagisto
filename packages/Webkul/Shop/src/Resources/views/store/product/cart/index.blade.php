@extends('shop::store.layouts.master')
@section('content-wrapper')
    <section class="cart">
        <div class="title">
            Shopping Cart
        </div>

        <div class="cart-content">

            <div class="left-side">

                <div class="item">
                    <img class="item-image" src="{{ asset('vendor/webkul/shop/assets/images/jeans_big.jpg') }}" />

                    <div class="item-details">

                        <div class="item-title">
                            Rainbow Creation Embroided
                        </div>

                        <div class="price">
                            <span class="main-price">
                                $24.00
                            </span>
                            <span class="real-price">
                                $25.00
                            </span>
                            <span class="discount">
                                10% Off
                            </span>
                        </div>

                        <div class="summary">
                            Color : Gray, Size : S, Sleeve type : Puffed Sleeves, Occasion : Birthday,  Marriage Anniversary
                        </div>

                        <div class="misc">
                            <div class="qty-text">Quantity</div>
                            <div class="box">1</div>
                            <span class="remove">Remove</span>
                            <span class="towishlist">Move to Wishlist</span>
                        </div>
                    </div>

                </div>
                <div class="item">
                    <img class="item-image" src="{{ asset('vendor/webkul/shop/assets/images/jeans_big.jpg') }}" />

                    <div class="item-details">

                        <div class="item-title">
                            Rainbow Creation Embroided
                        </div>

                        <div class="price">
                            <span class="main-price">
                                $24.00
                            </span>
                            <span class="real-price">
                                $25.00
                            </span>
                            <span class="discount">
                                10% Off
                            </span>
                        </div>

                        <div class="summary">
                            Color : Gray, Size : S, Sleeve type : Puffed Sleeves, Occasion : Birthday,  Marriage Anniversary
                        </div>

                        <div class="misc">
                            <div class="qty-text">Quantity</div>
                            <div class="box">1</div>
                            <span class="remove">Remove</span>
                            <span class="towishlist">Move to Wishlist</span>
                        </div>
                    </div>

                </div>

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

                        <div class="item-details">
                            <span class="name">Item 1</span>
                            <span class="price">$25.00</span>
                        </div>

                        <div class="item-details">
                            <span class="name">Item 2</span>
                            <span class="price">$25.00</span>
                        </div>

                        <div class="item-details">
                            <span class="name">Item 3</span>
                            <span class="price">$25.00</span>
                        </div>

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
                    <img src="vendor/webkul/shop/assets/images/grid.png" />
                </div>
                <div class="product-name">
                    <span>Red Black Tees</span>
                </div>
                <div class="product-price">
                    <span>$65.00</span>
                </div>
                <div class="product-ratings">
                    <span>
                        <img src="vendor/webkul/shop/assets/images/5star.svg" />
                    </span>
                </div>
                <div class="cart-fav-seg">
                    <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                    <span><img src="vendor/webkul/shop/assets/images/wishadd.svg" /></span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="vendor/webkul/shop/assets/images/grid.png" />
                </div>
                <div class="product-name">
                    <span>Red Black Tees</span>
                </div>
                <div class="product-price">
                    <span>$65.00</span>
                </div>
                <div class="product-ratings">
                    <span>
                        <img src="vendor/webkul/shop/assets/images/5star.svg" />
                    </span>
                </div>
                <div class="cart-fav-seg">
                    <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                    <span><img src="vendor/webkul/shop/assets/images/wishadd.svg" /></span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="vendor/webkul/shop/assets/images/grid.png" />
                </div>
                <div class="product-name">
                    <span>Red Black Tees</span>
                </div>
                <div class="product-price">
                    <span>$65.00</span>
                </div>
                <div class="product-ratings">
                    <span>
                        <img src="vendor/webkul/shop/assets/images/5star.svg" />
                    </span>
                </div>
                <div class="cart-fav-seg">
                    <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                    <span><img src="vendor/webkul/shop/assets/images/wishadd.svg" /></span>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="vendor/webkul/shop/assets/images/grid.png" />
                </div>
                <div class="product-name">
                    <span>Red Black Tees</span>
                </div>
                <div class="product-price">
                    <span>$65.00</span>
                </div>
                <div class="product-ratings">
                    <span>
                        <img src="vendor/webkul/shop/assets/images/5star.svg" />
                    </span>
                </div>
                <div class="cart-fav-seg">
                    <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                    <span><img src="vendor/webkul/shop/assets/images/wishadd.svg" /></span>
                </div>
            </div>
        </div>
    </div>
@endsection