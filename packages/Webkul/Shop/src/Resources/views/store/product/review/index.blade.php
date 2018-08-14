@extends('shop::store.layouts.master')
@section('content-wrapper')
    <section class="product-review">
        <div class="category-breadcrumbs">

            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>

        </div>
        <div class="layouter">

            <div class="mixed-group">

                <div class="single-image">
                    <img src="{{ asset('vendor/webkul/shop/assets/images/jeans_big.jpg') }}" />
                </div>

                <div class="details">

                    <div class="product-name">
                        Slit Open Jeans
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
                </div>

            </div>

            <div class="rating-reviews">

                <div class="title-inline">
                    <span>Ratings & Reviews</span>
                    <button class="btn btn-md btn-primary">Write Review</button>
                </div>

                <div class="overall">
                    <div class="left-side">
                        <span class="number">
                            4.3
                        </span>
                        <span class="stars">
                            <img src="{{ asset('vendor/webkul/shop/assets/images/5star.svg') }}" />
                        </span>

                        <div class="total-reviews">
                            24,330 Ratings & 104 Reviews
                        </div>
                    </div>
                    <div class="right-side">

                        <div class="rater 5star">
                            <div class="star">5 Star</div>
                            <div class="line-bar">
                                <div class="line-value"></div>
                            </div>
                            <div class="percentage">20%</div>
                        </div> <br/>
                        <div class="rater 4star">
                            <div class="star">4 Star</div>
                            <div class="line-bar">
                                <div class="line-value"></div>
                            </div>
                            <div class="percentage">20%</div>
                        </div> <br/>
                        <div class="rater 3star">
                            <div class="star">3 Star</div>
                            <div class="line-bar">
                                <div class="line-value"></div>
                            </div>
                            <div class="percentage">20%</div>
                        </div> <br/>
                        <div class="rater 2star">
                            <div class="star">2 Star</div>
                            <div class="line-bar">
                                <div class="line-value"></div>
                            </div>
                            <div class="percentage">20%</div>
                        </div> <br/>
                        <div class="rater 1star">
                            <div class="star">1 Star</div>
                            <div class="line-bar">
                                <div class="line-value"></div>
                            </div>
                            <div class="percentage">20%</div>
                        </div>

                    </div>

                </div>
                <div class="reviews">
                    <div class="review">
                        <div class="title">
                            Awesome
                        </div>
                        <div class="stars">
                            <img src="{{ asset('vendor/webkul/shop/assets/images/5star.svg') }}" />
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
                            <img src="{{ asset('vendor/webkul/shop/assets/images/5star.svg') }}" />
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
                            <img src="{{ asset('vendor/webkul/shop/assets/images/5star.svg') }}" />
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
                    <div class="view-all">Load More</div>
                </div>
            </div>
        </div>
    </section>
@endsection
