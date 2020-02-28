@extends('shop::layouts.master')

@section('page_title')
    {{ __('velocity::app.customer.compare.compare_similar_items') }}
@endsection

@section('content-wrapper')
    @php
        $attributeRepository = app('\Webkul\Attribute\Repositories\AttributeRepository');
        $comparableAttributes = $attributeRepository->findByField('is_comparable', 1);
    @endphp

    <compare-product></compare-product>
@endsection

@push('scripts')
    <script type="text/x-template" id="compare-product-template">
        <section class="cart-details row no-margin col-12">
            <h1 class="fw6 col-6">
                {{ __('velocity::app.customer.compare.compare_similar_items') }}
            </h1>

            <div class="col-6" v-if="products.length > 0">
                <button class="theme-btn light pull-right" @click="removeProductCompare('all')">Clear All</button>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.compare.view.before') !!}

            <div class="row compare-products col-12 ml0">
                <template v-if="products.length > 0">
                    @php
                        $comparableAttributes = $comparableAttributes->toArray();

                        array_splice($comparableAttributes, 1, 0, [[
                            'code' => 'image',
                            'admin_name' => 'Product Image'
                        ]]);

                        array_splice($comparableAttributes, 2, 0, [[
                            'code' => 'addToCartHtml',
                            'admin_name' => 'Actions'
                        ]]);
                    @endphp

                    @foreach ($comparableAttributes as $attribute)
                        <div class="row col-12 pr-0 mt15">
                            <div class="col-2">
                                <span class="fs16">{{ $attribute['admin_name'] }}</span>
                            </div>

                            <div class="col" :key="`title-${index}`" v-for="(product, index) in products">
                                @if ($attribute['code'] == 'name')
                                    <a :href="`${$root.baseUrl}/${isCustomer ? product.url_key : product.slug}`" class="unset">
                                        <h1 class="fw6 fs18" v-text="product['{{ $attribute['code'] }}']"></h1>
                                    </a>
                                @elseif ($attribute['code'] == 'image')
                                    <a :href="`${$root.baseUrl}/${isCustomer ? product.url_key : product.slug}`" class="unset">
                                        <img :src="product['{{ $attribute['code'] }}']" class="image-wrapper"></span>
                                    </a>
                                @elseif ($attribute['code'] == 'price')
                                    <span v-html="product['priceHTML']"></span>
                                @elseif ($attribute['code'] == 'addToCartHtml')
                                    <div v-html="product['addToCartHtml']"></div>
                                    <i class="material-icons cross fs16" @click="removeProductCompare(isCustomer ? product.id : product.slug)">close</i>
                                @else
                                    <span v-html="product['{{ $attribute['code'] }}']"></span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </template>

                <span v-if="isProductListLoaded && products.length == 0">
                    @{{ __('customer.compare.empty-text') }}
                </span>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.compare.view.after') !!}
        </section>
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

                'removeProductCompare': function (productId) {
                    if (this.isCustomer) {
                        this.$http.delete(`${this.$root.baseUrl}/comparison?productId=${productId}`)
                        .then(response => {
                            if (productId == 'all') {
                                this.$set(this, 'products', this.products.filter(product => false));
                            } else {
                                this.$set(this, 'products', this.products.filter(product => product.id != productId));
                            }

                            window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                        })
                        .catch(error => {
                            console.log(this.__('error.something-went-wrong'));
                        });
                    } else {
                        let existingItems = window.localStorage.getItem('compared_product');
                        existingItems = JSON.parse(existingItems);

                        if (productId == "all") {
                            updatedItems = [];
                            this.$set(this, 'products', []);
                        } else {
                            updatedItems = existingItems.filter(item => item != productId);
                            this.$set(this, 'products', this.products.filter(product => product.slug != productId));
                        }

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
        });
    </script>
@endpush