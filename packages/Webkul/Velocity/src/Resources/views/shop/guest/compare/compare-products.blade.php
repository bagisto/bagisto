@php
    $attributeRepository = app('\Webkul\Attribute\Repositories\AttributeRepository');
    $comparableAttributes = $attributeRepository->findByField('is_comparable', 1);
@endphp

@push('css')
    <style>
        .btn-add-to-cart {
            max-width: 130px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
@endpush

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

            <table class="row compare-products">
                <shimmer-component v-if="!isProductListLoaded && !isMobile()"></shimmer-component>

                <template v-else-if="isProductListLoaded && products.length > 0">
                    @php
                        $comparableAttributes = $comparableAttributes->toArray();

                        array_splice($comparableAttributes, 1, 0, [[
                            'admin_name' => 'Product Image',
                            'type' => 'product_image'
                        ]]);

                        array_splice($comparableAttributes, 2, 0, [[
                            'admin_name' => 'Actions',
                            'type' => 'action'
                        ]]);
                    @endphp

                    @foreach ($comparableAttributes as $attribute)
                        <tr>
                            <td>
                                <span class="fs16">{{ isset($attribute['name']) ? $attribute['name'] : $attribute['admin_name'] }}</span>
                            </td>

                            <td :key="`title-${index}`" v-for="(product, index) in products">
                                @switch ($attribute['type'])
                                    @case('text')
                                        <a :href="`${baseUrl}/${product.url_key}`" class="unset remove-decoration active-hover">
                                            <h3 class="fw6 fs18" v-text="product['{{ $attribute['code'] }}']"></h3>
                                        </a>
                                        @break;

                                    @case('textarea')
                                        <span v-html="product.product['{{ $attribute['code'] }}']"></span>
                                        @break;
    
                                    @case('price')
                                        <span v-html="product.product['{{ $attribute['code'] }}']"></span>
                                        @break;

                                    @case('boolean')
                                        <span
                                            v-text="product.product['{{ $attribute['code'] }}']
                                                    ? '{{ __('velocity::app.shop.general.yes') }}'
                                                    : '{{ __('velocity::app.shop.general.no') }}'"
                                        ></span>
                                        @break;
                                    
                                    @case('select')
                                        <span v-html="product.product['{{ $attribute['code'] }}']" class="fs16"></span>
                                        @break;

                                    @case('multiselect')
                                        <span v-html="product.product['{{ $attribute['code'] }}']" class="fs16"></span>
                                        @break

                                    @case('file')
                                        <a v-if="product.product['{{ $attribute['code'] }}']" :href="`${baseUrl}/storage/${product.product['{{ $attribute['code'] }}']}`">
                                            <span v-text="product.product['{{ $attribute['code'] }}'].substr(product.product['{{ $attribute['code'] }}'].lastIndexOf('/') + 1)"  class="fs16"></span>
                                            <i class='icon sort-down-icon download'></i>
                                        </a>
                                        <span v-else class="fs16">__</span>
                                        @break;
                                        
                                    @case('image')
                                        <img v-if="product.product['{{ $attribute['code'] }}']" :src="`${baseUrl}/storage/${product.product['{{ $attribute['code'] }}']}`">
                                        @break;
                                    
                                    @case('product_image')
                                        <a :href="`${baseUrl}/${product.url_key}`" class="unset">
                                            <img
                                                class="image-wrapper"
                                                :src="product['product_image']"
                                                :onerror="`this.src='${baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />
                                        </a>
                                    @break

                                    @case('action')
                                        <div class="action">
                                            <div v-html="product.defaultAddToCart"></div>

                                            <span class="icon white-cross-sm-icon remove-product" @click="removeProductCompare(product.id)"></span>
                                        </div>
                                        @break;

                                @endswitch 
                            </td>
                        </tr>
                    @endforeach
                </template>

                <span v-else-if="isProductListLoaded && products.length == 0" class="col-12">
                    @{{ __('customer.compare.empty-text') }}
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

                            if (response.data.products.length > 3) {
                                $('.compare-products').css('overflow-x', 'scroll');
                            }

                            this.products = response.data.products;
                        })
                        .catch(error => {
                            this.isProductListLoaded = true;
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