@extends('shop::layouts.master')
@section('content-wrapper')
    <section class="product-detail">
        <div class="category-breadcrumbs">

            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>

        </div>
        <div class="layouter">

            <div class="product-image-group">

                <div class="side-group">
                    <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
                    <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
                    <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
                    <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
                </div>

                <div class="product-hero-image">
                    <img src="{{ bagisto_asset('images/jeans_big.jpg') }}" />
                    <img class="wishlist" src="{{ bagisto_asset('images/wish.svg') }}" />
                    <img class="share" src="{{ bagisto_asset('images/icon-share.svg') }}" />
                </div>

            </div>

            <div class="details">

                <div class="product-heading">
                    <span>Rainbow creation Embroidered</span>
                </div>
                <div class="rating">
                    <img src="{{ bagisto_asset('images/5star.svg') }}" />
                    75 Ratings & 11 Reviews
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
                <div class="stock-status">
                    InStock
                </div>
                <br/>
                <div class="description">
                    Bluetooth-enabled connectivity with NFC provides 100% independence, device compatibility and freedom of movement Ear-cup-mounted sensors for easy-access, touch-sensitive control 30mm drivers.
                </div>

                <hr/>

                <div class="attributes">

                    <div class="attribute color">
                        <div class="title">Color</div>

                        <div class="values">
                            <div class="colors red"></div>
                            <div class="colors blue"></div>
                            <div class="colors green"></div>
                        </div>
                    </div>

                    <div class="attribute size">
                        <div class="title">Size</div>

                        <div class="values">
                            <div class="size xl">XL</div>
                            <div class="size xxl">XXL</div>
                            <div class="size xxxl">XXXL</div>
                        </div>
                    </div>

                    <div class="attribute quantity">
                        <div class="title">Quantity</div>

                        <div class="values">
                            <div class="size">1</div>
                        </div>
                    </div>

                </div>

                <hr/>
                {{--  The elements below will be accordians  --}}
                <div class="full-description">
                    <h4>Description</h4>
                    Bluetooth-enabled connectivity with NFC provides 100% independence, device compatibility and freedom of movement Ear-cup-mounted sensors for easy-access, touch-sensitive control 30mm drivers

                    Bluetooth-enabled connectivity with NFC provides 100% independence, device compatibility and freedom of movement Ear-cup-mounted sensors for easy-access, touch-sensitive control 30mm d rivers. Bluetooth-enabled connectivity with NFC provides 100% independence, device compatibility and freedom of movement Ear-cup-mounted sensors for easy-access, touch-sensitive control 30mm drivers

                    NFC provides 100% independence, device compatibility and freedom of movement Ear-cup-mounted sensors for easy-access, touch-sensitive control 30mm drivers
                </div>
                <hr/>
                <div class="full-specifications">
                    <h4>Specification</h4>
                </div>

                <hr/>

                <div class="rating-reviews">
                    <div class="title">
                        Ratings & Reviews
                    </div>
                    <div class="overall">
                        <span class="number">
                            4.3
                        </span>
                        <span class="stars">
                            <img src="{{ bagisto_asset('images/5star.svg') }}" />
                        </span>

                        <button class="btn btn-md btn-primary">Write Review</button>

                        <div class="total-reviews">
                            24,330 Ratings & 104 Reviews
                        </div>
                    </div>
                    <div class="reviews">
                        <div class="review">
                            <div class="title">
                                Awesome
                            </div>
                            <div class="stars">
                                <img src="{{ bagisto_asset('images/5star.svg') }}" />
                            </div>
                            <div class="message">
                                NFC provides 100% independence, device compatibility and freedom of movement Ear-cup-mounted sensors for easy-access, touch-sensitive control 30mm drivers
                            </div>
                            <div class="reviewer-details">
                                <span class="by">
                                    By John Doe
                                </span>
                                <span class="when">
                                    , 25, June 2018
                                </span>
                            </div>
                        </div>
                        <div class="review">
                            <div class="title">
                                Awesome
                            </div>
                            <div class="stars">
                                <img src="{{ bagisto_asset('images/5star.svg') }}" />
                            </div>
                            <div class="message">
                                NFC provides 100% independence, device compatibility and freedom of movement Ear-cup-mounted sensors for easy-access, touch-sensitive control 30mm drivers
                            </div>
                            <div class="reviewer-details">
                                <span class="by">
                                    By John Doe
                                </span>
                                <span class="when">
                                    , 25, June 2018
                                </span>
                            </div>
                        </div>
                        <div class="review">
                            <div class="title">
                                Awesome
                            </div>
                            <div class="stars">
                                <img src="{{ bagisto_asset('images/5star.svg') }}" />
                            </div>
                            <div class="message">
                                NFC provides 100% independence, device compatibility and freedom of movement Ear-cup-mounted sensors for easy-access, touch-sensitive control 30mm drivers
                            </div>
                            <div class="reviewer-details">
                                <span class="by">
                                    By John Doe
                                </span>
                                <span class="when">
                                    , 25, June 2018
                                </span>
                            </div>
                        </div>
                        <div class="view-all">View All</div>
                        <hr/>
                    </div>
                </div>
            </div>
        </div>

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


    </section>
@endsection
