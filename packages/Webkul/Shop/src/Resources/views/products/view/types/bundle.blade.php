@if ($product->type == 'bundle')
    {!! view_render_event('bagisto.shop.products.view.bundle-options.before', ['product' => $product]) !!}

    <v-product-bundle-options :errors="errors"></v-product-bundle-options>

    {!! view_render_event('bagisto.shop.products.view.bundle-options.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-bundle-options-template"
        >
            <div class="mt-8">
                <v-product-bundle-option-item
                    v-for="(option, index) in options"
                    :option="option"
                    :errors="errors"
                    :key="index"
                    @onProductSelected="productSelected(option, $event)"
                >
                </v-product-bundle-option-item>

                <div class="flex justify-between items-center my-[20px]">
                    <p class="text-sm">
                        @lang('Total Amount')
                    </p>

                    <p class="text-lg font-medium">
                        @{{ formattedTotalPrice }}
                    </p>
                </div>

                <ul class="grid gap-2.5 text-base">
                    <li v-for="option in options">
                        <span class="inline-block mb-1.5">
                            @{{ option.label }}
                        </span>

                        <template v-for="product in option.products">
                            <div
                                class="text-[#6E6E6E]"
                                :key="product.id"
                                v-if="product.is_default"
                            >
                                @{{ product.qty + ' x ' + product.name }}
                            </div>
                        </template>
                    </li>
                </ul>
            </div>
        </script>

        <script type="text/x-template" id="v-product-bundle-option-item-template">
            <div class="mt-8 border-b border-[#E9E9E9] pb-4">
                <x-shop::form.control-group>
                    <!-- Dropdown Options Container -->
                    <x-shop::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': option.is_required }"
                    >
                        @{{ option.label }}
                    </x-shop::form.control-group.label>

                    <template v-if="option.type == 'select'">
                        <x-shop::form.control-group.control
                            type="select"
                            ::name="'bundle_options[' + option.id + '][]'"
                            ::rules="{'required': option.is_required}"
                            v-model="selectedProduct"
                            ::label="option.label"
                        >
                            <option
                                value="0"
                                v-if="! option.is_required"
                            >
                                @lang('shop::app.products.view.type.bundle.none')
                            </option>

                            <option
                                v-for="product in option.products"
                                :value="product.id"
                            >
                                @{{ product.name + ' + ' + product.price.final.formatted_price }}
                            </option>
                        </x-shop::form.control-group.control>
                    </template>
                    
                    <template v-if="option.type == 'radio'">
                        <div class="grid gap-2">
                            <!-- None radio option if option is not required -->
                            <div
                                class="flex gap-x-4 select-none"
                                v-if="! option.is_required"
                            >
                                <x-shop::form.control-group.control
                                    type="radio"
                                    ::name="'bundle_options[' + option.id + '][]'"
                                    ::for="'bundle_options[' + option.id + '][' + index + ']'"
                                    ::id="'bundle_options[' + option.id + '][' + index + ']'"
                                    value="0"
                                    v-model="selectedProduct"
                                    ::rules="{'required': option.is_required}"
                                    ::label="option.label"
                                />

                                <label
                                    class="text-[#6E6E6E] cursor-pointer"
                                    :for="'bundle_options[' + option.id + '][' + index + ']'"
                                >
                                    @lang('shop::app.products.view.type.bundle.none')
                                </label>
                            </div>

                            <!-- Options -->
                            <div
                                class="flex gap-x-4 items-center select-none"
                                v-for="(product, index) in option.products"
                            >
                                <x-shop::form.control-group.control
                                    type="radio"
                                    ::name="'bundle_options[' + option.id + '][]'"
                                    ::for="'bundle_options[' + option.id + '][' + index + ']'"
                                    ::id="'bundle_options[' + option.id + '][' + index + ']'"
                                    ::value="product.id"
                                    v-model="selectedProduct"
                                    ::rules="{'required': option.is_required}"
                                    ::label="option.label"
                                />

                                <label
                                    class="text-[#6E6E6E] cursor-pointer"
                                    :for="'bundle_options[' + option.id + '][' + index + ']'"
                                >
                                    @{{ product.name }}

                                    <span class="text-black">
                                        @{{ '+ ' + product.price.final.formatted_price }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </template>

                    <template v-if="option.type == 'checkbox'">
                        <div class="grid gap-2">
                        <!-- Options -->
                            <div
                                class="flex gap-x-4 items-center select-none"
                                v-for="(product, index) in option.products"
                            >
                                <x-shop::form.control-group.control
                                    type="checkbox"
                                    ::name="'bundle_options[' + option.id + '][]'"
                                    ::for="'bundle_options[' + option.id + '][' + index + ']'"
                                    ::id="'bundle_options[' + option.id + '][' + index + ']'"
                                    ::value="product.id"
                                    v-model="selectedProduct"
                                    ::rules="{'required': option.is_required}"
                                    ::label="option.label"
                                />

                                <label
                                    class="text-[#6E6E6E] cursor-pointer"
                                    :for="'bundle_options[' + option.id + '][' + index + ']'"
                                >
                                    @{{ product.name }}

                                    <span class="text-black">
                                        @{{ '+ ' + product.price.final.formatted_price }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </template>

                    <template v-if="option.type == 'multiselect'">
                        <x-shop::form.control-group.control
                            type="select"
                            ::name="'bundle_options[' + option.id + '][]'"
                            ::rules="{'required': option.is_required}"
                            v-model="selectedProduct"
                            ::label="option.label"
                            multiple
                        >
                            <option
                                value="0"
                                v-if="! option.is_required"
                            >
                                @lang('shop::app.products.view.type.bundle.none')
                            </option>

                            <option
                                v-for="product in option.products"
                                :value="product.id"
                            >
                                @{{ product.name + ' + ' + product.price.final.formatted_price }}
                            </option>
                        </x-shop::form.control-group.control>
                    </template>

                    <x-shop::form.control-group.error ::name="'bundle_options[' + option.id + '][]'" />
                </x-shop::form.control-group>

                <template v-if="['select', 'radio'].includes(option.type)">
                    <x-shop::quantity-changer
                        ::name="'bundle_option_qty[' + option?.id + ']'"
                        ::value="productQty"
                        class="gap-x-4 w-max rounded-xl py-2.5 px-4 mt-5 !border-[#E9E9E9]"
                        @change="qtyUpdated($event)"
                    />
                </template>
            </div>
        </script>

        <script type="module">
            app.component('v-product-bundle-options', {
                template: '#v-product-bundle-options-template',

                props: ['errors'],

                data: function() {
                    return {
                        config: @json(app('Webkul\Product\Helpers\BundleOption')->getBundleConfig($product)),

                        options: [],

                    }
                },

                computed: {
                    formattedTotalPrice: function() {
                        var total = 0;

                        for (var key in this.options) {
                            for (var key1 in this.options[key].products) {
                                if (! this.options[key].products[key1].is_default)
                                    continue;

                                total += this.options[key].products[key1].qty * this.options[key].products[key1].price.final.price;
                            }
                        }

                        return this.$shop.formatPrice(total);
                    }
                },

                created: function() {
                    for (var key in this.config.options) {
                        this.options.push(this.config.options[key]);
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

            app.component('v-product-bundle-option-item', {
                template: '#v-product-bundle-option-item-template',

                props: ['option', 'errors'],

                data: function() {
                    return {
                        selectedProduct: (this.option.type == 'checkbox' || this.option.type == 'multiselect')  ? [] : null,
                    };
                },

                computed: {
                    productQty: function() {
                        let qty = 0;

                        this.option.products.forEach((product, key) => {
                            if (this.selectedProduct == product.id) {
                                qty =  this.option.products[key].qty;
                            }
                        });

                        return qty;
                    }
                },

                watch: {
                    selectedProduct: function (value) {
                        this.$emit('onProductSelected', value);
                    }
                },

                created: function() {
                    for (var key in this.option.products) {
                        if (! this.option.products[key].is_default)
                            continue;

                        if (this.option.type == 'checkbox' || this.option.type == 'multiselect') {
                            this.selectedProduct.push(this.option.products[key].id)
                        } else {
                            this.selectedProduct = this.option.products[key].id
                        }
                    }
                },

                methods: {
                    qtyUpdated: function(qty) {
                        if (! this.option.products.find(data => data.id == this.selectedProduct)) {
                            return;
                        }

                        this.option.products.find(data => data.id == this.selectedProduct).qty = qty;
                    }
                }
            });
        </script>
    @endPushOnce
@endif
