<x-shop::layouts>
    {{-- Checkout component --}}
    {{-- Todo (@suraj-webkul): need change translation of this page.  --}}
    {{-- @translations --}}
    <v-checkout></v-checkout>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-checkout-template">
            <div class="container px-[60px] max-lg:px-[30px]">
                <div class="grid grid-cols-[1fr_auto] gap-[30px]">
                    <div class="grid gap-[30px] mt-[30px]">
                        <v-checkout-addresses></v-checkout-addresses>
                    </div>
                    
                    {{-- Cart summary --}}
                    @include('shop::checkout.onepage.cart-summary')
                </div>
            </div>
        </script>
    
        <script type="text/x-template" id="v-checkout-addresses-template">
            <div>
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, store)">
                        <x-shop::accordion>
                            <x-slot:header>
                                <div class="flex justify-between items-center">
                                    <h2 class="text-[26px] font-medium">@lang('Billing Address')</h2>
                                </div>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label>
                                        @lang('Company name')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[company_name]"
                                        :value="old('billing[company_name]')"
                                        label="Company name"
                                        placeholder="Company name"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[company_name]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('First name')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[first_name]"
                                        :value="old('billing[first_name]')"
                                        label="First name"
                                        rules="required"
                                        placeholder="First name"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[first_name]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('Last name')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[last_name]"
                                        :value="old('billing[last_name]')"
                                        label="Last name"
                                        rules="required"
                                        placeholder="Last name"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[last_name]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('Email')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="email"
                                        name="billing[email]"
                                        :value="old('billing[email]')"
                                        rules="required|email"
                                        label="Email"
                                        placeholder="email@example.com"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[email]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('Street address')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[address1][]"
                                        :value="old('billing[address1][]')"
                                        rules="required"
                                        label="Street address"
                                        placeholder="Street address"
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[address1][]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('Country')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="select"
                                        name="billing[country]"
                                        :value="old('billing[country]')"
                                        class="!text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required"
                                        label="Country"
                                        placeholder="Country"
                                    >
                                        <option value="">@lang('Select country')</option>
                                        @foreach (core()->countries() as $country)
                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[country]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('State')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[state]"
                                        :value="old('billing[state]')"
                                        class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required"
                                        label="State"
                                        placeholder="State"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[state]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('City')
                                    </x-shop::form.control-group.label>
        
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[city]"
                                        :value="old('billing[city]')"
                                        class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required"
                                        label="City"
                                        placeholder="City"
                                    >
                                    </x-shop::form.control-group.control>
        
                                    <x-shop::form.control-group.error
                                        control-name="billing[city]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('Zip/Postcode')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[postcode]"
                                        :value="old('billing[postcode]')"
                                        class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required"
                                        label="Zip/Postcode"
                                        placeholder="Zip/Postcode"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[postcode]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('Telephone')
                                    </x-shop::form.control-group.label>
            
                                    
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[phone]"
                                        :value="old('billing[phone]')"
                                        class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required|numeric"
                                        label="Telephone"
                                        placeholder="Telephone"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[phone]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
        
                                <div class="mt-[30px] pb-[15px]">
                                    <div class="grid gap-[10px]">
                                        @if ($cart->haveStockableItems())
                                            <div class="select-none flex gap-x-[15px]">
                                                <input 
                                                    type="checkbox" 
                                                    name="billing[isUseForShipping]"
                                                    id="billing[isUseForShipping]" 
                                                    class="hidden peer"
                                                    v-model="address.billing.isUseForShipping"
                                                >
                                                <span class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white"></span>
                                                <label for="billing[isUseForShipping]">@lang('Ship to this address')</label>
                                            </div>
                                        @endif
        
                                        @auth('customer')
                                            <div class="select-none flex gap-x-[15px]">
                                                <input 
                                                    type="checkbox"
                                                    name="billing[isSaveAsAddress]"
                                                    id="billing[isSaveAsAddress]"
                                                    class="hidden peer"
                                                    v-model="address.billing.isSaveAsAddress"
                                                >
        
                                                <span class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white"></span>
                                                <label for="billing[isSaveAsAddress]">@lang('Save this address')</label>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </x-slot:content>
                        </x-shop::accordion>

                        @if ($cart->haveStockableItems())
                            <div 
                                v-if="
                                    ! address.billing.isUseForShipping 
                                    && isNewShippingAddress
                                "
                            >
                                <x-shop::accordion>
                                    <x-slot:header>
                                        <div class="flex justify-between items-center">
                                            <h2 class="text-[26px] font-medium">@lang('Billing Address')</h2>
                                        </div>
                                    </x-slot:header>
                                
                                    <x-slot:content>
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label>
                                                @lang('Company name')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[company_name]"
                                                :value="old('shipping[company_name]')"
                                                label="Company name"
                                                placeholder="Company name"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[company_name]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('First name')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[first_name]"
                                                :value="old('shipping[first_name]')"
                                                label="First name"
                                                rules="required"
                                                placeholder="First name"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[first_name]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('Last name')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[last_name]"
                                                :value="old('shipping[last_name]')"
                                                label="Last name"
                                                rules="required"
                                                placeholder="Last name"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[last_name]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('Email')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="email"
                                                name="shipping[email]"
                                                :value="old('shipping[email]')"
                                                rules="required|email"
                                                label="Email"
                                                placeholder="email@example.com"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[email]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('Street address')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[address1][]"
                                                :value="old('shipping[address1][]')"
                                                rules="required"
                                                label="Street address"
                                                placeholder="Street address"
                                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[address1][]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('Country')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="select"
                                                name="shipping[country]"
                                                :value="old('shipping[country]')"
                                                class="!text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                rules="required"
                                                label="Country"
                                                placeholder="Country"
                                            >
                                                <option value="">@lang('Select country')</option>
                                                @foreach (core()->countries() as $country)
                                                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                                                @endforeach
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[country]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('State')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[state]"
                                                :value="old('shipping[state]')"
                                                class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                rules="required"
                                                label="State"
                                                placeholder="State"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[state]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('City')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[city]"
                                                :value="old('shipping[city]')"
                                                class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                rules="required"
                                                label="City"
                                                placeholder="City"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[city]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('Zip/Postcode')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[postcode]"
                                                :value="old('shipping[postcode]')"
                                                class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                rules="required"
                                                label="Zip/Postcode"
                                                placeholder="Zip/Postcode"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[postcode]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('Telephone')
                                            </x-shop::form.control-group.label>
                                        
                                            
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[phone]"
                                                :value="old('shipping[phone]')"
                                                class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                rules="required|numeric"
                                                label="Telephone"
                                                placeholder="Telephone"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[phone]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>

                                        <div class="mt-[30px] border-b-[1px] border-[#E9E9E9] pb-[15px]">
                                            <div class="grid gap-[10px]">
                                                @auth('customer')
                                                    <div class="select-none flex gap-x-[15px]">
                                                        <input 
                                                            type="checkbox"
                                                            name="shipping[isSaveAsAddress]"
                                                            id="shipping[isSaveAsAddress]"
                                                            class="hidden peer"
                                                            v-model="address.shipping.isSaveAsAddress"
                                                        >
                
                                                        <span class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white"></span>
                                                        <label for="shipping[isSaveAsAddress]">@lang('Save this address')</label>
                                                    </div>
                                                @endauth
                                            </div>
                                        </div>
                                    </x-slot:content>
                                </x-shop::accordion>
                            </div>
                        @endif
                    </form>
                </x-shop::form>
            </div>
        </script>

        <script type="module">
            app.component('v-checkout', {
                template: '#v-checkout-template',

                data() {
                    return {}
                },
            });

        </script>

        <script type="module">
            app.component('v-checkout-addresses', {
                template: '#v-checkout-addresses-template',

                data() {
                    return  {
                        address: {
                            billing: {
                                address1: [''],

                                isUseForShipping: true,
                            },

                            shipping: {
                                address1: ['']
                            },
                        },

                        customerAddress: @json(auth('customer')->user()->addresses),

                        isNewShippingAddress: false,

                        isNewBillingAddress: false,

                        allAddress: {},
                    }
                }, 
                
                created() {
                    if (! this.customerAddress) {
                        this.isNewShippingAddress = true;

                        this.isNewBillingAddress = true;
                    } else {
                        this.address.billing.first_name = this.address.shipping.first_name = this.customerAddress.first_name;
                        this.address.billing.last_name = this.address.shipping.last_name = this.customerAddress.last_name;
                        this.address.billing.email = this.address.shipping.email = this.customerAddress.email;

                        if (! this.customerAddress.length) {
                            this.isNewShippingAddress = true;

                            this.isNewBillingAddress = true;
                        } else {
                            this.allAddress = this.customerAddress;
                        }
                    }
                },

                methods: {
                    store(params) {
                        this.$axios.post('{{ route("shop.checkout.save_address") }}', params, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                console.log(response);
                            })
                            .catch(error => {
                                console.log();
                            })
                    }
                }
            })
        </script>
    @endPushOnce

</x-shop::layouts>
