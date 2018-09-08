@inject ('reviewHelper', 'Webkul\Product\Product\Review')
@inject ('priceHelper', 'Webkul\Product\Product\Price')

@extends('shop::layouts.master')
@section('content-wrapper')
    <section class="product-review">
        <div class="category-breadcrumbs">

            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>

        </div>
        <div class="layouter">

            <div class="mixed-group">

                <div class="single-image">
                    <img src="{{ bagisto_asset('images/jeans_big.jpg') }}" />
                </div>

                <div class="details">

                    <div class="product-name">
                        {{ $product->name }}
                    </div>

                    <div class="price">
                        @if ($product->type == 'configurable')
                            <span class="main-price">${{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>
                        @else
                            @if ($priceHelper->haveSpecialPrice($product))
                                <span class="main-price">${{ core()->currency($priceHelper->getSpecialPrice($product)) }}</span>
                            @else
                                <span class="main-price">${{ core()->currency($product->price) }}</span>
                            @endif
                        @endif
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
                    <span>Ratings & {{ __('admin::app.customers.reviews.name') }}</span>
                    <!-- <button class="btn btn-md btn-primary">Write Review</button> -->
                    <a href="{{ route('shop.reviews.create', $product->url_key) }}" class="btn btn-lg btn-primary right">Write Review</a>
                </div>

                <div class="overall">
                    <div class="left-side">
                        <span class="number">
                            {{ $reviewHelper->getAverageRating($product) }}
                        </span>

                        @for($i = 1; $i <= $reviewHelper->getAverageRating($product) ; $i++)
                        <span class="stars">
                            <span class="icon star-icon"></span>
                        </span>
                        @endfor

                        <div class="total-reviews">
                            {{ $reviewHelper->getTotalRating($product) }} {{ __('admin::app.customers.reviews.rating') }} & {{ $reviewHelper->getTotalReviews($product) }} {{ __('admin::app.customers.reviews.name') }}
                        </div>
                    </div>
                    <div class="right-side">
                        @foreach($reviewHelper->getPercentageRating($product) as $key=>$count)
                        <div class="rater 5star">
                            <div class="star" id={{$key}}star> Star</div>
                            <div class="line-bar" >
                                <div class="line-value" id="{{ $key }}"></div>
                            </div>
                            <div class="percentage"> {{$count}}% </div>
                        </div>

                        <br/>
                        @endforeach
                    </div>
                </div>

                <div class="reviews">
                    @foreach($reviewHelper->loadMore($product) as $review)
                    <div class="review">
                        <div class="title">
                            {{ $review->title }}
                        </div>
                        <div class="stars">
                            @for ($i = 1; $i <= $review->rating ; $i++)
                                <span class="icon star-icon"></span>
                            @endfor
                        </div>
                        <div class="message">
                            {{ $review->comment }}
                        </div>
                        <div class="reviewer-details">
                            <span class="by">
                                {{ __('shop::app.products.by', ['name' => $review->customer->name]) }}
                            </span>
                            <span class="when">
                                {{ $reviewHelper->formatDate($review->created_at) }}
                            </span>
                        </div>
                    </div>
                    @endforeach

                    <div class="view-all" onclick="loadMore()">Load More</div>
                </div>

            </div>
        </div>
    </section>
@endsection


@push('scripts')

<script>

    window.onload = (function(){

        var percentage = {};
        <?php foreach ($reviewHelper->getPercentageRating($product) as $key=>$count) { ?>
            percentage.<?php echo $key; ?> = <?php echo "'$count';"; ?>
        <?php } ?>

        var i=5;
        for(var key in percentage){
            width= percentage[key] * 1.58;
            let id =key + 'star';
            console.log(id);
            document.getElementById(key).style.width = width + "px";
            document.getElementById(key).style.height = 4 + "px";
            document.getElementById(id).innerHTML = i + '\xa0\xa0' + "star";
            i--;
        }

    })();

    function loadMore(){
        var segment_str = window.location.pathname;
        var segment_array = segment_str.split( '/' );
        var last_segment = segment_array[segment_array.length - 1];
        url = segment_str.slice(0, segment_str.lastIndexOf('/'));
        project = url + "/" + (parseInt(last_segment)+1) ;
        location.href = project;
    }

</script>

@endpush







