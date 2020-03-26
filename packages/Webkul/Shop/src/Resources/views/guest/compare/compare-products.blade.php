@php
    $attributeRepository = app('\Webkul\Attribute\Repositories\AttributeRepository');
    $comparableAttributes = $attributeRepository->findByField('is_comparable', 1);
@endphp

@push('css')
    <style>
        body {
            overflow-x: hidden;
        }

        .comparison-component {
            width: 100%;
            padding-top: 20px;
        }

        .comparison-component > h1 {
            display: inline-block;
        }

        td {
            padding: 15px;
            min-width: 250px;
            max-width: 250px;
            line-height: 30px;
            vertical-align: top;
            word-break: break-word;
        }

        .icon.remove-product {
            top: 15px;
            float: right;
            cursor: pointer;
            position: relative;
            background-color: black;
        }

        .action > div {
            display: inline-block;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/x-template" id="compare-product-template">
        <section class="comparison-component">
            <h1>
                {{ __('velocity::app.customer.compare.compare_similar_items') }}
            </h1>

            <button
                v-if="products.length > 0"
                class="btn btn-primary btn-md pull-right"
                @click="removeProductCompare('all')">
                {{ __('shop::app.customer.account.wishlist.deleteall') }}
            </button>

            {!! view_render_event('bagisto.shop.customers.account.compare.view.before') !!}

            <table class="compare-products">
                <template v-if="isProductListLoaded && products.length > 0">
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
                        <tr>
                            <td>
                                <span class="fs16">{{ $attribute['admin_name'] }}</span>
                            </td>

                            <td :key="`title-${index}`" v-for="(product, index) in products">
                                @switch ($attribute['code'])
                                    @case('name')
                                        <a :href="`${baseUrl}/${product.url_key}`" class="unset remove-decoration active-hover">
                                            <h3 class="fw6 fs18" v-text="product['{{ $attribute['code'] }}']"></h3>
                                        </a>
                                        @break

                                    @case('image')
                                        <a :href="`${baseUrl}/${product.url_key}`" class="unset">
                                            <img
                                                class="image-wrapper"
                                                :src="product['{{ $attribute['code'] }}']"
                                                :onerror="`this.src='${baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />
                                        </a>
                                        @break

                                    @case('price')
                                        <span v-html="product['priceHTML']"></span>
                                        @break

                                    @case('addToCartHtml')
                                        <div class="action">
                                            <div v-html="product.defaultAddToCart"></div>

                                            <span class="icon white-cross-sm-icon remove-product" @click="removeProductCompare(product.id)"></span>
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
                                        @switch ($attribute['type'])
                                            @case('boolean')
                                                <span
                                                    v-text="product.product['{{ $attribute['code'] }}']
                                                            ? '{{ __('velocity::app.shop.general.yes') }}'
                                                            : '{{ __('velocity::app.shop.general.no') }}'"
                                                ></span>
                                                @break;
                                            @default
                                                <span v-html="product['{{ $attribute['code'] }}'] ? product['{{ $attribute['code'] }}'] : product.product['{{ $attribute['code'] }}'] ? product.product['{{ $attribute['code'] }}'] : '__'" class="fs16"></span>
                                                @break;
                                        @endswitch

                                        @break

                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </template>

                <span v-else-if="isProductListLoaded && products.length == 0">
                    {{ __('velocity::app.customer.compare.empty-text') }}
                </span>
            </table>

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
                    'baseUrl': "{{ url()->to('/') }}",
                    'isCustomer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },

            mounted: function () {
                this.getComparedProducts();
            },

            methods: {
                'getComparedProducts': function () {
                    let items = '';
                    let url = `${this.baseUrl}/${this.isCustomer ? 'comparison' : 'detailed-products'}`;

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

                            if (response.data.products.length > 2) {
                                $('.comparison-component').css('overflow-x', 'scroll');
                            }

                            this.products = response.data.products;
                        })
                        .catch(error => {
                            this.isProductListLoaded = true;
                            console.log("{{ __('velocity::app.error.something_went_wrong') }}");
                        });
                    } else {
                        this.isProductListLoaded = true;
                    }

                },

                'removeProductCompare': function (productId) {
                    if (this.isCustomer) {
                        this.$http.delete(`${this.baseUrl}/comparison?productId=${productId}`)
                        .then(response => {
                            if (productId == 'all') {
                                this.$set(this, 'products', this.products.filter(product => false));
                            } else {
                                this.$set(this, 'products', this.products.filter(product => product.id != productId));
                            }

                            // window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                        })
                        .catch(error => {
                            console.log("{{ __('velocity::app.error.something_went_wrong') }}");
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

                        // window.showAlert(
                        //     `alert-success`,
                        //     "{{ __('velocity::app.shop.general.alert.success') }}",
                        //     `${this.__('customer.compare.removed')}`
                        // );
                    }
                },

                'getDynamicHTML': function (input) {
                    var _staticRenderFns;
                    const { render, staticRenderFns } = Vue.compile(input);

                    if (this.$options.staticRenderFns.length > 0) {
                        _staticRenderFns = this.$options.staticRenderFns;
                    } else {
                        _staticRenderFns = this.$options.staticRenderFns = staticRenderFns;
                    }

                    try {
                        var output = render.call(this, this.$createElement);
                    } catch (exception) {
                        console.log(this.__('error.something_went_wrong'));
                    }

                    this.$options.staticRenderFns = _staticRenderFns;

                    return output;
                },

                'isMobile': function () {
                    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                    } else {
                    return false
                    }
                },

                'getStorageValue': function (key) {
                    let value = window.localStorage.getItem(key);

                    if (value) {
                        value = JSON.parse(value);
                    }

                    return value;
                },

                'setStorageValue': function (key, value) {
                    window.localStorage.setItem(key, JSON.stringify(value));

                    return true;
                },
            }
        });
    </script>
@endpush