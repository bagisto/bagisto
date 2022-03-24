@push('css')
    <style>
        .table th.price, .table th.weight {
            width: 100px;
        }
        .table th.actions {
            width: 85px;
        }
        .table td.actions .icon {
            margin-top: 8px;
        }
        .table td.actions .icon.pencil-lg-icon {
            margin-right: 10px;
        }
    </style>
@endpush

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.grouped_products.before', ['product' => $product]) !!}

<accordian :title="'{{ __('admin::app.catalog.products.grouped-products') }}'" :active="true">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.grouped_products.controls.before', ['product' => $product]) !!}

        <grouped-product-list></grouped-product-list>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.grouped_products.controls.after', ['product' => $product]) !!}

    </div>
</accordian>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.grouped_products.after', ['product' => $product]) !!}

@push('scripts')
    <script type="text/x-template" id="grouped-product-list-template">
        <div>
            <div class="control-group">
                <label>{{ __('admin::app.catalog.products.search-products') }}</label>

                <input type="text" class="control" placeholder="{{ __('admin::app.catalog.products.product-search-hint') }}" v-model.lazy="search_term" v-debounce="500" autocomplete="off">

                <div class="linked-product-search-result">
                    <ul>
                        <li v-for='(product, index) in searched_results' v-if='searched_results.length' @click="addGroupedProduct(product)">
                            @{{ product.name }}
                        </li>

                        <li v-if='! searched_results.length && search_term.length && ! is_searching'>
                            {{ __('admin::app.catalog.products.no-result-found') }}
                        </li>

                        <li v-if="is_searching && search_term.length">
                            {{ __('admin::app.catalog.products.searching') }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table" style="margin-top: 20px; overflow-x: unset;">
                <table>
                    <thead>
                        <tr>
                            <th class="name">{{ __('admin::app.catalog.products.name') }}</th>
                            <th class="sku">{{ __('admin::app.catalog.products.sku') }}</th>
                            <th class="qty">{{ __('admin::app.catalog.products.qty') }}</th>
                            <th class="sort-order">{{ __('admin::app.catalog.products.sort-order') }}</th>
                            <th class="actions"></th>
                        </tr>
                    </thead>

                    <tbody>

                        <grouped-product-item v-for='(groupedProduct, index) in grouped_products' :grouped-product="groupedProduct" :key="index" :index="index" @onRemoveGroupedProduct="removeGroupedProduct($event)"></grouped-product-item>

                    </tbody>
                </table>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="grouped-product-item-template">
        <tr>
            <td>
                @{{ groupedProduct.associated_product.name }}
                <input type="hidden" :name="[inputName + '[associated_product_id]']" :value="groupedProduct.associated_product.id"/>
            </td>

            <td>@{{ groupedProduct.associated_product.sku }}</td>

            <td>
                <div class="control-group" :class="[errors.has(inputName + '[qty]') ? 'has-error' : '']">
                    <input type="number" v-validate="'required|min_value:0'" :name="[inputName + '[qty]']" v-model="groupedProduct.qty" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.qty') }}&quot;"/>
                    <span class="control-error" v-if="errors.has(inputName + '[qty]')">@{{ errors.first(inputName + '[qty]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(inputName + '[sort_order]') ? 'has-error' : '']">
                    <input type="number" v-validate="'required|min_value:0'" :name="[inputName + '[sort_order]']" v-model="groupedProduct.sort_order" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.sort-order') }}&quot;"/>
                    <span class="control-error" v-if="errors.has(inputName + '[sort_order]')">@{{ errors.first(inputName + '[sort_order]') }}</span>
                </div>
            </td>

            <td class="actions">
                <i class="icon remove-icon" @click="removeGroupedProduct()"></i>
            </td>
        </tr>
    </script>

    <script>
        Vue.component('grouped-product-list', {
            template: '#grouped-product-list-template',

            inject: ['$validator'],

            data: function() {
                return {
                    search_term: '',

                    is_searching: false,

                    searched_results: [],

                    grouped_products: @json($product->grouped_products()->with('associated_product')->get())
                }
            },

            watch: {
                'search_term': function(newVal, oldVal) {
                    this.search('products')
                }
            },

            methods: {
                addGroupedProduct: function(item, key) {
                    let alreadyAdded = false;

                    this.grouped_products.forEach(function(groupProduct) {
                        if (item.id == groupProduct.associated_product.id) {
                            alreadyAdded = true;
                        }
                    });

                    if (! alreadyAdded) {
                        this.grouped_products.push({
                                associated_product: item,
                                qty: 0,
                                sort_order: 0
                            });
                    }

                    this.search_term = '';

                    this.searched_result = [];
                },

                removeGroupedProduct: function(groupedProduct) {
                    let index = this.grouped_products.indexOf(groupedProduct)

                    this.grouped_products.splice(index, 1)
                },

                search: function (key) {
                    this.is_searching = true;

                    if (this.search_term.length < 3) {
                        this.searched_results = [];

                        this.is_searching = false;

                        return;
                    }

                    let self = this;

                    this.$http.get ("{{ route('admin.catalog.products.search_simple_product') }}", {params: {query: this.search_term}})
                        .then (function(response) {
                            self.searched_results = response.data;

                            self.is_searching = false;
                        })
                        .catch (function (error) {
                            self.is_searching = false;
                        })
                }
            }
        });

        Vue.component('grouped-product-item', {
            template: '#grouped-product-item-template',

            props: ['index', 'groupedProduct'],

            inject: ['$validator'],

            computed: {
                inputName: function () {
                    if (this.groupedProduct.id)
                        return 'links[' + this.groupedProduct.id + ']';

                    return 'links[link_' + this.index + ']';
                }
            },

            methods: {
                removeGroupedProduct: function () {
                    this.$emit('onRemoveGroupedProduct', this.groupedProduct)
                }
            }
        });
    </script>
@endpush