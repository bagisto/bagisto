@extends('shop::layouts.master')

@section('page_title')
    {{ __('velocity::app.customer.compare.compare_similar_items') }}
@endsection

@section('content-wrapper')

    <section class="cart-details row no-margin col-12">
        <h1 class="fw6 col-12">
            {{ __('velocity::app.customer.compare.compare_similar_items') }}
        </h1>

        {!! view_render_event('bagisto.shop.customers.account.compare.view.before') !!}

            <compare-product></compare-product>

        {!! view_render_event('bagisto.shop.customers.account.compare.view.after') !!}
    </section>

@endsection

@push('scripts')
    <script type="text/x-template" id="compare-product-template">
        <div class="row compare-products col-12 ml0">
            <div class="col" :key="index" v-for="(product, index) in products">
                <div class="row col-12 pl0">
                    <div class="product-title">
                        <h3>@{{ product.name }}</h3>
                    </div>

                    <div class='image-wrapper'>
                        <img :src="product.image" />
                    </div>

                    <div class="product-price" v-html="product.priceHTML"></div>

                    <div class="product-reviews">
                        <star-ratings :ratings="product.avgRating"></star-ratings>
                        <a class="fs14 align-top unset active-hover" :href="`${$root.baseUrl}/reviews/${product.slug}`">
                            @{{ __('products.reviews-count', {'totalReviews': product.totalReviews}) }}
                        </a>
                    </div>

                    <div class="action">
                        <vnode-injector :nodes="getAddToCartHtml(product.addToCartHtml)"></vnode-injector>
                        <div class="close-btn rango-close fs18 cursor-pointer" @click="removeProductCompare(product.slug)"></div>
                    </div>

                    <div class="product-description">
                        <p v-html="product.description"></p>
                    </div>
                </div>
            </div>

            <span v-if="isProductListLoaded && products.length == 0">
                @{{ __('customer.compare.empty-text') }}
            </span>
        </div>
    </script>

    <script>
        Vue.component('compare-product', {
            template: '#compare-product-template',

            data: function () {
                return {
                    'products': [],
                    'isProductListLoaded': false,
                    'isCustomer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },

            mounted: function () {
                this.getComparedProducts();
            },

            methods: {
                'getComparedProducts': function () {
                    if (this.isCustomer) {
                        var data = {
                            params: {
                                data: true,
                            }
                        };
                    } else {
                        let items = JSON.parse(window.localStorage.getItem('compared_product'));
                        items = items ? items.join('&') : '';

                        var data = {
                            params: {
                                items,
                                data: true,
                            }
                        };
                    }

                    this.$http.get(`${this.$root.baseUrl}/comparison`, data)
                    .then(response => {
                        this.isProductListLoaded = true;
                        this.products = response.data.products
                    })
                    .catch(error => {
                        console.log(this.__('error.something-went-wrong'));
                    });
                },

                'removeProductCompare': function (slug) {
                    if (this.isCustomer) {
                        this.$http.delete(`${this.$root.baseUrl}/comparison?slug=${slug}`)
                        .then(response => {
                            this.$set(this, 'products', this.products.filter(product => product.slug != slug));

                            window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                        })
                        .catch(error => {
                            console.log(this.__('error.something-went-wrong'));
                        });
                    } else {
                        let existingItems = window.localStorage.getItem('compared_product');
                        existingItems = JSON.parse(existingItems);

                        updatedItems = existingItems.filter(item => item != slug);
                        this.$set(this, 'products', this.products.filter(product => product.slug != slug));

                        window.localStorage.setItem('compared_product', JSON.stringify(updatedItems));

                        window.showAlert(
                            `alert-success`,
                            this.__('shop.general.alert.success'),
                            `${this.__('customer.compare.removed')}`
                        );
                    }
                },

                'getAddToCartHtml': function (input) {
                    const { render, staticRenderFns } = Vue.compile(input);
                    const _staticRenderFns = this.$options.staticRenderFns = staticRenderFns;

                    try {
                        var output = render.call(this, this.$createElement)
                    } catch (exception) {
                        console.log(this.__('error.something-went-wrong'));
                    }

                    this.$options.staticRenderFns = _staticRenderFns

                    return output;
                }
            }
        })
    </script>
@endpush