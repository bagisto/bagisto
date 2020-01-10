@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')

@php
    if (! isset($total)) {
        $total = $reviewHelper->getTotalReviews($product);

        $avgRatings = $reviewHelper->getAverageRating($product);
        $avgStarRating = ceil($avgRatings);
    }

    $percentageRatings = $reviewHelper->getPercentageRating($product);
    $countRatings = $customHelper->getCountRating($product);
@endphp

{!! view_render_event('bagisto.shop.products.review.before', ['product' => $product]) !!}

    @if ($total)

        {{-- reviews count --}}
        <div class="row reviews pb15">
            <star-ratings
                :size="24"
                :ratings="{{ $avgStarRating }}"
            ></star-ratings>

            <div class="reviews-text">
                <span>{{ $avgRatings }} Ratings and {{ $total }} Reviews</span>
            </div>
        </div>

        @if (isset($accordian) && $accordian)
            <accordian :active="true">
                {{-- customer ratings --}}
                <div slot="header" class="col-lg-12 no-padding">
                    <h3 class="display-inbl">
                        {{ __('velocity::app.products.customer-rating') }}
                    </h3>

                    <i class="rango-arrow"></i>
                </div>

                <div class="row customer-rating" slot="body">
                    <div class="row full-width text-center mb30">
                        <div class="col-lg-12 col-xl-6">
                            <h4 class="col-lg-12">{{ $avgRatings }} Star</h4>

                            <star-ratings
                                :size="24"
                                :ratings="{{ $avgStarRating }}"
                            ></star-ratings>

                            <span>{{ $avgRatings }} Ratings and {{ $total }} Reviews</span>

                            @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
                                <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                                    <button type="button" class="btn-write-review">{{ __('velocity::app.products.write-your-review') }}</button>
                                </a>
                            @endif
                        </div>

                        <div class="col-lg-12 col-xl-6">

                            @for ($i = 5; $i >= 1; $i--)

                                <div class="row">
                                    <span class="col-lg-3 no-padding fs16 fw6">{{ $i }} Star</span>

                                    <div class="col-lg-7 rating-bar" title="{{ $percentageRatings[$i] }}%">
                                        <div style="width: {{ $percentageRatings[$i] }}%"></div>
                                    </div>

                                    <span class="col-lg-2 fs16">{{ $countRatings[$i] }}</span>
                                </div>
                            @endfor

                        </div>
                    </div>
                </div>
            </accordian>
        @else
            <div class="row customer-rating">
                <div class="row full-width text-center mb30">
                    <div class="col-lg-12 col-xl-6">
                        <h3 class="col-lg-12">{{ $avgRatings }} Star</h3>

                        <star-ratings
                            :size="24"
                            :ratings="{{ $avgStarRating }}"
                        ></star-ratings>

                        <span class="fs16 display-block">{{ $avgRatings }} Ratings and {{ $total }} Reviews</span>

                        @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
                            <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                                <button type="button" class="btn-write-review">{{ __('velocity::app.products.write-your-review') }}</button>
                            </a>
                        @endif
                    </div>

                    <div class="col-lg-12 col-xl-6">

                        @for ($i = 5; $i >= 1; $i--)

                            <div class="row">
                                <span class="col-lg-3 no-padding fs16 fw6">{{ $i }} Star</span>

                                <div class="col-lg-7 rating-bar" title="{{ $percentageRatings[$i] }}%">
                                    <div style="width: {{ $percentageRatings[$i] }}%"></div>
                                </div>

                                <span class="col-lg-2 fs16">{{ $countRatings[$i] }}</span>
                            </div>
                        @endfor

                    </div>
                </div>
            </div>
        @endif

        @if (isset($accordian) && $accordian)
            <accordian :title="'{{ __('shop::app.products.total-reviews') }}'" :active="true">
                {{-- customer reviews --}}
                <div slot="header" class="col-lg-12 no-padding">
                    <h3 class="display-inbl">
                        {{ __('velocity::app.products.reviews') }}
                    </h3>

                    <i class="rango-arrow"></i>
                </div>

                <div class="customer-reviews" slot="body">
                    @foreach ($reviewHelper->getReviews($product)->paginate(10) as $review)
                        <div class="row">
                            <h4 class="col-lg-12">{{ $review->title }}</h4>

                            <star-ratings
                                :ratings="{{ $review->rating }}"
                                push-class="mr10 fs16 col-lg-12"
                            ></star-ratings>

                            <div class="review-description col-lg-12">
                                <span>{{ $review->comment }}</span>
                            </div>

                            <div class="col-lg-12 review-date-time">
                                <span>{{ __('velocity::app.products.review-by') }} -</span>

                                <label>
                                    {{ $review->name }},
                                </label>

                                <span>{{ core()->formatDate($review->created_at, 'F d, Y') }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <a
                        href="{{ route('shop.reviews.index', ['slug' => $product->url_key ]) }}"
                        class="mb20"
                    >{{ __('velocity::app.products.view-all-reviews') }}</a>
                </div>
            </accordian>
        @else
            <h3 class="display-inbl mb20 col-lg-12 no-padding">
                {{ __('velocity::app.products.reviews') }}
            </h3>

            <div class="customer-reviews">
                @foreach ($reviewHelper->getReviews($product)->paginate(10) as $review)
                    <div class="row">
                        <h4 class="col-lg-12">{{ $review->title }}</h4>

                        <star-ratings
                            :ratings="{{ $review->rating }}"
                            push-class="mr10 fs16 col-lg-12"
                        ></star-ratings>

                        <div class="review-description col-lg-12">
                            <span>{{ $review->comment }}</span>
                        </div>

                        <div class="col-lg-12 review-date-time">
                            @if ("{{ $review->name }}")
                                <span>{{ __('velocity::app.products.review-by') }} -</span>

                                <label>
                                    {{ $review->name }},
                                </label>
                            @endif

                            <span>{{ core()->formatDate($review->created_at, 'F d, Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @else
        @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
            <div class="customer-rating" style="border: none">
                <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                    <button type="button" class="btn-write-review">{{ __('velocity::app.products.write-your-review') }}</button>
                </a>
            </div>
        @endif
    @endif

{!! view_render_event('bagisto.shop.products.review.after', ['product' => $product]) !!}