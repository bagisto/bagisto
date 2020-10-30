@php
    $count = core()->getConfigData('catalog.products.homepage.no_of_new_product_homepage');
    $count = $count ? $count : 10;
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp

<new-products></new-products>

@push('scripts')
    <script type="text/x-template" id="new-products-template">
        <div class="container-fluid">
            <shimmer-component v-if="isLoading"></shimmer-component>

            <template v-else-if="newProducts.length > 0">
                <card-list-header heading="{{ __('shop::app.home.new-products') }}">
                </card-list-header>

                {!! view_render_event('bagisto.shop.new-products.before') !!}

                    @if ($showRecentlyViewed)
                        <div class="row {{ $direction }}">
                            <div class="col-lg-9 col-md-12 no-padding carousel-products with-recent-viewed">
                                <carousel-component
                                    :slides-per-page="slidesPerPage"
                                    navigation-enabled="hide"
                                    pagination-enabled="hide"
                                    id="new-products-carousel"
                                    locale-direction="{{ $direction }}"
                                    :slides-count="newProducts.length">

                                    <slide
                                        :key="index"
                                        :slot="`slide-${index}`"
                                        v-for="(product, index) in newProducts">
                                        <product-card
                                            :list="list"
                                            :product="product">
                                        </product-card>
                                    </slide>
                                </carousel-component>
                            </div>

                            @include ('shop::products.list.recently-viewed', [
                                'quantity'          => 3,
                                'addClass'          => 'col-lg-3 col-md-12',
                            ])
                        </div>
                    @else
                        <div class="carousel-products {{ $direction }}">
                            <carousel-component
                                :slides-per-page="6"
                                navigation-enabled="hide"
                                pagination-enabled="hide"
                                id="new-products-carousel"
                                locale-direction="{{ $direction }}"
                                :slides-count="newProducts.length">

                                <slide
                                    :key="index"
                                    :slot="`slide-${index}`"
                                    v-for="(product, index) in newProducts">
                                    <product-card
                                        :list="list"
                                        :product="product">
                                    </product-card>
                                </slide>
                            </carousel-component>
                        </div>
                    @endif

                {!! view_render_event('bagisto.shop.new-products.after') !!}
            </template>

            @if ($count==0)
                <template>
                        @if ($showRecentlyViewed)
                            <div class="row {{ $direction }}">
                                <div class="col-lg-9 col-md-12 no-padding carousel-products with-recent-viewed"></div>

                                @include ('shop::products.list.recently-viewed', [
                                    'quantity'          => 3,
                                    'addClass'          => 'col-lg-3 col-md-12',
                                ])
                            </div>
                        @endif
                </template>
            @endif
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('new-products', {
                'template': '#new-products-template',

                data: function () {
                    return {
                        list: false,
                        isLoading: true,
                        newProducts: [],
                        slidesPerPage: 6,
                        windowWidth: window.innerWidth,
                    }
                },

                mounted: function () {
                    this.$nextTick(() => {
                        window.addEventListener('resize', this.onResize);
                    });

                    this.getNewProducts();
                    this.setSlidesPerPage(this.windowWidth);
                },

                watch: {
                    /* checking the window width */
                    windowWidth(newWidth, oldWidth) {
                        this.setSlidesPerPage(newWidth);
                    }
                },

                methods: {
                    'getNewProducts': function () {
                        this.$http.get(`${this.baseUrl}/category-details?category-slug=new-products&count={{ $count }}`)
                        .then(response => {
                             var count = '{{$count}}';
                            if (response.data.status && count != 0){
                                this.newProducts = response.data.products;
                            }else{
                                this.newProducts = 0;
                            }

                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;
                            console.log(this.__('error.something_went_wrong'));
                        })
                    },

                    onResize: function () {
                        this.windowWidth = window.innerWidth;
                    },

                    /* setting slides on the basis of window width */
                    setSlidesPerPage: function (width) {
                        if (width >= 992) {
                            this.slidesPerPage = 5;
                        } else if (width < 992 && width >= 420) {
                            this.slidesPerPage = 4;
                        } else {
                            this.slidesPerPage = 2;
                        }
                    }
                },

                /* removing event */
                beforeDestroy: function () {
                    window.removeEventListener('resize', this.onResize);
                },
            })
        })()
    </script>
@endpush