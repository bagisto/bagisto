{!! view_render_event('bagisto.admin.sales.order.create.types.simple.before') !!}

<v-simple-product-customizable-options
    :errors="errors"
    :product-options="selectedProductOptions"
></v-simple-product-customizable-options>

{!! view_render_event('bagisto.admin.sales.order.create.types.simple.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-simple-product-customizable-options-template"
    >
        <div class="">
            <v-simple-product-customizable-option-item
                :key="index"
                :option="option"
                v-for="(option, index) in options"
                @priceUpdated="priceUpdated"
            >
            </v-simple-product-customizable-option-item>

            <div class="p-4">
                <div class="my-5 flex items-center justify-between">
                    <p class="text-sm dark:text-white">
                        @lang('admin::app.sales.orders.create.types.simple.total-amount')
                    </p>

                    <p class="text-lg font-medium dark:text-white">
                        @{{ formattedTotalPrice }}
                    </p>
                </div>
            </div>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-simple-product-customizable-option-item-template"
    >
        <div class="border-b border-[#E9E9E9] p-4">
            <x-admin::form.control-group>
                <!-- Text Options -->
                <template v-if="option.type == 'text'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}

                        <span class="text-black">
                            @{{ '+ ' + $admin.formatPrice(option.price) }}
                        </span>
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        ::name="'customizable_options[' + option.id + '][]'"
                        ::value="option.id"
                        v-model="selectedItems"
                        ::rules="{ 'required': Boolean(option.is_required), 'max': option.max_characters }"
                        ::label="option.label"
                    />
                </template>

                <!-- Textarea Options -->
                <template v-else-if="option.type == 'textarea'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}

                        <span class="text-black">
                            @{{ '+ ' + $admin.formatPrice(option.price) }}
                        </span>
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="textarea"
                        ::name="'customizable_options[' + option.id + '][]'"
                        ::value="option.id"
                        v-model="selectedItems"
                        ::rules="{ 'required': Boolean(option.is_required), 'max': option.max_characters }"
                        ::label="option.label"
                    />
                </template>

                <!-- Checkbox Options -->
                <template v-else-if="option.type == 'checkbox'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}
                    </x-admin::form.control-group.label>

                    <div class="grid gap-2">
                        <!-- Options -->
                        <div
                            class="flex select-none items-center gap-x-4"
                            v-for="(item, index) in optionItems"
                        >
                            <x-admin::form.control-group.control
                                type="checkbox"
                                ::name="'customizable_options[' + option.id + '][]'"
                                ::value="item.id"
                                ::for="'customizable_options[' + option.id + '][' + index + ']'"
                                ::id="'customizable_options[' + option.id + '][' + index + ']'"
                                v-model="selectedItems"
                                ::rules="{ 'required': Boolean(option.is_required) }"
                                ::label="option.label"
                            />

                            <label
                                class="cursor-pointer text-sm text-[#6E6E6E] dark:text-gray-300"
                                :for="'customizable_options[' + option.id + '][' + index + ']'"
                            >
                                @{{ item.label }}

                                <span class="text-black dark:text-white">
                                    @{{ '+ ' + $admin.formatPrice(item.price) }}
                                </span>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- Radio Options -->
                <template v-else-if="option.type == 'radio'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}
                    </x-admin::form.control-group.label>

                    <div class="grid gap-2">
                        <!-- "None" radio option for cases where the option is not required. -->
                        <div
                            class="flex select-none gap-x-4"
                            v-if="! Boolean(option.is_required)"
                        >
                            <x-admin::form.control-group.control
                                type="radio"
                                ::name="'customizable_options[' + option.id + '][]'"
                                value="0"
                                ::for="'customizable_options[' + option.id + '][' + index + ']'"
                                ::id="'customizable_options[' + option.id + '][' + index + ']'"
                                v-model="selectedItems"
                                ::rules="{ 'required': Boolean(option.is_required) }"
                                ::label="option.label"
                                ::checked="true"
                            />

                            <label
                                class="cursor-pointer text-sm text-[#6E6E6E] dark:text-gray-300"
                                :for="'customizable_options[' + option.id + '][' + index + ']'"
                            >
                                @lang('admin::app.sales.orders.create.types.simple.none')
                            </label>
                        </div>

                        <!-- Options -->
                        <div
                            class="flex select-none items-center gap-x-4"
                            v-for="(item, index) in optionItems"
                        >
                            <x-admin::form.control-group.control
                                type="radio"
                                ::name="'customizable_options[' + option.id + '][]'"
                                ::value="item.id"
                                ::for="'customizable_options[' + option.id + '][' + index + ']'"
                                ::id="'customizable_options[' + option.id + '][' + index + ']'"
                                v-model="selectedItems"
                                ::rules="{ 'required': Boolean(option.is_required) }"
                                ::label="option.label"
                            />

                            <label
                                class="cursor-pointer text-sm text-[#6E6E6E] dark:text-gray-300"
                                :for="'customizable_options[' + option.id + '][' + index + ']'"
                            >
                                @{{ item.label }}

                                <span class="text-black dark:text-white">
                                    @{{ '+ ' + $admin.formatPrice(item.price) }}
                                </span>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- Select Options -->
                <template v-else-if="option.type == 'select'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        ::name="'customizable_options[' + option.id + '][]'"
                        v-model="selectedItems"
                        ::rules="{ 'required': Boolean(option.is_required) }"
                        ::label="option.label"
                    >
                        <!-- "None" select option for cases where the option is not required. -->
                        <option
                            value="0"
                            v-if="! Boolean(option.is_required)"
                        >
                            @lang('admin::app.sales.orders.create.types.simple.none')
                        </option>

                        <option
                            v-for="item in optionItems"
                            :value="item.id"
                        >
                            @{{ item.label + ' + ' + $admin.formatPrice(item.price) }}
                        </option>
                    </x-admin::form.control-group.control>
                </template>

                <!-- Multiselect Options -->
                <template v-else-if="option.type == 'multiselect'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="multiselect"
                        ::name="'customizable_options[' + option.id + '][]'"
                        v-model="selectedItems"
                        ::rules="{'required': Boolean(option.is_required)}"
                        ::label="option.label"
                    >
                        <option
                            v-for="item in optionItems"
                            :value="item.id"
                            :selected="value && value.includes(item.id)"
                        >
                            @{{ item.label + ' + ' + $admin.formatPrice(item.price) }}
                        </option>
                    </x-admin::form.control-group.control>
                </template>

                <!-- Date Field -->
                <template v-else-if="option.type == 'date'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}

                        <span class="text-black">
                            @{{ '+ ' + $admin.formatPrice(option.price) }}
                        </span>
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="date"
                        ::name="'customizable_options[' + option.id + '][]'"
                        ::value="option.id"
                        v-model="selectedItems"
                        ::rules="{'required': Boolean(option.is_required)}"
                        ::label="option.label"
                    />
                </template>

                <!-- Datetime Field -->
                <template v-else-if="option.type == 'datetime'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}

                        <span class="text-black">
                            @{{ '+ ' + $admin.formatPrice(option.price) }}
                        </span>
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="datetime"
                        ::name="'customizable_options[' + option.id + '][]'"
                        ::value="option.id"
                        v-model="selectedItems"
                        ::rules="{'required': Boolean(option.is_required)}"
                        ::label="option.label"
                    />
                </template>

                <!-- Time Field -->
                <template v-else-if="option.type == 'time'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}

                        <span class="text-black">
                            @{{ '+ ' + $admin.formatPrice(option.price) }}
                        </span>
                    </x-admin::form.control-group.label>

                    <v-field
                        type="time"
                        :name="'customizable_options[' + option.id + '][]'"
                        :value="option.id"
                        v-model="selectedItems"
                        :rules="{'required': Boolean(option.is_required)}"
                        :label="option.label"
                    />
                </template>

                <!-- File -->
                <template v-else-if="option.type == 'file'">
                    <x-admin::form.control-group.label
                        class="!mt-0"
                        ::class="{ 'required': Boolean(option.is_required) }"
                    >
                        @{{ option.label }}

                        <span class="text-black">
                            @{{ '+ ' + $admin.formatPrice(option.price) }}
                        </span>
                    </x-admin::form.control-group.label>

                    <v-field
                        type="file"
                        :name="'customizable_options[' + option.id + '][]'"
                        :rules="{'required': Boolean(option.is_required), ...(option.supported_file_extensions && option.supported_file_extensions.length ? {'ext': option.supported_file_extensions.split(',').map(ext => ext.trim())} : {})}"
                        :label="option.label"
                        @change="handleFileChange"
                    >
                    </v-field>
                </template>

                <x-admin::form.control-group.error ::name="'customizable_options[' + option.id + '][]'" />
            </x-admin::form.control-group>
        </div>
    </script>

    <script type="module">
        app.component('v-simple-product-customizable-options', {
            template: '#v-simple-product-customizable-options-template',

            props: ['errors', 'productOptions'],

            data: function() {
                return {
                    isLoading: false,

                    initialPrice: 0,

                    options: [],

                    prices: [],
                }
            },

            computed: {
                formattedTotalPrice: function() {
                    let totalPrice = parseFloat(this.initialPrice);

                    for (let price of this.prices) {
                        totalPrice += parseFloat(price.price);
                    }

                    return this.$admin.formatPrice(totalPrice);
                }
            },

            mounted() {
                this.getCustomizableOptions();
            },

            methods: {
                getCustomizableOptions() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('admin.catalog.products.simple.customizable-options', ':replace') }}".replace(':replace', this.productOptions.product.id))
                        .then(response => {
                            this.initialPrice = response.data.meta.initial_price;

                            this.options = response.data.data.map((option) => {
                                if (! this.canHaveMultiplePriceOptions(option.type)) {
                                    return {
                                        id: option.id,
                                        label: option.label,
                                        type: option.type,
                                        is_required: option.is_required,
                                        max_characters: option.max_characters,
                                        supported_file_extensions: option.supported_file_extensions,
                                        customizable_option_prices: option.customizable_option_prices,
                                        price_id: option.customizable_option_prices[0].id,
                                        price: option.customizable_option_prices[0].price,
                                    };
                                }

                                return {
                                    id: option.id,
                                    label: option.label,
                                    type: option.type,
                                    is_required: option.is_required,
                                    max_characters: option.max_characters,
                                    supported_file_extensions: option.supported_file_extensions,
                                    customizable_option_prices: option.customizable_option_prices,
                                    price: 0,
                                };
                            });

                            this.prices = this.options.map((option) => {
                                return {
                                    option_id: option.id,
                                    price: 0,
                                };
                            });

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },

                priceUpdated({ option, totalPrice }) {
                    let price = this.prices.find(price => price.option_id === option.id);

                    price.price = totalPrice;
                },

                canHaveMultiplePriceOptions(type) {
                    return ['checkbox', 'radio', 'select', 'multiselect'].includes(type);
                },
            }
        });

        app.component('v-simple-product-customizable-option-item', {
            template: '#v-simple-product-customizable-option-item-template',

            emits: ['priceUpdated'],

            props: ['option'],

            data: function() {
                return {
                    optionItems: [],

                    selectedItems: this.canHaveMultiplePrices()  ? [] : null,
                };
            },

            mounted() {
                if (! this.option.customizable_option_prices) {
                    return;
                }

                this.optionItems = this.option.customizable_option_prices.map(optionItem => {
                    return {
                        id: optionItem.id,
                        label: optionItem.label,
                        price: optionItem.price,
                    };
                });
            },

            watch: {
                selectedItems: function (value) {
                    let selectedItemValues = Array.isArray(value) ? value : [value];

                    let totalPrice = 0;

                    for (let item of this.optionItems) {
                        switch (this.option.type) {
                            case 'text':
                            case 'textarea':
                            case 'date':
                            case 'datetime':
                            case 'time':
                                if (selectedItemValues[0].length > 0) {
                                    totalPrice += parseFloat(item.price);
                                }

                                break;

                            case 'checkbox':
                            case 'radio':
                            case 'select':
                            case 'multiselect':
                                if (selectedItemValues.includes(item.id)) {
                                    totalPrice += parseFloat(item.price);
                                }

                            case 'file':
                                if (selectedItemValues[0] instanceof File) {
                                    totalPrice += parseFloat(item.price);
                                }

                                break;
                        }
                    }

                    this.$emit('priceUpdated', {
                        option: this.option,

                        totalPrice,
                    });
                },
            },

            methods: {
                canHaveMultiplePrices() {
                    return ['checkbox', 'multiselect'].includes(this.option.type);
                },

                handleFileChange($event) {
                    const selectedFiles = event.target.files;

                    this.selectedItems = selectedFiles[0];
                },
            },
        });
    </script>
@endPushOnce
