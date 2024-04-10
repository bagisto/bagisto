{!! view_render_event('bagisto.admin.sales.order.create.types.bundle.before') !!}

<v-product-bundle-options
    :errors="errors"
    :product-options="selectedProductOptions"
></v-product-bundle-options>

{!! view_render_event('bagisto.admin.sales.order.create.types.bundle.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-bundle-options-template"
    >
        <div class="">
            <v-product-bundle-option-item
                v-for="(option, index) in options"
                :option="option"
                :errors="errors"
                :key="index"
                @onProductSelected="productSelected(option, $event)"
            >
            </v-product-bundle-option-item>

            <div class="p-4">
                <div class="flex justify-between items-center my-[20px]">
                    <p class="text-sm dark:text-white">
                        @lang('admin::app.sales.orders.create.types.bundle.total-amount')
                    </p>

                    <p class="text-lg font-medium dark:text-white">
                        @{{ formattedTotalPrice }}
                    </p>
                </div>

                <ul class="grid gap-2.5 text-base">
                    <li v-for="option in options">
                        <span class="inline-block mb-1.5 dark:text-white">
                            @{{ option.label }}
                        </span>

                        <template v-for="product in option.products">
                            <div
                                class="text-[#6E6E6E] dark:text-gray-300"
                                :key="product.id"
                                v-if="product.is_default"
                            >
                                @{{ product.qty + ' x ' + product.name }}
                            </div>
                        </template>
                    </li>
                </ul>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-product-bundle-option-item-template">
        <div class="border-b border-[#E9E9E9] p-4">
            <x-admin::form.control-group>
                <!-- Dropdown Options Container -->
                <x-admin::form.control-group.label
                    class="!mt-0"
                    ::class="{ 'required': option.is_required }"
                >
                    @{{ option.label }}
                </x-admin::form.control-group.label>

                <template v-if="option.type == 'select'">
                    <x-admin::form.control-group.control
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
                            @lang('admin::app.sales.orders.create.types.bundle.none')
                        </option>

                        <option
                            v-for="product in option.products"
                            :value="product.id"
                        >
                            @{{ product.name + ' + ' + product.price.final.formatted_price }}
                        </option>
                    </x-admin::form.control-group.control>
                </template>
                
                <template v-if="option.type == 'radio'">
                    <div class="grid gap-2">
                        <!-- None radio option if option is not required -->
                        <div
                            class="flex gap-x-4 select-none"
                            v-if="! option.is_required"
                        >
                            <x-admin::form.control-group.control
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
                                class="text-sm text-[#6E6E6E] dark:text-gray-300 cursor-pointer"
                                :for="'bundle_options[' + option.id + '][' + index + ']'"
                            >
                                @lang('admin::app.sales.orders.create.types.bundle.none')
                            </label>
                        </div>

                        <!-- Options -->
                        <div
                            class="flex gap-x-4 items-center select-none"
                            v-for="(product, index) in option.products"
                        >
                            <x-admin::form.control-group.control
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
                                class="text-sm text-[#6E6E6E] dark:text-gray-300 cursor-pointer"
                                :for="'bundle_options[' + option.id + '][' + index + ']'"
                            >
                                @{{ product.name }}

                                <span class="text-black dark:text-white">
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
                            <x-admin::form.control-group.control
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
                                class="text-sm text-[#6E6E6E] dark:text-gray-300 cursor-pointer"
                                :for="'bundle_options[' + option.id + '][' + index + ']'"
                            >
                                @{{ product.name }}

                                <span class="text-black dark:text-white">
                                    @{{ '+ ' + product.price.final.formatted_price }}
                                </span>
                            </label>
                        </div>
                    </div>
                </template>

                <template v-if="option.type == 'multiselect'">
                    <x-admin::form.control-group.control
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
                            @lang('admin::app.sales.orders.create.types.bundle.none')
                        </option>

                        <option
                            v-for="product in option.products"
                            :value="product.id"
                        >
                            @{{ product.name + ' + ' + product.price.final.formatted_price }}
                        </option>
                    </x-admin::form.control-group.control>
                </template>

                <x-admin::form.control-group.error ::name="'bundle_options[' + option.id + '][]'" />
            </x-admin::form.control-group>

            <template v-if="['select', 'radio'].includes(option.type)">
                <x-admin::quantity-changer
                    ::name="'bundle_option_qty[' + option?.id + ']'"
                    ::value="productQty"
                    class="gap-x-4 w-max rounded-l py-1 px-4 mt-5"
                    @change="qtyUpdated($event)"
                />
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-product-bundle-options', {
            template: '#v-product-bundle-options-template',

            props: ['errors', 'productOptions'],

            data: function() {
                return {
                    config: [],

                    isLoading: false,

                    bundleOptions: [],

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

                    return this.$admin.formatPrice(total);
                }
            },

            mounted() {
                this.getOptions();
            },

            methods: {
                getOptions() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('admin.catalog.products.bundle.options', ':replace') }}".replace(':replace', this.productOptions.product.id))
                        .then(response => {
                            this.config = response.data.data;

                            for (var key in this.config.options) {
                                this.options.push(this.config.options[key]);
                            }

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },

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