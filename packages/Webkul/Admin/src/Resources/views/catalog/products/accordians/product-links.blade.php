<accordian :title="'{{ __('admin::app.catalog.products.product-link') }}'" :active="true">
    <div slot="body">

        <linked-products></linked-products>

    </div>
</accordian>

@push('scripts')

<script type="text/x-template" id="linked-products-template">
    <div>

        <div class="control-group" v-for='(key) in linkedProducts'>
            <label for="up-selling" v-if="(key == 'up_sells')">
                {{ __('admin::app.catalog.products.up-selling') }}
            </label>
            <label for="cross_sells" v-if="(key == 'cross_sells')">
                {{ __('admin::app.catalog.products.cross-selling') }}
            </label>
            <label for="related_products" v-if="(key == 'related_products')">
                {{ __('admin::app.catalog.products.related-products') }}
            </label>

            <input type="text" class="control" autocomplete="off" v-model="search_term[key]" placeholder="{{ __('admin::app.catalog.products.product-search-hint') }}" v-on:keyup="search(key)">

            <div class="linked-product-search-result">
                <ul>
                    <li v-for='(product, index) in products[key]' v-if='products[key].length' @click="addProduct(product, key)">
                        @{{ product.name }}
                    </li>

                    <li v-if='!products[key].length && search_term[key].length && !is_searching[key]'>
                        {{ __('admin::app.catalog.products.no-result-found') }}
                    </li>

                    <li v-if="is_searching[key] && search_term[key].length">
                        {{ __('admin::app.catalog.products.searching') }}
                    </li>
                </ul>
            </div>

            <input type="hidden" name="up_sell[]" v-for='(product, index) in addedProducts.up_sells' v-if="(key == 'up_sells') && addedProducts.up_sells.length" :value="product.id"/>

            <input type="hidden" name="cross_sell[]" v-for='(product, index) in addedProducts.cross_sells' v-if="(key == 'cross_sells') && addedProducts.cross_sells.length" :value="product.id"/>

            <input type="hidden" name="related_products[]" v-for='(product, index) in addedProducts.related_products' v-if="(key == 'related_products') && addedProducts.related_products.length" :value="product.id"/>

            <span class="filter-tag" style="text-transform: capitalize; margin-top: 10px; margin-right: 0px; justify-content: flex-start" v-if="addedProducts[key].length">
                <span class="wrapper" style="margin-left: 0px; margin-right: 10px;" v-for='(product, index) in addedProducts[key]'>
                    @{{ product.name }}
                <span class="icon cross-icon" @click="removeProduct(product, key)"></span>
                </span>
            </span>
        </div>

    </div>
</script>

<script>

    Vue.component('linked-products', {

        template: '#linked-products-template',

        data: () => ({
            products: {
                'cross_sells': [],
                'up_sells': [],
                'related_products': []
            },

            search_term: {
                'cross_sells': '',
                'up_sells': '',
                'related_products': ''
            },

            addedProducts: {
                'cross_sells': [],
                'up_sells': [],
                'related_products': []
            },

            is_searching: {
                'cross_sells': false,
                'up_sells': false,
                'related_products': false
            },

            productId: {{ $product->id }},

            linkedProducts: ['up_sells', 'cross_sells', 'related_products'],

            upSellingProducts: @json($product->up_sells()->get()),

            crossSellingProducts: @json($product->cross_sells()->get()),

            relatedProducts: @json($product->related_products()->get()),
        }),

        created () {
            if (this.upSellingProducts.length >= 1) {
                for (var index in this.upSellingProducts) {
                    this.addedProducts.up_sells.push(this.upSellingProducts[index]);
                }
            }

            if (this.crossSellingProducts.length >= 1) {
                for (var index in this.crossSellingProducts) {
                    this.addedProducts.cross_sells.push(this.crossSellingProducts[index]);
                }
            }

            if (this.relatedProducts.length >= 1) {
                for (var index in this.relatedProducts) {
                    this.addedProducts.related_products.push(this.relatedProducts[index]);
                }
            }
        },

        methods: {
            addProduct (product, key) {
                this.addedProducts[key].push(product);
                this.search_term[key] = '';
                this.products[key] = []
            },

            removeProduct (product, key) {
                for (var index in this.addedProducts[key]) {
                    if (this.addedProducts[key][index].id == product.id ) {
                        this.addedProducts[key].splice(index, 1);
                    }
                }
            },

            search (key) {
                this_this = this;

                this.is_searching[key] = true;

                if (this.search_term[key].length >= 1) {
                    this.$http.get ("{{ route('admin.catalog.products.productlinksearch') }}", {params: {query: this.search_term[key]}})
                        .then (function(response) {

                            for (var index in response.data) {
                                if (response.data[index].id == this_this.productId) {
                                    response.data.splice(index, 1);
                                }
                            }

                            if (this_this.addedProducts[key].length) {
                                for (var product in this_this.addedProducts[key]) {
                                    for (var productId in response.data) {
                                        if (response.data[productId].id == this_this.addedProducts[key][product].id) {
                                            response.data.splice(productId, 1);
                                        }
                                    }
                                }
                            }

                            this_this.products[key] = response.data;

                            this_this.is_searching[key] = false;
                        })

                        .catch (function (error) {
                            this_this.is_searching[key] = false;
                        })
                } else {
                    this_this.products[key] = [];
                    this_this.is_searching[key] = false;
                }
            }
        }
    });

</script>

@endpush