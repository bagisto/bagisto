<template>
    <!-- @WIP -->
    <div class="col-12 lg-card-container list-card product-card row" v-if="list">
        <div class="product-image">
            <a :title="productName" :href="productUrl">
                <img :src="productImg" />
            </a>
        </div>

        <div class="product-information">
            <div>
                <div class="product-name">
                    <a :href="productUrl" :title="productName" class="unset">
                        <span class="fs16">{{ productName }}</span>
                    </a>
                </div>

                <div class="product-price" v-html="priceHtml"></div>

                <div class="product-rating" v-if="totalReviews && totalReviews > 0">
                    <star-ratings :ratings="avgRating"></star-ratings>
                    <span>{{ totalReviews }} Ratings</span>
                </div>

                <div class="product-rating" v-else>
                    <span class="fs14" v-text="firstReviewText"></span>
                </div>

                <div class="cart-wish-wrap row mt5" v-html="addToCartHtml"></div>
            </div>
        </div>
    </div>

    <div class="card grid-card product-card-new" v-else>
        <a :href="productUrl" :title="productName" class="product-image-container">
            <img
                loading="lazy"
                :src="productImg"
                :alt="productName"
                class="card-img-top">

            <!-- <quick-view-btn details="{{ $product }}"></quick-view-btn> -->
        </a>

        <div class="card-body">
            <div class="product-name col-12 no-padding">
                <a
                    class="unset"
                    :href="productUrl"
                    :title="productName">

                    <span class="fs16">{{ productName }}</span>
                </a>
            </div>

            <div class="product-price fs16" v-html="priceHtml"></div>

            <div class="product-rating col-12 no-padding" v-if="totalReviews && totalReviews > 0">
                <star-ratings :ratings="avgRating"></star-ratings>
                <span class="align-top">{{ totalReviews }} Ratings</span>
            </div>

            <div class="product-rating col-12 no-padding" v-else>
                <span class="fs14" v-text="firstReviewText"></span>
            </div>

            <div class="cart-wish-wrap row col-12 no-padding ml0" v-html="addToCartHtml"></div>
        </div>
    </div>
</template>

<script type="text/javascript">
    // compile add to cart html (it contains wishlist component)
    export default {
        props: [
            'list',
            'productImg',
            'productUrl',
            'priceHtml',
            'addToCartHtml',
            'productName',
            'totalReviews',
            'avgRating',
            'firstReviewText',
        ],

        data: function () {
            return {
            }
        }
    }
</script>