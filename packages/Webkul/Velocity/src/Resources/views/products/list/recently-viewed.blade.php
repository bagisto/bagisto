@inject ('velocityHelper', 'Webkul\Velocity\Helpers\Helper')
@inject ('productRatingHelper', 'Webkul\Product\Helpers\Review')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

<recently-viewed
    add-class="{{ isset($addClass) ? $addClass : '' }}"
    add-class-wrapper="{{ isset($addClassWrapper) ? $addClassWrapper : '' }}">
</recently-viewed>

@push('scripts')
    <script type="text/x-template" id="recently-viewed-template">
        <div :class="`${addClass} recently-viewed`">
            <div class="row remove-padding-margin">
                <div class="col-12 no-padding">
                    <h2 class="fs20 fw6 mb15">{{ __('velocity::app.products.recently-viewed') }}</h2>
                </div>
            </div>

            <div :class="`recetly-viewed-products-wrapper ${addClassWrapper}`">
                <div
                    :key="Math.random()"
                    class="row small-card-container"
                    v-for="(product, index) in recentlyViewed">

                    <div class="col-4 product-image-container mr15">
                        <a :href="`${baseURL}/${product.urlKey}`" class="unset">
                            <div class="product-image" :style="`background-image: url(${product.image})`"></div>
                        </a>
                    </div>

                    <div class="col-8 no-padding card-body align-vertical-top">
                        <a :href="`${baseURL}/${product.urlKey}`" class="unset no-padding">
                            <div class="product-name">
                                <span class="fs16 text-nowrap">@{{ product.name }}</span>
                            </div>

                            <div
                                v-html="product.priceHTML"
                                class="fs18 card-current-price fw6">
                            </div>

                            <star-ratings
                                push-class="display-inbl"
                                :ratings="product.rating">
                            </star-ratings>

                            <span class="fs16 text-nowrap align-text-bottom">@{{ product.totalReviews }} Reviews</span>
                        </a>
                    </div>
                </div>

                <span
                    class="fs16"
                    v-if="!recentlyViewed"
                    v-text="'Not available'">
                </span>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('recently-viewed', {
                template: '#recently-viewed-template',
                props: ['addClass', 'addClassWrapper'],

                data: function () {
                    return {
                        baseURL: '{{ url()->to('/') }}',
                        recentlyViewed: (() => {
                            let storedRecentlyViewed = window.localStorage.recentlyViewed;
                            if (storedRecentlyViewed) {
                                var slugs = JSON.parse(storedRecentlyViewed);
                                var updatedSlugs = {};

                                slugs.forEach(slug => {
                                    updatedSlugs[slug] = {};
                                });

                                return updatedSlugs;
                            }
                        })(),
                    }
                },

                created: function () {
                    // @TODO:- current product and recentlyViewed

                    for (slug in this.recentlyViewed) {
                        if (slug) {
                            this.$http(`${this.baseURL}/product-details/${slug}`)
                            .then(response => {
                                if (response.data.status) {
                                    this.$set(this.recentlyViewed, response.data.details.urlKey, response.data.details);
                                }
                            })
                            .catch(error => {})
                        }
                    }
                },
            })
        })()
    </script>
@endpush
