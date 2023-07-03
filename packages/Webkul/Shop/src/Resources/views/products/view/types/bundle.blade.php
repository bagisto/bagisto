@if ($product->type == 'bundle')
    {!! view_render_event('bagisto.shop.products.view.bundle-options.before', ['product' => $product]) !!}

    <v-bundle-option-list></v-bundle-option-list>

    {!! view_render_event('bagisto.shop.products.view.bundle-options.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-bundle-option-list-template">
            <div class="mt-[30px]">
                <bundle-option-item
                    v-for="(option, index) in options"
                    :option="option"
                    :key="index"
                    @onProductSelected="productSelected(option, $event)">
                </bundle-option-item>

                <div class="flex justify-between items-center my-[20px]">
                    <p class="text-[14px]">@lang('Total Amount')</p>
                    <p class="text-[18px] font-medium">@{{ formatted_total_price }}</p>
                </div>

                <ul class="grid gap-[10px] text-[16px]">
                    <li v-for="option in options">
                        <span class="inline-block mb-[5px]">
                            @{{ option.label }}
                        </span>

                        <div
                            class="text-[#7D7D7D]"
                            v-for="product in option.products"
                            :key="product.id"
                            :if="product.is_default"
                        >
                            @{{ product.qty + ' x ' + product.name }}
                        </div>
                    </li>
                </ul>
            </div>
        </script>

        <script type="text/x-template" id="bundle-option-item-template">
            <div class="mt-[30px] border-b-[1px] border-[#E9E9E9] pb-[15px]">
                <div>
                    <label class="block text-[16px] mb-[5px]">@{{ option.label }}</label>

                    <div v-if="option.type == 'select'">
                        <select
                            :name="'bundle_options[' + option.id + '][]'"
                            class="custom-select bg-white border border-[#E9E9E9] text-[16px] text-[#7D7D7D] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px] max-md:border-0 max-md:outline-none max-md:w-[110px] cursor-pointer"
                            v-model="selected_product"
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
                        </select>
                    </div>

                    <div v-if="option.type == 'radio'">
                        <span
                            class="flex"
                            v-if="! option.is_required"
                        >
                            <input
                                type="radio"
                                :name="'bundle_options[' + option.id + '][]'"
                                value="0"
                                :id="'bundle_options[' + option.id + '][]'"
                                class="hidden peer"
                                v-model="selected_product"
                            >

                            <span class="icon-radio-unselect text-[24px] text-navyBlue peer-checked:icon-radio-select"></span>

                            <label
                                class="text-[#7D7D7D]"
                                :for="'bundle_options[' + option.id + '][]'"
                            >
                                @lang('shop::app.products.view.type.bundle.none')
                            </label>
                        </span>

                        <span
                            class="flex"
                            v-for="(product, index) in option.products"
                        >
                            <div class="grid gap-[10px]">
                                <div class="select-none flex gap-x-[15px]">
                                    <input
                                        type="radio"
                                        :name="'bundle_options[' + option.id + '][]'"
                                        :value="product.id"
                                        :id="'bundle_options[' + option.id + '][' + index + ']'"
                                        class="hidden peer"
                                        v-model="selected_product"
                                    >

                                    <label
                                        class="icon-radio-unselect text-[24px] text-navyBlue peer-checked:icon-radio-select"
                                        :for="'bundle_options[' + option.id + '][' + index + ']'"
                                    ></label>

                                    <label class="text-[#7D7D7D]" :for="'bundle_options[' + option.id + '][' + index + ']'">
                                        @{{ product.name }}

                                        <span class="text-[#000000]">
                                            @{{ '+ ' + product.price.final.formatted_price }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </span>
                    </div>

                    <div v-if="option.type == 'checkbox'">
                        <div class="grid gap-[10px]">
                            <div
                                class="select-none flex gap-x-[15px]"
                                v-for="(product, index) in option.products"
                            >
                                <input
                                    type="checkbox"
                                    :name="'bundle_options[' + option.id + '][]'"
                                    :value="product.id"
                                    :id="'bundle_options[' + option.id + '][' + index + ']'"
                                    class="hidden peer"
                                    v-model="selected_product"
                                >

                                <label
                                    class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                    :for="'bundle_options[' + option.id + '][' + index + ']'"
                                ></label>

                                <label class="text-[#7D7D7D]" :for="'bundle_options[' + option.id + '][' + index + ']'">
                                    @{{ product.name }}

                                    <span class="text-[#000000]">
                                        @{{ '+ ' + product.price.final.formatted_price }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div v-if="option.type == 'multiselect'">
                        <select
                            :name="'bundle_options[' + option.id + '][]'"
                            class="border border-[#E9E9E9] text-[#7D7D7D] text-[16px] rounded-[2px] w-full px-[15px] py-[10px] focus:ring-2 focus:outline-none focus:ring-black overflow-y-auto overflow-x-hidden journal-scroll"
                            v-model="selected_product"
                            multiple
                        >
                            <option
                                value="0"
                                v-if="! option.is_required"
                            >
                                @lang('shop::app.products.view.type.bundle.none')
                            </option>

                            <option
                                v-for="(product, index2) in option.products"
                                :value="product.id"
                            >
                                @{{ product.name }}

                                <span class="text-[#000000]">
                                    @{{ '+ ' + product.price.final.formatted_price }}
                                </span>
                            </option>
                        </select>
                    </div>
                </div>

                <div v-if="option.type == 'select' || option.type == 'radio'">
                    <x-shop::quantity-changer
                        ::name="'bundle_option_qty[' + option?.id + ']'"
                        ::value="product_qty"
                        class="gap-x-[16px] w-max rounded-[12px] py-[10px] px-[17px] mt-5 !border-[#E9E9E9]"
                        @change="qtyUpdated($event)"
                    >
                    </x-shop::quantity-changer>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-bundle-option-list', {
                template: '#v-bundle-option-list-template',

                data: function() {
                    return {
                        config: @json(app('Webkul\Product\Helpers\BundleOption')->getBundleConfig($product)),

                        options: [],

                    }
                },

                computed: {
                    formatted_total_price: function() {
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

            app.component('bundle-option-item', {
                template: '#bundle-option-item-template',

                props: ['option'],

                data: function() {
                    return {
                        selected_product: (this.option.type == 'checkbox' || this.option.type == 'multiselect')  ? [] : null,
                    };
                },

                computed: {
                    product_qty: function() {
                        let qty = 0;

                        this.option.products.forEach((product, key) => {
                            if (this.selected_product == product.id) {
                                qty =  this.option.products[key].qty;
                            }
                        });

                        return qty;
                    }
                },

                watch: {
                    selected_product: function (value) {
                        this.$emit('onProductSelected', value);
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
                        if (! this.option.products.find(data => data.id == this.selected_product)) {
                            return;
                        }

                        this.option.products.find(data => data.id == this.selected_product).qty = qty;
                    }
                }
            });
        </script>
    @endPushOnce
@endif
