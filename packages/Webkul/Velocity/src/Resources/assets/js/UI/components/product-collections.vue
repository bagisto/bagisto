<template>
    <div class="container-fluid">
        <shimmer-component v-if="isLoading"></shimmer-component>

        <template v-else-if="productCollections.length > 0">
            <card-list-header :heading="productTitle">
            </card-list-header>

            <div class="row" :class="localeDirection">
                <div
                    class="col-md-12 no-padding carousel-products with-recent-viewed"
                    :class="showRecentlyViewed ? 'col-lg-9' : 'col-lg-12'">
                    <carousel-component
                        :slides-per-page="slidesPerPage"
                        navigation-enabled="hide"
                        pagination-enabled="hide"
                        id="new-products-carousel"
                        :locale-direction="localeDirection"
                        :slides-count="productCollections.length"
                        v-if="count != 0">

                        <slide
                            :key="index"
                            :slot="`slide-${index}`"
                            v-for="(product, index) in productCollections">
                            <product-card
                                :list="list"
                                :product="product">
                            </product-card>
                        </slide>
                    </carousel-component>
                </div>

                <recently-viewed
                    :title="recentlyViewedTitle"
                    :no-data-text="noDataText"
                    add-class="col-lg-3 col-md-12"
                    :add-class="localeDirection"
                    quantity="3"
                    add-class-wrapper=""
                    v-if="showRecentlyViewed">
                </recently-viewed>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            'count': Number,
            'productTitle': String,
            'productRoute': String,
            'localeDirection': String,
            'showRecentlyViewed': {
                type: Boolean,
                default: false
            },
            'recentlyViewedTitle': String,
            'noDataText': String,
        },

        data: function () {
            return {
                list: false,
                isLoading: true,
                productCollections: [],
                slidesPerPage: 6,
                windowWidth: window.innerWidth,
            }
        },

        mounted: function () {
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            });

            this.getProducts();
            this.setSlidesPerPage(this.windowWidth);

            console.log(this.productRoute, this.showRecentlyViewed);
        },

        watch: {
            /* checking the window width */
            windowWidth(newWidth, oldWidth) {
                this.setSlidesPerPage(newWidth);
            }
        },

        methods: {
            /* fetch product collections */
            getProducts: function () {
                this.$http.get(this.productRoute)
                .then(response => {
                    let count = this.count;

                    if (response.data.status && count != 0) {
                        this.productCollections = response.data.products;
                    } else {
                        this.productCollections = 0;
                    }

                    this.isLoading = false;
                })
                .catch(error => {
                    this.isLoading = false;
                    console.log(this.__('error.something_went_wrong'));
                })
            },

            /* on resize set window width */
            onResize: function () {
                this.windowWidth = window.innerWidth;
            },

            /* setting slides on the basis of window width */
            setSlidesPerPage: function (width) {
                if (width >= 992) {
                    this.slidesPerPage = 6;
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
    }
</script>