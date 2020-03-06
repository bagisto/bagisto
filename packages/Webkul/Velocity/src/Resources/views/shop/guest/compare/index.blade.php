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
                <button
                    class="theme-btn light pull-right"
                    @click="removeProductCompare('all')">
                    {{ __('shop::app.customer.account.wishlist.deleteall') }}
                </button>
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
                                @switch ($attribute['code'])
                                    @case('name')
                                        <a :href="`${$root.baseUrl}/${product.url_key}`" class="unset remove-decoration active-hover">
                                            <h1 class="fw6 fs18" v-text="product['{{ $attribute['code'] }}']"></h1>
                                        </a>
                                        @break

                                    @case('image')
                                        <a :href="`${$root.baseUrl}/${product.url_key}`" class="unset">
                                            <img :src="product['{{ $attribute['code'] }}']" class="image-wrapper"></span>
                                        </a>
                                        @break

                                    @case('price')
                                        <span v-html="product['priceHTML']"></span>
                                        @break

                                    @case('addToCartHtml')
                                        <div class="action">
                                            <vnode-injector :nodes="getDynamicHTML(product.addToCartHtml)"></vnode-injector>

                                            <i
                                                class="material-icons cross fs16"
                                                @click="removeProductCompare(product.id)">

                                                close
                                            </i>
                                        </div>
                                        @break

                                    @case('color')
                                        <span v-html="product.color_label" class="fs16"></span>
                                        @break

                                    @case('size')
                                        <span v-html="product.size_label" class="fs16"></span>
                                        @break

                                    @case('description')
                                        <span v-html="product.description"></span>
                                        @break

                                    @default
                                        <span v-html="product['{{ $attribute['code'] }}']" class="fs16"></span>
                                        @break

                                @endswitch
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
                    let items = '';
                    let url = `${this.$root.baseUrl}/${this.isCustomer ? 'comparison' : 'detailed-products'}`;

                    let data = {
                        params: {'data': true}
                    }

                    if (! this.isCustomer) {
                        items = this.getStorageValue('compared_product');
                        items = items ? items.join('&') : '';

                        data = {
                            params: {
                                items
                            }
                        };
                    }

                    if (this.isCustomer || (! this.isCustomer && items != "")) {
                        this.$http.get(url, data)
                        .then(response => {
                            this.isProductListLoaded = true;
                            this.products = response.data.products;
                        })
                        .catch(error => {
                            console.log(this.__('error.something_went_wrong'));
                        });
                    } else {
                        this.isProductListLoaded = true;
                    }

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
                            console.log(this.__('error.something_went_wrong'));
                        });
                    } else {
                        let existingItems = this.getStorageValue('compared_product');

                        if (productId == "all") {
                            updatedItems = [];
                            this.$set(this, 'products', []);
                        } else {
                            updatedItems = existingItems.filter(item => item != productId);
                            this.$set(this, 'products', this.products.filter(product => product.id != productId));
                        }

                        this.setStorageValue('compared_product', updatedItems);

                        window.showAlert(
                            `alert-success`,
                            this.__('shop.general.alert.success'),
                            `${this.__('customer.compare.removed')}`
                        );
                    }

                    this.$root.headerItemsCount++;
                },
            }
        });
    </script>
@endpush