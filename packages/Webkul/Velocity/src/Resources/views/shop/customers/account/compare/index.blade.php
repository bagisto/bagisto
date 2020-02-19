@extends('shop::customers.account.index')

@section('page_title')
    {{ __('velocity::app.customer.compare.text') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-0">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>
        <span class="account-heading">
            {{ __('velocity::app.customer.compare.text') }}
        </span>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.compare.view.before') !!}

        <compare-product></compare-product>

    {!! view_render_event('bagisto.shop.customers.account.compare.view.after') !!}
@endsection

@push('scripts')
    <script type="text/x-template" id="compare-product-template">
        <div class="account-table-content">
            {{-- https://prnt.sc/r447tc
            https://prnt.sc/r4484e --}}

            <div class="row compare-products col-12">

                <div class="col" :key="index" v-for="(product, index) in products">
                    <div class="row col-12">
                        <div class='image-wrapper'>
                            <img :src="product.image" />
                        </div>

                        <div class="product-title">
                            <h3>@{{ product.name }}</h3>
                        </div>

                        <div class="product-reviews">
                            <star-ratings :ratings="product.avgRating"></star-ratings>
                            <a class="fs14 align-top unset active-hover" :href="`${$root.baseUrl}/reviews/${product.slug}`">
                                @{{ __('products.reviews-count', {'totalReviews': product.totalReviews}) }}
                            </a>
                        </div>

                        <div class="product-price" v-html="product.priceHTML"></div>

                        <div class="product-description">
                            <p v-html="product.description"></p>
                        </div>

                        <div class="add-to-cart" v-html="product.addToCartHtml"></div>

                        <div class="close-btn rango-close fs18 cursor-pointer" @click="removeProductCompare(product.slug)"></div>
                    </div>
                </div>

                <span v-if="isProductListLoaded && products.length == 0">
                    @{{ __('customer.compare.empty-text') }}
                </span>
            </div>
        </div>
    </script>

    <script>
        Vue.component('compare-product', {
            template: '#compare-product-template',

            data: function () {
                return {
                    'products': [],
                    'isProductListLoaded': false,
                }
            },

            mounted: function () {
                this.getComparedProducts();
            },

            methods: {
                'getComparedProducts': function () {
                    this.$http.get(`${this.$root.baseUrl}/customer/account/compare?data=true`, {data: true})
                    .then(response => {
                        this.isProductListLoaded = true;
                        this.products = response.data.products
                    })
                    .catch(error => {
                        console.log(this.__('error.something-went-wrong'));
                    });
                },

                'removeProductCompare': function (slug) {
                    this.$http.delete(`${this.$root.baseUrl}/customer/account/compare?slug=${slug}`)
                    .then(response => {
                        this.$set(this, 'products', this.products.filter(product => product.slug != slug));

                        window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                    })
                    .catch(error => {
                        console.log(this.__('error.something-went-wrong'));
                    });
                }
            }
        })
    </script>
@endpush