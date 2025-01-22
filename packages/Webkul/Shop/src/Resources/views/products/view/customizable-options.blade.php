@if ($product->getTypeInstance()->isCustomizable())
    @php
        $options = $product->customizable_options()->with([
            'product',
            'customizable_option_prices',
        ])->get();
    @endphp

    {!! view_render_event('bagisto.shop.products.view.customizable-options.before', ['product' => $product]) !!}

    <v-product-customizable-options
        :initial-price="{{ $product->getTypeInstance()->getMinimalPrice() }}"
    >
    </v-product-customizable-options>

    {!! view_render_event('bagisto.shop.products.view.customizable-options.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-customizable-options-template"
        >
            <div class="mt-8 max-sm:mt-0">
                <template v-for="(option, index) in options">
                    <v-product-customizable-option-item
                        :option="option"
                        :key="index"
                        @priceUpdated="priceUpdated"
                    >
                    </v-product-customizable-option-item>
                </template>

                <div class="mb-2.5 mt-5 flex items-center justify-between">
                    <p class="text-sm">
                        @lang('shop::app.products.view.type.simple.customizable-options.total-amount')
                    </p>

                    <p class="text-lg font-medium max-sm:text-sm">
                        @{{ formattedTotalPrice }}
                    </p>
                </div>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-product-customizable-option-item-template"
        >
            <div class="mt-8 border-b border-zinc-200 pb-4 max-sm:mt-4 max-sm:pb-0">
                <x-shop::form.control-group>
                    <!-- Text Field -->
                    <template v-if="option.type == 'text'">
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}

                            <span class="text-black">
                                @{{ '+ ' + $shop.formatPrice(option.price) }}
                            </span>
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            ::name="'customizable_options[' + option.id + '][]'"
                            ::value="option.id"
                            v-model="selectedItems"
                            ::rules="{ 'required': Boolean(option.is_required), 'max': option.max_characters }"
                            ::label="option.label"
                        />
                    </template>

                    <!-- Textarea Field -->
                    <template v-else-if="option.type == 'textarea'">
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}

                            <span class="text-black">
                                @{{ '+ ' + $shop.formatPrice(option.price) }}
                            </span>
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
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
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}
                        </x-shop::form.control-group.label>

                        <div class="grid gap-2">
                            <div
                                class="flex select-none items-center gap-x-4 max-sm:gap-x-1.5"
                                v-for="(item, index) in optionItems"
                            >
                                <x-shop::form.control-group.control
                                    type="checkbox"
                                    ::name="'customizable_options[' + option.id + '][]'"
                                    ::value="item.id"
                                    ::for="'customizable_options[' + option.id + '][' + index + ']'"
                                    ::id="'customizable_options[' + option.id + '][' + index + ']'"
                                    v-model="selectedItems"
                                    ::rules="{'required': Boolean(option.is_required)}"
                                    ::label="option.label"
                                />

                                <label
                                    class="cursor-pointer text-zinc-500 max-sm:text-sm"
                                    :for="'customizable_options[' + option.id + '][' + index + ']'"
                                >
                                    @{{ item.label }}

                                    <span class="text-black">
                                        @{{ '+ ' + $shop.formatPrice(item.price) }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </template>

                    <!-- Radio Options -->
                    <template v-else-if="option.type == 'radio'">
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}
                        </x-shop::form.control-group.label>

                        <div class="grid gap-2 max-sm:gap-1">
                            <!-- "None" radio option for cases where the option is not required. -->
                            <div
                                class="flex select-none gap-x-4"
                                v-if="! Boolean(option.is_required)"
                            >
                                <x-shop::form.control-group.control
                                    type="radio"
                                    ::name="'customizable_options[' + option.id + '][]'"
                                    value="0"
                                    ::for="'customizable_options[' + option.id + '][' + index + ']'"
                                    ::id="'customizable_options[' + option.id + '][' + index + ']'"
                                    v-model="selectedItems"
                                    ::rules="{'required': Boolean(option.is_required)}"
                                    ::label="option.label"
                                    ::checked="true"
                                />

                                <label
                                    class="cursor-pointer text-zinc-500 max-sm:text-sm"
                                    :for="'customizable_options[' + option.id + '][' + index + ']'"
                                >
                                    @lang('shop::app.products.view.type.simple.customizable-options.none')
                                </label>
                            </div>

                            <!-- Options -->
                            <div
                                class="flex select-none items-center gap-x-4 max-sm:gap-x-1.5"
                                v-for="(item, index) in optionItems"
                            >
                                <x-shop::form.control-group.control
                                    type="radio"
                                    ::name="'customizable_options[' + option.id + '][]'"
                                    ::value="item.id"
                                    ::for="'customizable_options[' + option.id + '][' + index + ']'"
                                    ::id="'customizable_options[' + option.id + '][' + index + ']'"
                                    v-model="selectedItems"
                                    ::rules="{'required': Boolean(option.is_required)}"
                                    ::label="option.label"
                                />

                                <label
                                    class="cursor-pointer text-zinc-500 max-sm:text-sm"
                                    :for="'customizable_options[' + option.id + '][' + index + ']'"
                                >
                                    @{{ item.label }}

                                    <span class="text-black">
                                        @{{ '+ ' + $shop.formatPrice(item.price) }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </template>

                    <!-- Select Options -->
                    <template v-else-if="option.type == 'select'">
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="select"
                            ::name="'customizable_options[' + option.id + '][]'"
                            v-model="selectedItems"
                            ::rules="{'required': Boolean(option.is_required)}"
                            ::label="option.label"
                        >
                            <!-- "None" select option for cases where the option is not required. -->
                            <option
                                value="0"
                                v-if="! Boolean(option.is_required)"
                            >
                                @lang('shop::app.products.view.type.simple.customizable-options.none')
                            </option>

                            <option
                                v-for="item in optionItems"
                                :value="item.id"
                            >
                                @{{ item.label + ' + ' + $shop.formatPrice(item.price) }}
                            </option>
                        </x-shop::form.control-group.control>
                    </template>

                    <!-- Multiselect Options -->
                    <template v-else-if="option.type == 'multiselect'">
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
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
                                @{{ item.label + ' + ' + $shop.formatPrice(item.price) }}
                            </option>
                        </x-shop::form.control-group.control>
                    </template>

                    <!-- Date Field -->
                    <template v-else-if="option.type == 'date'">
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}

                            <span class="text-black">
                                @{{ '+ ' + $shop.formatPrice(option.price) }}
                            </span>
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
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
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}

                            <span class="text-black">
                                @{{ '+ ' + $shop.formatPrice(option.price) }}
                            </span>
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
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
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}

                            <span class="text-black">
                                @{{ '+ ' + $shop.formatPrice(option.price) }}
                            </span>
                        </x-shop::form.control-group.label>

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
                        <x-shop::form.control-group.label
                            class="!mt-0 max-sm:!mb-2.5"
                            ::class="{ 'required': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}

                            <span class="text-black">
                                @{{ '+ ' + $shop.formatPrice(option.price) }}
                            </span>
                        </x-shop::form.control-group.label>

                        <v-field
                            type="file"
                            :name="'customizable_options[' + option.id + '][]'"
                            :rules="{'required': Boolean(option.is_required), ...(option.supported_file_extensions && option.supported_file_extensions.length ? {'ext': option.supported_file_extensions.split(',').map(ext => ext.trim())} : {})}"
                            :label="option.label"
                            @change="handleFileChange"
                        >
                        </v-field>
                    </template>

                    <x-shop::form.control-group.error ::name="'customizable_options[' + option.id + '][]'" />
                </x-shop::form.control-group>
            </div>
        </script>

        <script type="module">
            app.component('v-product-customizable-options', {
                template: '#v-product-customizable-options-template',

                props: {
                    initialPrice: {
                        type: Number,

                        required: true,
                    },
                },

                data: function() {
                    return {
                        options: @json($options),

                        prices: [],
                    }
                },

                mounted() {
                    this.options = this.options.map((option) => {
                        if (! this.canHaveMultiplePriceOptions(option.type)) {
                            return {
                                id: option.id,
                                label: option.label,
                                type: option.type,
                                is_required: option.is_required,
                                max_characters: option.max_characters,
                                supported_file_extensions: option.supported_file_extensions,
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
                            price: 0,
                        };
                    });

                    this.prices = this.options.map((option) => {
                        return {
                            option_id: option.id,
                            price: 0,
                        };
                    });
                },

                computed: {
                    formattedTotalPrice: function() {
                        let totalPrice = this.initialPrice;

                        for (let price of this.prices) {
                            totalPrice += parseFloat(price.price);
                        }

                        return this.$shop.formatPrice(totalPrice);
                    }
                },

                methods: {
                    priceUpdated({ option, totalPrice }) {
                        let price = this.prices.find(price => price.option_id === option.id);

                        price.price = totalPrice;
                    },

                    canHaveMultiplePriceOptions(type) {
                        return ['checkbox', 'radio', 'select', 'multiselect'].includes(type);
                    },
                }
            });

            app.component('v-product-customizable-option-item', {
                template: '#v-product-customizable-option-item-template',

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
@endif
