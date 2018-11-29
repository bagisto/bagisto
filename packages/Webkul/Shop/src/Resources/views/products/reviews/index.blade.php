@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('priceHelper', 'Webkul\Product\Helpers\Price')
@section('page_title')
    {{ __('shop::app.reviews.product-review-page-title') }} - {{ $product->name }}
@endsection
@extends('shop::layouts.master')
@section('content-wrapper')
    <section class="review">
        {{--  <div class="category-breadcrumbs">

            <span class="breadcrumb">Home</span> > <span class="breadcrumb">Men</span> > <span class="breadcrumb">Slit Open Jeans</span>

        </div>  --}}
        <div class="review-layouter">

            @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

            <?php $productBaseImage = $productImageHelper->getProductBaseImage($product); ?>

            <div class="product-info">
                <div class="product-image">
                    <a href="{{ route('shop.products.index', $product->url_key) }}" title="{{ $product->name }}">
                        <img src="{{ $productBaseImage['medium_image_url'] }}" />
                    </a>
                </div>

                <div class="product-name mt-20">
                    <a href="{{ url()->to('/').'/products/'.$product->url_key }}" title="{{ $product->name }}">
                        <span>{{ $product->name }}</span>
                    </a>
                </div>

                <div class="product-price mt-10">
                    @inject ('priceHelper', 'Webkul\Product\Helpers\Price')

                    @if ($product->type == 'configurable')
                        <span class="pro-price">{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>
                    @else
                        @if ($priceHelper->haveSpecialPrice($product))
                            <span class="pro-price">{{ core()->currency($priceHelper->getSpecialPrice($product)) }}</span>
                        @else
                            <span class="pro-price">{{ core()->currency($product->price) }}</span>
                        @endif
                    @endif

                    {{--  <span class="pro-price-not">
                        <strike> $45.00 </strike>
                    </span>

                    <span class="offer"> 10% Off </span>  --}}
                </div>
            </div>

            <div class="review-form">

                <div class="heading mt-10">
                    <span> {{ __('shop::app.reviews.rating-reviews') }} </span>

                    <a href="{{ route('shop.reviews.create', $product->url_key) }}" class="btn btn-lg btn-primary right">Write Review</a>
                </div>

                <div class="ratings-reviews mt-35">

                    <div class="left-side">
                        <span class="rate">
                            {{ $reviewHelper->getAverageRating($product) }}
                        </span>

                        @for($i=1;$i<=$reviewHelper->getAverageRating($product);$i++)
                        <span class="stars">
                            <span class="icon star-icon"></span>
                        </span>
                        @endfor

                        <div class="total-reviews mt-5">
                            {{ $reviewHelper->getTotalRating($product) }} {{ __('admin::app.customers.reviews.rating') }} & {{ $reviewHelper->getTotalReviews($product) }} {{ __('admin::app.customers.reviews.title') }}
                        </div>
                    </div>

                    <div class="right-side">
                        @foreach($reviewHelper->getPercentageRating($product) as $key=>$count)
                        <div class="rater 5star">
                            <div class="rate-number" id={{$key}}star></div>
                            <div class="star-name">Star</div>
                            <div class="line-bar">
                                <div class="line-value" id="{{ $key }}"></div>
                            </div>
                            <div class="percentage">
                                <span> {{$count}}% </span>
                            </div>
                        </div>

                        <br/>
                        @endforeach
                    </div>

                </div>

                <div class="rating-reviews">
                    <div class="reviews">

                        @foreach ($reviewHelper->getReviews($product)->paginate(10) as $review)
                            <div class="review">
                                <div class="title">
                                    {{ $review->title }}
                                </div>

                                <span class="stars">
                                    @for ($i = 1; $i <= $review->rating; $i++)

                                        <span class="icon star-icon"></span>

                                    @endfor
                                </span>

                                <div class="message">
                                    {{ $review->comment }}
                                </div>

                                <div class="reviewer-details">
                                    <span class="by">
                                        {{ __('shop::app.products.by', ['name' => $review->customer->name]) }},
                                    </span>

                                    <span class="when">
                                        {{ core()->formatDate($review->created_at) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                    </div>
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

            percentage = <?php echo "'$count';"; ?>
            id = <?php echo "'$key';"; ?>
            idNumber = id + 'star';

            document.getElementById(id).style.width = percentage + "%";
            document.getElementById(id).style.height = 4 + "px";
            document.getElementById(idNumber).innerHTML = id ;

        <?php } ?>

    })();

</script>

@endpush