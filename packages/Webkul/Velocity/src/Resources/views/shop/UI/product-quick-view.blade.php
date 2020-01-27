@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

@php
    $productBaseImage = $productImageHelper->getProductBaseImage($product);
    $productPrice = $product->getTypeInstance()->getProductPrices();
    $totalReviews = $product->reviews;
    $avgRatings = ceil($reviewHelper->getAverageRating($product));

    // @TODO
    // foreach ($totalReviews as $review) {
    //     $productReview = $review;
    // }
@endphp

<script type="text/x-template" id="quick-view-btn-template">

    <div
        class="quick-view-btn-container"
        id="quick-view-btn-container"
        :name="details"
        @click="openQuickView({details: details, event: $event})">
        <product-quick-view v-if="quickViewDetails" :quick-view-details="quickViewDetails"></product-quick-view>

        <span class="rango-zoom-plus"></span>

        <button type="button">Quick View</button>
    </div>
</script>

<script type="text/x-template" id="product-quick-view-template">
    <div class="cd-quick-view" v-if="quickView">
        <div class="col-lg-6 cd-slider-wrapper model-animation">
            <ul class="cd-slider" type="none">
                <li class="selected">
                    <img src="{{ $productBaseImage['medium_image_url'] }}" :alt="quickViewDetails.name" />
                </li>
            </ul>

            <ul type="square" class="circle-list">
                <li
                    v-for="index in {{ sizeof($productBaseImage) }}"
                    :class="`circle ${(index - 1 == currentlyActiveImage) ? '' : 'fill'}`"
                    @click="changeImage(index - 1)"
                ></li>
            </ul>
        </div>

        <div class="col-lg-6 cd-item-info fs16">
            <h2 class="text-nowrap fw6 quick-view-name">{{ $product->name }}</h2>

            <div class="product-price fs14">
                <h2 class="text-nowrap fw6 quick-view-price">{{ $product->price }}</h2>
            </div>

            {{-- @TODO --}}
            {{-- @if ($totalReviews)
                <div class="">
                    {{ $productReview['rating'] }} Ratings
                </div>
            @else
                <div class="">
                    <a href="">{{ __('velocity::app.products.be-first-review') }}</a>
                </div>
            @endif --}}

            <p class="pt14 description-text">
                {!! $product->description !!}
            </p>
            <button type="submit" class="btn btn-add-to-cart quick-addtocart-btn">
                <span class="rango-cart-1 fs20"></span>
                    Add To Cart
            </button>

            <div class="action-buttons">
                <div style="display: inline-block;" class="rango-exchange fs24"></div>
                <div style="display: inline-block;" class="rango-heart fs24"></div>
            </div>

        </div>

        <div class="close-btn rango-close fs18 cursor-pointer" @click="closeQuickView"></div>
    </div>
</script>

@push('scripts')
    <script type="text/javascript">
        (() => {
            Vue.component('quick-view-btn', {
                props: ['details'],
                template: '#quick-view-btn-template',

                data: function () {
                    return {
                        quickViewDetails: false,
                    }
                },

                methods: {
                    openQuickView: function ({details, event}) {
                        document.getElementById('quick-view-btn-container').style.display = "block";
                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        this.quickViewDetails = true;
                    }
                }
            })

            Vue.component('product-quick-view', {
                template: '#product-quick-view-template',
                props: ['quickViewDetails'],

                data: function () {
                    // backgroud blur

                    let body = $('body');
                    return {
                        body: body,
                        currentlyActiveImage: 0,
                        quickView: this.quickViewDetails ? true : false,
                    }
                },

                mounted: function () {
                    // console.log(this.quickViewDetails, this.quickView);
                },

                methods: {
                    closeQuickView: function () {
                        this.quickView = false;
                        this.quickViewDetails = false;
                        document.getElementById('quick-view-btn-container').style.display = "none";
                        window.location.reload();

                    },

                    changeImage: function (imageIndex) {
                        this.currentlyActiveImage = imageIndex;
                    }
                }
            })
        })();
    </script>
@endpush