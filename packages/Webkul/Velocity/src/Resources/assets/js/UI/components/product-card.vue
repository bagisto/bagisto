<template>
    <div class="col-12 lg-card-container list-card product-card row" v-if="list">
        <div class="product-image">
            <a :title="product.name" :href="`${baseUrl}/${product.slug}`">
                <img :src="product.image" />
            </a>
        </div>

        <div class="product-information">
            <div>
                <div class="product-name">
                    <a :href="`${baseUrl}/${product.slug}`" :title="product.name" class="unset">
                        <span class="fs16">{{ product.name }}</span>
                    </a>
                </div>

                <div class="product-price" v-html="product.priceHTML"></div>

                <div class="product-rating" v-if="product.totalReviews && product.totalReviews > 0">
                    <star-ratings :ratings="product.avgRating"></star-ratings>
                    <span>{{ product.totalReviews }} Ratings</span>
                </div>

                <div class="product-rating" v-else>
                    <span class="fs14" v-text="product.firstReviewText"></span>
                </div>

                <div class="cart-wish-wrap row mt5" v-html="product.addToCartHtml"></div>
            </div>
        </div>
    </div>

    <div class="card grid-card product-card-new" v-else>
        <a :href="`${baseUrl}/${product.slug}`" :title="product.name" class="product-image-container">
            <img
                loading="lazy"
                :src="product.image"
                :alt="product.name"
                class="card-img-top">

            <!-- <quick-view-btn details="{{ $product }}"></quick-view-btn> -->
        </a>

        <div class="card-body">
            <div class="product-name col-12 no-padding">
                <a
                    class="unset"
                    :title="product.name"
                    :href="`${baseUrl}/${product.slug}`">

                    <span class="fs16">{{ product.name }}</span>
                </a>
            </div>

            <div class="product-price fs16" v-html="product.priceHTML"></div>

            <div class="product-rating col-12 no-padding" v-if="product.totalReviews && product.totalReviews > 0">
                <star-ratings :ratings="product.avgRating"></star-ratings>
                <span class="align-top">{{ product.totalReviews }} Ratings</span>
            </div>

            <div class="product-rating col-12 no-padding" v-else>
                <span class="fs14" v-text="product.firstReviewText"></span>
            </div>

            <div class="cart-wish-wrap row col-12 no-padding ml0" v-html="product.addToCartHtml"></div>
        </div>
    </div>
</template>

<script type="text/javascript">
    // compile add to cart html (it contains wishlist component)
    export default {
        props: [
            'list',
            'product',
        ],
    }
</script>