<template>
    <div class="modal-parent scrollable">
        <div class="cd-quick-view">
            <template v-if="showProductDetails || true">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="cd-slider" type="none">
                            <carousel-component
                                slides-per-page="1"
                                navigation-enabled="hide"
                                :slides-count="product.galleryImages.length">

                                    <slide
                                        :key="index"
                                        :slot="`slide-${index}`"
                                        title=" "
                                        v-for="(image, index) in product.galleryImages">

                                        <li class="selected" @click="showProductDetails = false">
                                            <img :src="image.medium_image_url" :alt="product.name" />
                                        </li>
                                    </slide>
                            </carousel-component>
                        </ul>
                    </div>

                    <div class="col-lg-6 fs16">
                        <h2 class="fw6 quick-view-name" v-html="product.name"></h2>

                        <div class="product-price" v-html="product.priceHTML"></div>

                        <div
                            class="product-rating"
                            v-if="product.totalReviews && product.totalReviews > 0">

                            <star-ratings :ratings="product.avgRating"></star-ratings>
                            <a class="pl10 unset active-hover" :href="`${$root.baseUrl}/reviews/${product.slug}`">
                                {{ __('products.reviews-count', {'totalReviews': product.totalReviews}) }}
                            </a>
                        </div>

                        <div class="product-rating" v-else>
                            <span class="fs14" v-text="product.firstReviewText"></span>
                        </div>

                        <p class="pt14 fs14 description-text" v-html="product.shortDescription"></p>

                        <div class="product-actions">
                            <vnode-injector :nodes="getDynamicHTML(product.addToCartHtml)"></vnode-injector>
                        </div>
                    </div>
                </div>

                <div
                    @click="closeQuickView"
                    class="close-btn rango-close fs18 cursor-pointer">
                </div>
            </template>

            <template v-else>
                <div class="product-gallery">
                    <ul class="cd-slider" type="none">
                        <carousel-component
                            slides-per-page="1"
                            navigation-enabled="hide"
                            :slides-count="product.galleryImages.length">

                                <slide
                                    :key="index"
                                    :slot="`slide-${index}`"
                                    v-for="(image, index) in product.galleryImages">

                                    <li class="selected">
                                        <img :src="image.medium_image_url" :alt="product.name" />
                                    </li>
                                </slide>
                        </carousel-component>
                    </ul>
                </div>

                <div
                    @click="showProductDetails = true"
                    class="close-btn rango-close fs18 cursor-pointer">
                </div>
            </template>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        data: function () {
            return {
                currentlyActiveImage: 0,
                showProductDetails: true,
                product: this.$root.productDetails,
            }
        },

        mounted: function () {
            $('.cd-quick-view').fadeIn(500);
            $('.compare-icon').click(this.closeQuickView);
            $('.wishlist-icon').click(this.closeQuickView);
            $('.add-to-cart-btn').click(() => setTimeout(this.closeQuickView, 0));
        },

        methods: {
            closeQuickView: function () {
                this.$root.quickView = false;
                this.$root.productDetails = [];
                $('body').toggleClass('overflow-hidden');
            },

            changeImage: function (imageIndex) {
                this.currentlyActiveImage = imageIndex;
            },
        }
    }
</script>