@push('css')
    <style>

        .col-lg-6{
            margin-top: 20px;
        }

        .image-show{
            display: inline-block!important;
            height: 90%;
            width: 90%;
        }

        .modal-header {
            display: none;
        }

        .close-btn {
            display: flex;
            position: absolute;
            right: 0;
            margin-right: 10px;
            margin-top: -10px;
        }

        .modal-parent{
            background: rgba(0,0,0,.7);
        }

    </style>
@endpush

@php
    $reviewHelper = app('Webkul\Product\Helpers\Review');

    $total = $reviewHelper->getTotalReviews($product);
@endphp

{!! view_render_event('bagisto.shop.products.review.before', ['product' => $product]) !!}

@if ($total)
    @php
        $reviews = $reviewHelper->getReviews($product)->paginate(10);
        
        $avgRatings = $reviewHelper->getAverageRating($product);
        
        $avgStarRating = round($avgRatings);

        $percentageRatings = $reviewHelper->getPercentageRating($product);
    @endphp
    @if (! empty($accordian))
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
                        <h4 class="col-lg-12 fs16">{{ $avgRatings }} {{ __('shop::app.reviews.star') }}</h4>

                        <star-ratings
                            :size="24"
                            :ratings="{{ $avgStarRating }}"
                        ></star-ratings>

                        <span class="fs16 fw6 display-block">
                            {{ __('shop::app.reviews.ratingreviews', [
                                'rating' => $avgRatings,
                                'review' => $total])
                            }}
                        </span>

                        @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
                            <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                                <button type="button" class="theme-btn light">{{ __('velocity::app.products.write-your-review') }}</button>
                            </a>
                        @endif
                    </div>

                    <div class="col-lg-12 col-xl-6">

                        @for ($i = 5; $i >= 1; $i--)

                            <div class="row">
                                <span class="col-3 no-padding fs16 fw6">{{ $i }} {{ __('shop::app.reviews.star') }}</span>

                                <div class="col-7 rating-bar" title="{{ $percentageRatings[$i] }}%">
                                    <div style="width: {{ $percentageRatings[$i] }}%"></div>
                                </div>

                                <span class="col-2 no-padding fs16">{{ $percentageRatings[$i] }} %</span>
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
                    <h3 class="col-lg-12">{{ $avgRatings }} {{ __('shop::app.reviews.star') }}</h3>

                    <star-ratings
                        :size="24"
                        :ratings="{{ $avgStarRating }}"
                    ></star-ratings>

                    <span class="fs16 display-block">
                        {{ __('shop::app.reviews.ratingreviews', [
                            'rating' => $avgRatings,
                            'review' => $total])
                        }}
                    </span>

                    @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
                        <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                            <button type="button" class="theme-btn light">{{ __('velocity::app.products.write-your-review') }}</button>
                        </a>
                    @endif
                </div>

                <div class="col-lg-12 col-xl-6">

                    @for ($i = 5; $i >= 1; $i--)

                        <div class="row">
                            <span class="col-3 no-padding fs16 fw6">{{ $i }} Star</span>

                            <div class="col-7 rating-bar" title="{{ $percentageRatings[$i] }}%">
                                <div style="width: {{ $percentageRatings[$i] }}%"></div>
                            </div>

                            <span class="col-2 no-padding fs16">{{ $percentageRatings[$i] }} %</span>
                        </div>
                    @endfor

                </div>
            </div>
        </div>
    @endif

    @if (! empty($accordian))
        <accordian :title="'{{ __('shop::app.products.total-reviews') }}'" :active="true">
            {{-- customer reviews --}}
            <div slot="header" class="col-lg-12 no-padding">
                <h3 class="display-inbl">
                    {{ __('velocity::app.products.reviews-title') }}
                </h3>

                <i class="rango-arrow"></i>
            </div>

            <div class="customer-reviews" slot="body">
                @foreach ($reviews as $review)
                    <div class="row">

                        <review-image 
                        review-detail='{{$review}}' 
                        image-detail='{{$review->images}}'>
                        </review-image>
                        
                        <div class="col-lg-12 mt5">
                            <span>{{ __('velocity::app.products.review-by') }} -</span>

                            <span class="fs16 fw6">
                                {{ $review->name }},
                            </span>

                            <span>{{ core()->formatDate($review->created_at, 'F d, Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach

                <a
                    href="{{ route('shop.reviews.index', ['slug' => $product->url_key ]) }}"
                    class="mb20 link-color"
                >{{ __('velocity::app.products.view-all-reviews') }}</a>
            </div>
        </accordian>
    @else
        <h3 class="display-inbl mb20 col-lg-12 no-padding">
            {{ __('velocity::app.products.reviews-title') }}
        </h3>
        <div class="customer-reviews">
            @foreach ($reviews as $review)
                <div class="row">
                    <h4 class="col-lg-12 fs18">{{ $review->title }}</h4>

                    <star-ratings
                        :ratings="{{ $review->rating }}"
                        push-class="mr10 fs16 col-lg-12"
                    ></star-ratings>

                    <div class="review-description col-lg-12">
                        <span>{{ $review->comment }}</span>
                    </div>

                    <div class="image col-lg-12">
                        @if (count($review->images) > 0)
                            @foreach ($review->images as $image)
                                <img class="image" src="{{ $image->url }}" style="height: 50px; width: 50px; margin: 5px;">
                            @endforeach
                        @endif
                    </div>

                    <div class="col-lg-12 mt5">
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
    @if (
        core()->getConfigData('catalog.products.review.guest_review')
        || auth()->guard('customer')->check()
    )
        <div class="customer-rating" style="border: none">
            <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                <button type="button" class="theme-btn light">{{ __('velocity::app.products.write-your-review') }}</button>
            </a>
        </div>
    @endif
@endif

{!! view_render_event('bagisto.shop.products.review.after', ['product' => $product]) !!}
@push('scripts')

<script type="text/x-template" id="review-image-template">
    <div>
        <h4 class="col-lg-12 fs18" v-text=reviewData.title></h4>

        <div v-if="reviewData.rating" class="stars mr5 fs16 mr10 fs16 col-lg-12">
            @if (! empty($review))
            <star-ratings 
            :ratings="reviewData.rating">
            </star-ratings>
            @endif
        </div>

        <div class="review-description col-lg-12">
            <span  v-text='reviewData.comment'></span>
        </div>

        <div class="image col-lg-12" >  
            <img  class="image" style="height: 50px; width: 50px; margin: 5px;" 
            v-for="(image, index) in imageData"
            :src="`${$root.baseUrl}/storage/${image.path}`"  
            @click="getModal()">
        </div>

        <modal id="modal-parent scrollable" class="reviewModal":is-open='showModal'>
            <div class="row" slot="body">
                <div @click="closeModal()" class="close-btn rango-close fs18 cursor-pointer">
                </div>
                <div class="col-lg-6">
                    <ul type="none" class="cd-slider">
                        <div class="VueCarousel navigation-hide">
                            <carousel-component
                                slides-per-page="1"
                                navigation-enabled="show"
                                :slides-count="imageDetails.length">
                                <slide
                                    :key="index"
                                    :slot="`slide-${index}`"
                                    title=" "
                                    v-for="(image, index) in imageDetails">
                                    <div tabindex="-1" aria-hidden="true" role="tabpanel" class="VueCarousel-slide">
                                        <li class="selected">
                                            <img class="image-show" :src="`${$root.baseUrl}/storage/${image.path}`">
                                        </li>
                                    </div>
                                </slide>
                            </carousel-component>
                        </div>
                    </ul>
                </div>
                <div class="col-lg-6 fs16">
                    <h2 class="quick-view-name" v-text='reviewDetails.title'></h2>
                    <div  class="product-rating">
                        <div v-if="reviewDetails.rating" class="stars mr5 fs16 ">
                            @if (! empty($review))
                            <star-ratings 
                            :ratings="reviewDetails.rating">
                            </star-ratings>
                            @endif
                        </div>
                    </div>
                    <p class="pt14 fs14 description-text" v-text='reviewDetails.comment'></p>
                </div>
            </div>
        </modal>           
    </div>
</script>
<script>
    Vue.component('review-image', {
        template: '#review-image-template',

        props: ['reviewDetail','imageDetail'],

        data() {
            return {
                showModal: false,
                reviewDetails: [],
                imageDetails: [],
                reviewData: [],
                imageData: [],
                
            }
        },
            mounted: function() {
                this.reviewData = JSON.parse(this.reviewDetail)
                this.imageData = JSON.parse(this.imageDetail)
            },

        methods: {
            getModal: function() {
                this.showModal = true;
                this.reviewDetails = JSON.parse(this.reviewDetail)
                this.imageDetails = JSON.parse(this.imageDetail)
            },

            closeModal: function() {
                this.showModal= false;
                this.imageDetails = [];
                this.reviewDetails = [];
               
            }
        }
    });
</script>
@endpush 