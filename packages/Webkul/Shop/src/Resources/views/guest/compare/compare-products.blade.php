@php
    $attributeRepository = app('\Webkul\Attribute\Repositories\AttributeFamilyRepository');

    $comparableAttributes = $attributeRepository->getComparableAttributesBelongsToFamily();

    $locale = core()->getRequestedLocaleCode();

    $attributeOptionTranslations = DB::table('attribute_option_translations')->where('locale', $locale)->get();
@endphp

@push('scripts')
    <script type="text/x-template" id="compare-product-template">
        <section class="comparison-component">
            <h1>
                {{ __('shop::app.customer.compare.compare_similar_items') }}
            </h1>

            <button
                v-if="products.length > 0"
                class="btn btn-primary btn-md {{ core()->getCurrentLocale()->direction == 'rtl' ? 'pull-left' : 'pull-right' }}"
                @click="removeProductCompare('all')">
                {{ __('shop::app.customer.account.wishlist.deleteall') }}
            </button>

            {!! view_render_event('bagisto.shop.customers.account.compare.view.before') !!}

            <table class="compare-products">
                <template v-if="isProductListLoaded && products.length > 0">
                    @php
                        $comparableAttributes = $comparableAttributes->toArray();

                        array_splice($comparableAttributes, 1, 0, [[
                            'code' => 'product_image',
                            'admin_name' => __('velocity::app.customer.compare.product_image'),
                        ]]);

                        array_splice($comparableAttributes, 2, 0, [[
                            'code' => 'addToCartHtml',
                            'admin_name' => __('velocity::app.customer.compare.actions'),
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
                                            <h3 class="fw6 fs18 mt-0" v-text="product['{{ $attribute['code'] }}']"></h3>
                                        </a>
                                        @break

                                    @case('product_image')
                                        <a :href="`${baseUrl}/${product.url_key}`" class="unset">
                                            <img
                                                class="image-wrapper"
                                                :src="product['{{ $attribute['code'] }}']"
                                                :onerror="`this.src='${baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" alt="" />
                                        </a>
                                        @break

                                    @case('price')
                                        <span v-html="product['priceHTML']"></span>
                                        @break

                                    @case('addToCartHtml')
                                        <div class="action">
                                            <div>
                                                <span class="d-inline-block">
                                                    <form :action="`${baseUrl}/checkout/cart/add/${product.product_id}`" method="POST">
                                                        @csrf

                                                        <input type="hidden" name="product_id" :value="product.product_id">

                                                        <input type="hidden" name="quantity" value="1">

                                                        <span v-html="product.addToCartHtml"></span>
                                                    </form>
                                                </span>

                                                <span class="icon white-cross-sm-icon remove-product" @click="removeProductCompare(product.id)"></span>
                                            </div>
                                        </div>
                                        @break

                                    @case('color')
                                        <span v-html="product.color_label" class="fs16"></span>
                                        @break

                                    @case('size')
                                        <span v-html="product.size_label" class="fs16"></span>
                                        @break

                                    @case('description')
                                        <span v-html="product.description" class="desc"></span>
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

                                            @case('checkbox')
                                                <span v-if="product.product['{{ $attribute['code'] }}']" v-html="getAttributeOptions(product['{{ $attribute['code'] }}'] ? product : product.product['{{ $attribute['code'] }}'] ? product.product : null, '{{ $attribute['code'] }}', 'multiple')" class="fs16"></span>
                                                <span v-else class="fs16">__</span>
                                                @break;

                                            @case('select')
                                                <span v-if="product.product['{{ $attribute['code'] }}']" v-html="getAttributeOptions(product['{{ $attribute['code'] }}'] ? product : product.product['{{ $attribute['code'] }}'] ? product.product : null, '{{ $attribute['code'] }}', 'single')" class="fs16"></span>
                                                <span v-else class="fs16">__</span>
                                                @break;

                                            @case('multiselect')
                                                <span v-if="product.product['{{ $attribute['code'] }}']" v-html="getAttributeOptions(product['{{ $attribute['code'] }}'] ? product : product.product['{{ $attribute['code'] }}'] ? product.product : null, '{{ $attribute['code'] }}', 'multiple')" class="fs16"></span>
                                                <span v-else class="fs16">__</span>
                                                @break

                                            @case ('file')
                                            @case ('image')
                                                <a :href="`${baseUrl}/${product.url_key}`" class="unset">
                                                    <img
                                                        class="image-wrapper"
                                                        :src="storageUrl + product.product['{{ $attribute['code'] }}']"
                                                        :onerror="`this.src='${baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" alt="" />
                                                </a>
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
                    {{ __('shop::app.customer.compare.empty-text') }}
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
                    'baseUrl': '{{ url()->to('/') }}',
                    'storageUrl': '{{ Storage::url('/') }}',
                    'isCustomer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == 'true',
                    'isProductListLoaded': false,
                    'attributeOptions': @json($attributeOptionTranslations),
                };
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
                            console.log("{{ __('shop::app.common.error') }}");
                        });
                    } else {
                        this.isProductListLoaded = true;
                    }
                },

                'removeProductCompare': function (productId) {
                    if (productId == 'all' && ! confirm('{{ __('shop::app.customer.compare.confirm-remove-all') }}')) {
                        return;
                    }

                    if (this.isCustomer) {
                        this.$http.delete(`${this.baseUrl}/comparison?productId=${productId}`)
                        .then(response => {
                            if (productId == 'all') {
                                this.$set(this, 'products', this.products.filter(product => false));
                            } else {
                                this.$set(this, 'products', this.products.filter(product => product.id != productId));
                            }

                            window.flashMessages = [{'type': 'alert-success', 'message': response.data.message }];

                            this.updateCompareCount();

                            this.$root.addFlashMessages();
                        })
                        .catch(error => {
                            console.log("{{ __('shop::app.common.error') }}");
                        });
                    } else {
                        let existingItems = this.getStorageValue('compared_product');

                        if (productId == "all") {
                            updatedItems = [];
                            this.$set(this, 'products', []);
                            window.flashMessages = [{'type': 'alert-success', 'message': '{{ __('shop::app.customer.compare.removed-all') }}' }];
                        } else {
                            updatedItems = existingItems.filter(item => item != productId);
                            this.$set(this, 'products', this.products.filter(product => product.id != productId));
                            window.flashMessages = [{'type': 'alert-success', 'message': '{{ __('shop::app.customer.compare.removed') }}' }];
                        }

                        this.setStorageValue('compared_product', updatedItems);

                        this.$root.addFlashMessages();
                    }

                    this.updateCompareCount();
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

                'getAttributeOptions': function (productDetails, attributeValues, type) {
                    var attributeOptions = '__';

                    if (productDetails && attributeValues) {
                        var attributeItems;

                        if (type == "multiple") {
                            attributeItems = productDetails[attributeValues].split(',');
                        } else if (type == "single") {
                            attributeItems = productDetails[attributeValues];
                        }

                        attributeOptions = this.attributeOptions.filter(option => {
                            if (type == "multiple") {
                                if (attributeItems.indexOf(option.attribute_option_id.toString()) > -1) {
                                    return true;
                                }
                            } else if (type == "single") {
                                if (attributeItems == option.attribute_option_id.toString()) {
                                    return true;
                                }
                            }

                            return false;
                        });

                        attributeOptions = attributeOptions.map(option => {
                            return option.label;
                        });

                        attributeOptions = attributeOptions.join(', ');
                    }

                    return attributeOptions;
                },

                'updateCompareCount': function () {
                    if (this.isCustomer == "true" || this.isCustomer == true) {
                        this.$http.get(`${this.baseUrl}/items-count`)
                        .then(response => {
                            $('#compare-items-count').html(response.data.compareProductsCount);
                        })
                        .catch(exception => {
                            window.flashMessages = [{
                                'type': `alert-error`,
                                'message': "{{ __('shop::app.common.error') }}"
                            }];

                            this.$root.addFlashMessages();
                        });
                    } else {
                        let comparedItems = JSON.parse(localStorage.getItem('compared_product'));
                        comparedItemsCount = comparedItems ? comparedItems.length : 0;

                        $('#compare-items-count').html(comparedItemsCount);
                    }
                }
            }
        });
    </script>
@endpush
