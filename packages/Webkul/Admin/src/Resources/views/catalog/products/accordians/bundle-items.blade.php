{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.bundle.before', ['product' => $product]) !!}

<accordian :title="'{{ __('admin::app.catalog.products.bundle-items') }}'" :active="true">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.bundle.controls.before', ['product' => $product]) !!}

        <bundle-option-list></bundle-option-list>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.bundle.controls.after', ['product' => $product]) !!}

    </div>
</accordian>

@push('scripts')
    @parent

    <script type="text/x-template" id="bundle-option-list-template">
        <div class="">
            <button type="button" class="btn btn-md btn-primary" @click="addOption" style="margin-bottom: 20px;">
                {{ __('admin::app.catalog.products.add-option-btn-title') }}
            </button>

            <bundle-option-item 
                v-for='(option, index) in options'
                :option="option"
                :key="index"
                :index="index"
                @onRemoveOption="removeOption($event)"
            ></bundle-option-item>
        </div>
    </script>

    <script type="text/x-template" id="bundle-option-item-template">
        <accordian :active="true">
            <div slot="header">
                <i class="icon expand-icon left"></i>
                <h1 v-if="option.label">@{{ option.label }}</h1>
                <h1 v-else>{{ __('admin::app.catalog.products.new-option') }}</h1>
                <i class="icon trash-icon" @click="removeOption()"></i>
            </div>

            <div slot="body">
                <div class="control-group" :class="[errors.has(titleInputName + '[label]') ? 'has-error' : '']">
                    <label class="required">{{ __('admin::app.catalog.products.option-title') }}</label>

                    <input type="text" v-validate="'required'" :name="titleInputName + '[label]'" v-model="option.label" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.option-title') }}&quot;"/>
                    
                    <span class="control-error" v-if="errors.has(titleInputName + '[label]')">@{{ errors.first(titleInputName + '[label]') }}</span>
                </div>

                <div class="control-group" :class="[errors.has(inputName + '[type]') ? 'has-error' : '']">
                    <label class="required">{{ __('admin::app.catalog.products.input-type') }}</label>

                    <select v-validate="'required'" :name="inputName + '[type]'" v-model="option.type" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.input-type') }}&quot;">
                        <option value="select">{{ __('admin::app.catalog.products.select') }}</option>
                        <option value="radio">{{ __('admin::app.catalog.products.radio') }}</option>
                        <option value="checkbox">{{ __('admin::app.catalog.products.checkbox') }}</option>
                        <option value="multiselect">{{ __('admin::app.catalog.products.multiselect') }}</option>
                    </select>
                    
                    <span class="control-error" v-if="errors.has(inputName + '[type]')">@{{ errors.first(inputName + '[type]') }}</span>
                </div>

                <div class="control-group" :class="[errors.has(inputName + '[is_required]') ? 'has-error' : '']">
                    <label class="required">{{ __('admin::app.catalog.products.is-required') }}</label>

                    <select v-validate="'required'" :name="inputName + '[is_required]'" v-model="option.is_required" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.is-required') }}&quot;">
                        <option value="1">{{ __('admin::app.catalog.products.yes') }}</option>
                        <option value="0">{{ __('admin::app.catalog.products.no') }}</option>
                    </select>
                    
                    <span class="control-error" v-if="errors.has(inputName + '[is_required]')">@{{ errors.first(inputName + '[is_required]') }}</span>
                </div>

                <div class="control-group" :class="[errors.has(inputName + '[sort_order]') ? 'has-error' : '']">
                    <label class="required">{{ __('admin::app.catalog.products.sort-order') }}</label>

                    <input type="text" v-validate="'required|numeric|min_value:0'" :name="inputName + '[sort_order]'" v-model="option.sort_order" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.sort-order') }}&quot;"/>
                    
                    <span class="control-error" v-if="errors.has(inputName + '[sort_order]')">@{{ errors.first(inputName + '[sort_order]') }}</span>
                </div>

                <div class="section">
                    <div class="secton-title">
                        <span>Products</span>
                    </div>
                    
                    <div class="section-content">
                        <bundle-product-list
                            :bundle-option-products="option.bundle_option_products"
                            :bundle-option="option"
                            :control-name="inputName">
                        </bundle-product-list>
                    </div>
                </div>
            </div>
        </accordian>
    </script>

    <script type="text/x-template" id="bundle-product-list-template">
        <div>
            <div class="control-group">
                <label>{{ __('admin::app.catalog.products.search-products') }}</label>

                <input type="text" class="control" placeholder="{{ __('admin::app.catalog.products.product-search-hint') }}" v-model.lazy="search_term" v-debounce="500" autocomplete="off">

                <div class="linked-product-search-result">
                    <ul>
                        <li v-for='(product, index) in searched_results' v-if='searched_results.length' @click="addProduct(product)">
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
                            <th class="name">{{ __('admin::app.catalog.products.is-default') }}</th>
                            <th class="name">{{ __('admin::app.catalog.products.name') }}</th>
                            <th class="sku">{{ __('admin::app.catalog.products.sku') }}</th>
                            <th class="qty">{{ __('admin::app.catalog.products.qty') }}</th>
                            <th class="sort-order">{{ __('admin::app.catalog.products.sort-order') }}</th>
                            <th class="actions"></th>
                        </tr>
                    </thead>

                    <tbody>

                        <bundle-product-item
                            v-for='(product, index) in bundle_option_products'
                            :bundle-option="bundleOption"
                            :product="product"
                            :key="index"
                            :index="index"
                            :control-name="controlName"
                            @onRemoveProduct="removeProduct($event)"
                            @onCheckProduct="checkProduct($event)">
                        </bundle-product-item>

                    </tbody>

                </table>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="bundle-product-item-template">
        <tr>
            <td>
                <div class="control-group" v-if="bundleOption.type == 'radio' || bundleOption.type == 'select'">
                    <span class="radio">
                        <input type="radio" :name="[inputName + '[is_default]']" :id="[inputName + '[is_default]']" :value="product.is_default" @click="checkProduct($event)" :checked="product.is_default">
                        <label class="radio-view" :for="[inputName + '[is_default]']"></label>
                    </span>
                </div>

                <div class="control-group" v-else>
                    <span class="checkbox">
                        <input type="checkbox" :name="[inputName + '[is_default]']" :id="[inputName + '[is_default]']" :value="product.is_default" @click="checkProduct($event)" :checked="product.is_default">
                        <label class="checkbox-view" :for="[inputName + '[is_default]']"></label>
                    </span>
                </div>
            </td>

            <td>
                @{{ product.product.name }}
                <input type="hidden" :name="[inputName + '[product_id]']" :value="product.product.id"/>
            </td>

            <td>@{{ product.product.sku }}</td>

            <td>
                <div class="control-group" :class="[errors.has(inputName + '[qty]') ? 'has-error' : '']">
                    <input type="number" v-validate="'required|min_value:0'" :name="[inputName + '[qty]']" v-model="product.qty" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.qty') }}&quot;"/>
                    <span class="control-error" v-if="errors.has(inputName + '[qty]')">@{{ errors.first(inputName + '[qty]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(inputName + '[sort_order]') ? 'has-error' : '']">
                    <input type="number" v-validate="'required|min_value:0'" :name="[inputName + '[sort_order]']" v-model="product.sort_order" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.sort-order') }}&quot;"/>
                    <span class="control-error" v-if="errors.has(inputName + '[sort_order]')">@{{ errors.first(inputName + '[sort_order]') }}</span>
                </div>
            </td>

            <td class="actions">
                <i class="icon remove-icon" @click="removeProduct()"></i>
            </td>
        </tr>
    </script>

    <script>
        Vue.component('bundle-option-list', {

            template: '#bundle-option-list-template',

            inject: ['$validator'],

            data: function() {
                return {
                    options: @json($product->bundle_options()->with(['product', 'bundle_option_products', 'bundle_option_products.product'])->get())
                }
            },

            methods: {
                addOption: function() {
                    this.options.push({
                        label: '',
                        type: 'select',
                        is_required: 1,
                        sort_order: 0,
                        bundle_option_products: []
                    });
                },
                
                removeOption: function(option) {
                    let index = this.options.indexOf(option)

                    this.options.splice(index, 1)
                },
            }
        });

        Vue.component('bundle-option-item', {

            template: '#bundle-option-item-template',

            props: ['index', 'option'],

            inject: ['$validator'],

            computed: {
                titleInputName: function () {
                    if (this.option.id)
                        return "bundle_options[" + this.option.id + "]" + '[{{$locale}}]';

                    return "bundle_options[option_" + this.index + "]" + '[{{$locale}}]';
                },

                inputName: function () {
                    if (this.option.id)
                        return "bundle_options[" + this.option.id + "]";

                    return "bundle_options[option_" + this.index + "]";
                }
            },

            methods: {
                removeOption: function() {
                    this.$emit('onRemoveOption', this.option)
                }
            }
        });

        Vue.component('bundle-product-list', {

            template: '#bundle-product-list-template',

            inject: ['$validator'],

            props: ['controlName', 'bundleOption', 'bundleOptionProducts'],

            data: function() {
                return {
                    search_term: '',

                    is_searching: false,

                    searched_results: [],

                    bundle_option_products: this.bundleOptionProducts
                }
            },

            watch: {
                'search_term': function(newVal, oldVal) {
                    this.search('products')
                }
            },

            methods: {
                addProduct: function(item, key) {
                    var alreadyAdded = false;
                    
                    this.bundle_option_products.forEach(function(optionProduct) {
                        if (item.id == optionProduct.product.id) {
                            alreadyAdded = true;
                        }
                    });

                    if (! alreadyAdded) {
                        this.bundle_option_products.push({
                                product: item,
                                qty: 0,
                                sort_order: 0
                            });
                    }

                    this.search_term = '';

                    this.searched_result = [];
                },

                removeProduct: function(product) {
                    let index = this.bundle_option_products.indexOf(product)

                    this.bundle_option_products.splice(index, 1)
                },

                search: function (key) {
                    this.is_searching = true;

                    if (this.search_term.length < 3) {
                        this.searched_results = [];

                        this.is_searching = false;

                        return;
                    }

                    var this_this = this;
                    
                    this.$http.get ("{{ route('admin.catalog.products.search_simple_product') }}", {params: {query: this.search_term}})
                        .then (function(response) {
                            this_this.searched_results = response.data;

                            this_this.is_searching = false;
                        })
                        .catch (function (error) {
                            this_this.is_searching = false;
                        })
                },

                checkProduct: function(productId) {
                    var this_this = this;

                    this.bundle_option_products.forEach(function(product) {
                        if (this_this.bundleOption.type == 'radio' || this_this.bundleOption.type == 'select') {
                            product.is_default = product.id == productId ? 1 : 0;
                        } else {
                            if (product.id == productId)
                                product.is_default = product.is_default ? 0 : 1;
                        }
                    });
                }
            }
        });

        Vue.component('bundle-product-item', {

            template: '#bundle-product-item-template',

            props: ['controlName', 'index', 'bundleOption', 'product'],

            inject: ['$validator'],

            computed: {
                inputName: function () {
                    if (this.product.id)
                        return this.controlName + "[products][" + this.product.id + "]";

                    return this.controlName + "[products][product_" + this.index + "]";
                }
            },

            methods: {
                removeProduct: function () {
                    this.$emit('onRemoveProduct', this.product)
                },

                checkProduct: function($event) {
                    this.$emit('onCheckProduct', this.product.id)
                }
            }
        });
    </script>

@endpush