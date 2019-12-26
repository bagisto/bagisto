@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@php
    $productBaseImage = $productImageHelper->getProductBaseImage($product);
    $productPrice = $product->getTypeInstance()->getProductPrices();

@endphp

<script type="text/x-template" id="quick-view-btn-template">

    <div
        class="quick-view-btn-container"
        id="quick-view-btn-container"
        @click="openQuickView({details: details, event: $event})">

        {{-- <product-quick-view
            v-if="quickViewDetails"
            :quick-view-details="quickViewDetails"
        ></product-quick-view>

        <span class="rango-zoom-plus"></span>

        <button type="button">Quick View</button> --}}
    </div>
</script>

<script type="text/x-template" id="product-quick-view-template">
    <div class="cd-quick-view" v-if="quickView">
        <div class="col-lg-6 cd-slider-wrapper">
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

        <div class="col-lg-6 cd-item-info fs14">
            <h2 class="text-nowrap fw6" v-text="quickViewDetails['name']"></h2>

            <h2 class="text-nowrap fw6">{{ $productPrice['final_price']['formated_price'] }}</h2>

            <star-ratings :ratings="quickViewDetails['star-rating']" push-class="display-inbl"></star-ratings>

            <span class="align-vertical-top"> 25 ratings</span>

            <p class="pt20">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p>

            <div class="close-btn rango-close fs18 cursor-pointer" @click="closeQuickView"></div>

            <div class="action-buttons">
                <add-to-cart-btn></add-to-cart-btn>
                <span class="rango-exchange fs24"></span>
                <span class="rango-heart fs24"></span>
            </div>

        </div>
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
                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        // this.quickView

                        // .velocity({

                        //     'width': '734px',
                        //     'left': '200px',
                        //     'top': '50px',
                        // }, 1000, [ 500, 20 ])

                        // .velocity({
                        //     'width': '734px',
                        //     'left': '200px',
                        // }, 3000, 'ease');

                        this.quickViewDetails = details;
                    }
                }
            })

            Vue.component('product-quick-view', {
                template: '#product-quick-view-template',
                props: ['quickViewDetails'],

                data: function () {
                    // backgroud blur

                    let body = $('body');
                    // body.addClass('body-blur');

                    return {
                        body: body,
                        currentlyActiveImage: 0,
                        quickView: this.quickViewDetails ? true : false,
                    }
                },

                mounted: function () {
                },

                methods: {
                    closeQuickView: function () {
                        // this.body.removeClass('body-blur');
                        this.quickView = false;
                        this.$parent.quickViewDetails = false;
                    },

                    changeImage: function (imageIndex) {
                        this.currentlyActiveImage = imageIndex;
                    }
                }
            })
        })();
    </script>
@endpush