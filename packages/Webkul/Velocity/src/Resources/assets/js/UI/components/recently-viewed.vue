<template>
    <div :class="addClass">
        <div class="row remove-padding-margin">
            <div class="col-12 no-padding">
                <h2 class="fs20 fw6 mb15" v-text="title"></h2>
            </div>
        </div>

        <div :class="`recently-viewed-products-wrapper ${addClassWrapper}`">
            <div
                :key="Math.random()"
                class="row small-card-container"
                v-for="(product, index) in recentlyViewed">

                <div class="col-4 product-image-container mr15">
                    <a :href="`${baseUrl}/${product.urlKey}`" class="unset">
                        <div class="product-image" :style="`background-image: url(${product.image})`"></div>
                    </a>
                </div>

                <div class="col-8 no-padding card-body align-vertical-top" v-if="product.urlKey">
                    <a :href="`${baseUrl}/${product.urlKey}`" class="unset no-padding">
                        <div class="product-name">
                            <span class="fs16 text-nowrap" v-text="product.name"></span>
                        </div>

                        <div
                            v-html="product.priceHTML"
                            class="fs18 card-current-price fw6">
                        </div>

                        <star-ratings v-if="product.rating > 0"
                            push-class="display-inbl"
                            :ratings="product.rating">
                        </star-ratings>
                    </a>
                </div>
            </div>

            <span
                class="fs16"
                v-if="!recentlyViewed ||(recentlyViewed && Object.keys(recentlyViewed).length == 0)"
                v-text="noDataText">
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'title',
            'noDataText',
            'quantity',
            'addClass',
            'addClassWrapper'
        ],

        data: function () {
            return {
                recentlyViewed: (() => {
                    let storedRecentlyViewed = window.localStorage.recentlyViewed;
                    if (storedRecentlyViewed) {
                        var slugs = JSON.parse(storedRecentlyViewed);
                        var updatedSlugs = {};

                        slugs = slugs.reverse();

                        slugs.forEach(slug => {
                            updatedSlugs[slug] = {};
                        });

                        return updatedSlugs;
                    }
                })(),
            }
        },

        created: function () {
            for (const slug in this.recentlyViewed) {
                if (slug) {
                    this.$http(`${this.baseUrl}/product-details/${slug}`)
                    .then(response => {
                        if (response.data.status) {
                            this.$set(this.recentlyViewed, response.data.details.urlKey, response.data.details);
                        } else {
                            delete this.recentlyViewed[response.data.slug];
                            this.$set(this, 'recentlyViewed', this.recentlyViewed);

                            this.$forceUpdate();
                        }
                    })
                    .catch(error => {})
                }
            }
        },
    }
</script>