<template>
    <div class="container-fluid featured-products">
        <shimmer-component v-if="isLoading"></shimmer-component>

        <template v-else-if="featuredProducts.length > 0">
            <card-list-header :heading="cardTitle">
            </card-list-header>

            <div class="carousel-products" :class="localeDirection">
                <carousel-component
                    :slides-per-page="slidesPerPage"
                    navigation-enabled="hide"
                    pagination-enabled="hide"
                    id="fearured-products-carousel"
                    :locale-direction="localeDirection"
                    :autoplay="false"
                    :slides-count="featuredProducts.length">

                    <slide
                        :key="index"
                        :slot="`slide-${index}`"
                        v-for="(product, index) in featuredProducts">
                        <product-card
                            :list="list"
                            :product="product">
                        </product-card>
                    </slide>
                </carousel-component>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: [
            'cardTitle',
            'localeDirection',
            'count'
        ],

        data: function () {
            return {
                list: false,
                isLoading: true,
                featuredProducts: [],
                slidesPerPage: 6,
                windowWidth: window.innerWidth,
            }
        },

        mounted: function () {
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            })

            this.getFeaturedProducts();
            this.setSlidesPerPage(this.windowWidth);
        },

        watch: {
            /* checking the window width */
            windowWidth(newWidth, oldWidth) {
                this.setSlidesPerPage(newWidth);
            }
        },

        methods: {
            getFeaturedProducts: function () {
                this.$http.get(`${this.baseUrl}/category-details?category-slug=featured-products&count=${this.count}`)
                .then(response => {
                    var count = this.count;
                    if (response.data.status && count != 0 )
                    {
                        this.featuredProducts = response.data.products;
                    }else{
                        this.featuredProducts = 0;
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