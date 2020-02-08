@if ($product->type == 'bundle')

    {!! view_render_event('bagisto.shop.products.view.bundle-options.before', ['product' => $product]) !!}

    <bundle-option-list></bundle-option-list>

    {!! view_render_event('bagisto.shop.products.view.bundle-options.after', ['product' => $product]) !!}

    @push('scripts')
        <script type="text/x-template" id="bundle-option-list-template">
            <div class="bundle-options-wrapper">
                <div class="bundle-option-list">
                    <h3>{{ __('shop::app.products.customize-options') }}</h3>

                    <bundle-option-item
                        v-for="(option, index) in options"
                        :option="option"
                        :key="index"
                        :index="index"
                        @onProductSelected="productSelected(option, $event)">
                    </bundle-option-item>
                </div>

                <div class="bundle-summary">
                    <h3 class="mb10">{{ __('shop::app.products.your-customization') }}</h3>

                    <quantity-changer></quantity-changer>

                    <div class="control-group">
                        <label>{{ __('shop::app.products.total-amount') }}</label>

                        <div class="bundle-price no-margin">
                            @{{ formated_total_price | currency(currency_options) }}
                        </div>
                    </div>

                    <ul type="none" class="bundle-items">
                        <li v-for="(option, index) in options">
                            @{{ option.label }}

                            <div class="selected-products">
                                <div v-for="(product, index1) in option.products" v-if="product.is_default">
                                    @{{ product.qty + ' x ' + product.name }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </script>

        <script type="text/x-template" id="bundle-option-item-template">
            <div class="bundle-option-item">
                <div :class="`control-group custom-form mb10 ${errors.has('bundle_options[' + option.id + '][]') ? 'has-error' : ''}`">
                    <label :class="[option.is_required ? 'required' : '']">@{{ option.label }}</label>

                    <div v-if="option.type == 'select'">
                        <select class="control styled-select" :name="'bundle_options[' + option.id + '][]'" v-model="selected_product" v-validate="option.is_required ? 'required' : ''" :data-vv-as="option.label + '&quot;'">
                            <option value="">{{ __('shop::app.products.choose-selection') }}</option>
                            <option v-for="(product, index2) in option.products" :value="product.id">
                                @{{ product.name + ' + ' + product.price.final_price.formated_price }}
                            </option>
                        </select>
                    </div>

                    <div v-if="option.type == 'radio'">
                        <span class="radio col-12 ml5" v-if="! option.is_required">
                            <input
                                type="radio"
                                :name="'bundle_options[' + option.id + '][]'"
                                v-model="selected_product"
                                value="0" />

                            <label class="radio-view no-padding" :for="'bundle_options[' + option.id + '][]'"></label>
                            {{ __('shop::app.products.none') }}
                        </span>

                        <span class="radio col-12 ml5" v-for="(product, index2) in option.products">
                            <input
                                type="radio"
                                :name="'bundle_options[' + option.id + '][]'"
                                v-model="selected_product"
                                v-validate="option.is_required ? 'required' : ''"
                                :data-vv-as="'&quot;' + option.label + '&quot;'"
                                :value="product.id" />

                            @{{ product.name }}

                            <span class="price">
                                + @{{ product.price.final_price.formated_price }}
                            </span>
                        </span>
                    </div>

                    <div v-if="option.type == 'checkbox'">
                        <span class="checkbox col-12 ml5" v-for="(product, index2) in option.products">
                            <input type="checkbox" :name="'bundle_options[' + option.id + '][]'" :value="product.id" v-model="selected_product" v-validate="option.is_required ? 'required' : ''" :data-vv-as="'&quot;' + option.label + '&quot;'" :id="'bundle_options[' + option.id + '][]'">

                            @{{ product.name }}

                            <span class="price">
                                + @{{ product.price.final_price.formated_price }}
                            </span>
                        </span>
                    </div>

                    <div v-if="option.type == 'multiselect'">
                        <select class="control styled-select" :name="'bundle_options[' + option.id + '][]'" v-model="selected_product" v-validate="option.is_required ? 'required' : ''" :data-vv-as="'&quot;' + option.label + '&quot;'" multiple>
                            <option v-for="(product, index2) in option.products" :value="product.id">
                                @{{ product.name + ' + ' + product.price.final_price.formated_price }}
                            </option>
                        </select>
                    </div>

                    <span class="control-error" v-if="errors.has('bundle_options[' + option.id + '][]')">
                        @{{ errors.first('bundle_options[' + option.id + '][]') }}
                    </span>
                </div>

                <div v-if="option.type == 'select' || option.type == 'radio'">
                    <quantity-changer
                        :control-name="'bundle_option_qty[' + option.id + ']'"
                        :validations="parseInt(selected_product) ? 'required|numeric|min_value:1' : ''"
                        :quantity="product_qty"
                        @onQtyUpdated="qtyUpdated($event)">
                    </quantity-changer>
                </div>
            </div>
        </script>

        <script type="text/javascript">
            Vue.component('bundle-option-list', {
                template: '#bundle-option-list-template',
                inject: ['$validator'],

                data: function() {
                    return {
                        options: [],
                        currency_options: @json(core()->getAccountJsSymbols()),
                        config: @json(app('Webkul\Product\Helpers\BundleOption')->getBundleConfig($product)),
                    }
                },

                computed: {
                    formated_total_price: function() {
                        var total = 0;

                        for (var key in this.options) {
                            for (var key1 in this.options[key].products) {
                                if (! this.options[key].products[key1].is_default)
                                    continue;

                                total += this.options[key].products[key1].qty * this.options[key].products[key1].price.final_price.price;
                            }
                        }

                        return total;
                    }
                },

                created: function() {
                    for (var key in this.config.options) {
                        this.options.push(this.config.options[key])
                    }
                },

                methods: {
                    productSelected: function(option, value) {
                        var selectedProductIds = Array.isArray(value) ? value : [value];

                        for (var key in option.products) {
                            option.products[key].is_default = selectedProductIds.indexOf(option.products[key].id) > -1 ? 1 : 0;
                        }
                    }
                }
            });

            Vue.component('bundle-option-item', {
                template: '#bundle-option-item-template',

                props: ['index', 'option'],

                inject: ['$validator'],

                data: function() {
                    return {
                        selected_product: (this.option.type == 'checkbox' || this.option.type == 'multiselect')  ? [] : null,

                        qty_validations: ''
                    }
                },

                computed: {
                    product_qty: function() {
                        return this.option.products[this.selected_product]
                            ? this.option.products[this.selected_product].qty
                            : 0;
                    }
                },

                watch: {
                    selected_product: function (value) {
                        this.qty_validations = this.selected_product ? 'required|numeric|min_value:1' : '';

                        this.$emit('onProductSelected', value)
                    }
                },

                created: function() {
                    for (var key1 in this.option.products) {
                        if (! this.option.products[key1].is_default)
                            continue;

                        if (this.option.type == 'checkbox' || this.option.type == 'multiselect') {
                            this.selected_product.push(this.option.products[key1].id)
                        } else {
                            this.selected_product = this.option.products[key1].id
                        }
                    }
                },

                methods: {
                    qtyUpdated: function(qty) {
                        if (! this.option.products[this.selected_product])
                            return;

                        this.option.products[this.selected_product].qty = qty;
                    }
                }
            });
        </script>
    @endpush
@endif