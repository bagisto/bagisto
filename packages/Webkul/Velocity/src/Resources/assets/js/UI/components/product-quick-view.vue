<template>
    <div class="modal-parent scrollable">
        <div class="cd-quick-view">
            <div class="col-lg-6 cd-slider-wrapper model-animation">
                <ul class="cd-slider" type="none">
                    <li class="selected">
                        <img :src="product.galleryImages[currentlyActiveImage].medium_image_url" :alt="product.name" />
                    </li>
                </ul>

                <ul type="square" class="circle-list" v-if='product.galleryImages.length > 1'>
                    <li
                        :key="index"
                        v-for="index in product.galleryImages.length"
                        :class="`circle ${(index - 1 == currentlyActiveImage) ? '' : 'fill'}`"
                        @click="changeImage(index - 1)">
                    </li>
                </ul>
            </div>

            <div class="col-lg-6 cd-item-info fs16">
                <h2 class="text-nowrap fw6 quick-view-name">{{ product.name }}</h2>

                <div class="product-price" v-html="product.priceHTML"></div>

                <div
                    class="product-rating"
                    v-if="product.totalReviews && product.totalReviews > 0">

                    <star-ratings :ratings="product.avgRating"></star-ratings>
                    <span class="pl10">
                        {{ __('products.ratings', {'totalRatings': product.totalReviews}) }}
                    </span>
                </div>

                <div class="product-rating" v-else>
                    <span class="fs14" v-text="product.firstReviewText"></span>
                </div>

                <p class="pt14 fs14 description-text" v-html="product.description"></p>

                <vnode-injector :nodes="getAddToCartHtml()"></vnode-injector>
            </div>

            <div
                @click="closeQuickView"
                class="close-btn rango-close fs18 cursor-pointer">
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        data: function () {
            return {
                currentlyActiveImage: 0,
                product: this.$root.productDetails,
            }
        },

        mounted: function () {
            // console.log(this.quickViewDetails, this.quickView);
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

            getAddToCartHtml: function () {
                const { render, staticRenderFns } = Vue.compile(this.product.addToCartHtml);
                const _staticRenderFns = this.$options.staticRenderFns = staticRenderFns;

                try {
                    var output = render.call(this, this.$createElement)
                } catch (exception) {
                    console.log("something went wrong");
                }

                this.$options.staticRenderFns = _staticRenderFns

                return output;
            }
        }
    }
</script>