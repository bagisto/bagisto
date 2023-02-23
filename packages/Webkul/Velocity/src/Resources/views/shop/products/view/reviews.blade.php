@push('css')
    <style>
        .col-lg-6 {
            margin-top: 20px;
        }

        .modal-header {
            background: white;
            position: fixed;
            width: 600px;
            max-width: 80%;
            z-index: 102;
            border-bottom: none; 
            justify-content: right;
            border-top-right-radius: 0px;
        }

        .modal-body {
            margin-top: 30px;
        }

        .reviewModal .mt5 {
            display: table-row;
        }

        .modal-open .modal-overlay {
            display: block;
        }

        .modal-open .vue-go-top {
            display: none;
        }

        .modal-header i:nth-child(2){
            display: none;
        }

        .modal-container{
            border-radius: 0px;
        }
    </style>
@endpush

@php
    $reviewHelper = app('Webkul\Product\Helpers\Review');
    
    $total = $reviewHelper->getTotalReviews($product);
@endphp

<div class="modal-overlay"></div>

{!! view_render_event('bagisto.shop.products.review.before', ['product' => $product]) !!}

@if ($total)
    @php
        $reviews = $reviewHelper->getReviews($product)->paginate(10);
        
        $avgRatings = $reviewHelper->getAverageRating($product);
        
        $avgStarRating = round($avgRatings);
        
        $percentageRatings = $reviewHelper->getPercentageRating($product);
    @endphp

    @if (!empty($accordian))
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

                        <star-ratings :size="24" :ratings="{{ $avgStarRating }}"></star-ratings>

                        <span class="fs16 fw6 display-block">
                            {{ __('shop::app.reviews.ratingreviews', [
                                'rating' => $avgRatings,
                                'review' => $total,
                            ]) }}
                        </span>

                        @if (core()->getConfigData('catalog.products.review.guest_review') ||
                                auth()->guard('customer')->check())

                            <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key]) }}">
                                <button type="button" class="theme-btn light">
                                    {{ __('velocity::app.products.write-your-review') }}
                                </button>
                            </a>
                        @endif
                    </div>

                    <div class="col-lg-12 col-xl-6">

                        @for ($i = 5; $i >= 1; $i--)
        
                            <div class="row">
                                <span class="col-3 no-padding fs16 fw6">
                                    {{ $i }}{{ __('shop::app.reviews.star') }}
                                </span>

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

                    <star-ratings :size="24" :ratings="{{ $avgStarRating }}"></star-ratings>

                    <span class="fs16 display-block">
                        {{ __('shop::app.reviews.ratingreviews', [
                            'rating' => $avgRatings,
                            'review' => $total,
                        ]) }}
                    </span>

                    @if (core()->getConfigData('catalog.products.review.guest_review') ||
                            auth()->guard('customer')->check())

                        <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key]) }}">
                            <button type="button" class="theme-btn light">
                                {{ __('velocity::app.products.write-your-review') }}
                            </button>
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

    @if (!empty($accordian))
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
                            review-detail='{{ $review }}'
                            image-detail='{{ $review->images }}'>
                        </review-image>
                    </div>
                @endforeach

                <a href="{{ route('shop.reviews.index', ['slug' => $product->url_key]) }}" class="mb20 link-color">
                    {{ __('velocity::app.products.view-all-reviews') }}
                </a>
            </div>
        </accordian>
    @else
        <h3 class="display-inbl mb20 col-lg-12 no-padding">
            {{ __('velocity::app.products.reviews-title') }}
        </h3>

        <div class="customer-reviews">
            @foreach ($reviews as $review)
                <div class="row">
                    <review-image 
                        review-detail='{{ $review }}'
                        image-detail='{{ $review->images }}'>
                    </review-image>
                </div>
            @endforeach
        </div>
    @endif

@else
    @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
        <div class="customer-rating" style="border: none">

            <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key]) }}">
                <button type="button" class="theme-btn light">
                    {{ __('velocity::app.products.write-your-review') }}
                </button>
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
                <star-ratings :ratings="reviewData.rating"></star-ratings>
            </div>

            <div class="review-description col-lg-12">
                <span v-text='reviewData.comment'></span>
            </div>

            <div class="image col-lg-12" >  
                <img  class="image" style="height: 50px; width: 50px; margin: 5px; cursor: pointer;" 
                    v-for="(image, index) in imageData"
                    :src="`${$root.baseUrl}/storage/${image.path}`"  
                    @click="getModal()">
            </div>

            <div class="col-lg-12 mt5">
                <span>{{ __('velocity::app.products.review-by') }} -</span>
                <span class="fs16 fw6" v-text='reviewData.name'></span>

                <span>
                    @{{ new Date(reviewData.created_at).toLocaleDateString('en-us', {year:"numeric", month:"short", day:"numeric"}) }}
                </span>                
            </div>

            <modal class="reviewModal" :is-open='showModal'>
                <h3 slot="header">
                    <i @click="closeModal()" class="icon remove-icon "></i>
                </h3>
                <div class="row" slot="body">
                    <div class="col-lg-6">
                        <ul type="none" class="cd-slider">
                            <div class="VueCarousel navigation-hide">
                                <carousel-component
                                    slides-per-page="1"
                                    navigation-enabled="show"
                                    :slides-count="imageData.length">
                                    <slide
                                        :key="index"
                                        :slot="`slide-${index}`"
                                        title=" "
                                        v-for="(image, index) in imageData">
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
                        <h2 class="quick-view-name" v-text='reviewData.title'></h2>
                        <div  class="product-rating">
                            <div v-if="reviewData.rating" class="stars mr5 fs16 ">
                                <star-ratings 
                                    :ratings="reviewData.rating" >
                                </star-ratings>
                            </div>
                        </div>

                        <p class="pt14 fs14 description-text" v-text='reviewData.comment'></p>

                        <div class="col-lg-12 mt5">
                            <span>{{ __('velocity::app.products.review-by') }} -</span>
                            <span class="fs16 fw6" v-text='reviewData.name'></span>
                            
                            <span class="reviewDate"> 
                                @{{ new Date(reviewData.created_at).toLocaleDateString('en-us', {year:"numeric", month:"short", day:"numeric"}) }}
                            </span>
                        </div>
                    </div>
                </div>
            </modal>           
        </div>
    </script>

    <script>
        Vue.component('review-image', {
            template: '#review-image-template',

            props: ['reviewDetail', 'imageDetail'],

            data() {
                return {
                    showModal: false,

                    reviewData: JSON.parse(this.reviewDetail),

                    imageData: JSON.parse(this.imageDetail),
                }
            },

            methods: {
                getModal: function() {
                    this.showModal = true;
                },

                closeModal: function() {
                    this.showModal = false;
                },
            }
        });
    </script>
@endpush
