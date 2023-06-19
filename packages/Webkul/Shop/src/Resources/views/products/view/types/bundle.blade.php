@if ($product->type == 'bundle')

    {!! view_render_event('bagisto.shop.products.view.bundle-options.before', ['product' => $product]) !!}

    <v-bundle-option-list></v-bundle-option-list>

    {!! view_render_event('bagisto.shop.products.view.bundle-options.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-bundle-option-list-template">
            <div>
                {{-- @translations --}}
                <h3 class="font-bold">@lang('Customize Options')</h3>

                <bundle-option-item
                    v-for="(option, index) in options"
                    :option="option"
                    :key="index"
                    :index="index"
                    @onProductSelected="productSelected(option, $event)">
                </bundle-option-item>

                {{-- @translations --}}
                <h3>@lang('Your Customization')</h3>

                <x-shop::quantity-changer   
                    name="quantity"
                    value="1"
                    class="gap-x-[16px] w-max rounded-[12px] py-[10px] px-[17px] mt-3"
                >
                </x-shop::quantity-changer>

                <div class="flex justify-between items-center mt-[20px]">
                    <p class="text-[14px]">@lang('Total Amount')</p>
                    <p class="text-[18px] font-medium">$ @{{ formatted_total_price }}</p>
                </div>

                <ul class="grid gap-[20px] text-[14px]">
                    <li v-for="(option, index) in options">
                        @{{ option.label }}

                        <div 
                            v-for="(product, index1) in option.products"
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
            <div class="border-b-[1px] border-[#E9E9E9] pb-[15px]">
                <div>
                    <label class="block text-[16px] mb-[15px]">@{{ option.label }}</label>

                    <v-bundle-select-option
                        v-if="option.type == 'select'"
                        :option="option"
                        :selected_product="selected_product"
                        @selectedProduct="setProductValue($event)"
                    >
                    </v-bundle-select-option>
                    
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
                                class="ml-2"
                                :for="'bundle_options[' + option.id + '][]'"
                            > 
                                {{-- @translations --}}
                                @lang('None')
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
                                        :id="'bundle_options[' + index + '][]'" 
                                        class="hidden peer"
                                        v-model="selected_product" 
                                    >

                                    <span class="icon-radio-unselect text-[24px] text-navyBlue peer-checked:icon-radio-select"></span>

                                    <label :for="'bundle_options[' + index + '][]'">
                                        @{{ product.name }}
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
                                    :id="'bundle_options[' + index + '][]'"
                                    class="hidden peer"
                                    v-model="selected_product"
                                >

                                <span class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white"></span>

                                <label :for="'bundle_options[' + index + '][]'">@{{ product.name }}</label>
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
                                {{-- @translations --}}
                                @lang('None')
                            </option>

                            <option 
                                v-for="(product, index2) in option.products" 
                                :value="product.id"
                            >
                                @{{ product.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div v-if="option.type == 'select' || option.type == 'radio'">
                    <x-shop::quantity-changer   
                        ::name="'bundle_option_qty[' + option.id + ']'"
                        ::value="product_qty"
                        class="gap-x-[16px] w-max rounded-[12px] py-[10px] px-[17px] mt-5"
                        @change="qtyUpdated($event)"
                    >
                    </x-shop::quantity-changer>
                </div>
            </div>
        </script>

        <script type="text/x-template" id="v-bundle-select-option-template">
            <div>                
                <button 
                    class="text-[#7D7D7D] w-full marker:shadow appearance-none border rounded focus:ring-2 focus:outline-none focus:ring-black text-[14px] font-medium py-2 px-3 text-center inline-flex items-center justify-between" 
                    type="button"
                    @click="showOption = ! showOption"
                >
                    @{{ value.name }}
                    <span class="icon-sort text-[24px]"></span>
                </button>
                
                <div 
                    class=" w-full z-10 bg-white divide-y divide-gray-100 rounded shadow absolute z-10"
                    v-if="showOption"
                >
                    <ul 
                        class="py-2 text-[14px] text-[#7D7D7D]" 
                        aria-labelledby="dropdownDefaultButton"
                    >
                        <li 
                            v-for="(product, index) in option.products"
                            @click="select(product)"
                        >
                            <span class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                @{{ product.name }}
                            </span>
                        </li>
                    </ul>
                </div>  
                
                <input 
                    type="hidden"
                    :name="'bundle_options[' + option.id + '][]'" 
                    :value="value.id"
                />
            </div>
        </script>

        <script type="module">
            app.component('v-bundle-select-option', {
                template: '#v-bundle-select-option-template',

                props:['option', 'selected_product'],

                data: function() {
                    return  {
                        {{-- @translations --}}
                        value: {},

                        showOption: false
                    }
                },

                created() {
                    this.value = this.option.products.find(data => data.id == this.selected_product)

                    this.$emit('selected-product', this.value.id);
                },

                methods: {
                    select(product) {
                        this.value = product;

                        this.showOption = false

                        this.$emit('selected-product', product.id);
                    }
                }
            });

            app.component('v-bundle-option-list', {
                template: '#v-bundle-option-list-template',

                data: function() {
                    return {
                        config: @json(app('Webkul\Product\Helpers\BundleOption')->getBundleConfig($product)),

                        options: [],

                        currency_options: @json(core()->getAccountJsSymbols())
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

            app.component('bundle-option-item', {
                template: '#bundle-option-item-template',

                props: ['index', 'option'],

                data: function() {
                    return {
                        selected_product: (this.option.type == 'checkbox' || this.option.type == 'multiselect')  ? [] : null,

                        qty_validations: ''
                    }
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
                        if (! this.option.products.find(data => data.id == this.selected_product)) {
                            return;
                        }

                        this.option.products.find(data => data.id == this.selected_product).qty = qty;

                    },

                    setProductValue(value) {
                        this.selected_product = value;
                    }
                }
            });
        </script>
    @endPushOnce
@endif