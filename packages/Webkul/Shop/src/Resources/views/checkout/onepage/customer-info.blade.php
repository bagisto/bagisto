<v-checkout-address></v-checkout-address>

@pushOnce('scripts')
    <script type="text/x-template" id="v-checkout-address-template">
       	<!-- Billing Address -->
        <form>            
            <div class="">
                <div class="" v-if="! this.newBillingAddress">
                    <div class="flex justify-between items-center">
                        <h2 class="text-[26px] font-medium">1.Billing Address</h2>
                        <span class="icon-arrow-up text-[24px]"></span>
                    </div>

                    <div class="grid mt-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr]" >
                        <!-- Single card addredd -->
                        <div class="border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap"  v-for='(addresses, index) in this.allAddress'>
                            <div class="flex justify-between items-center">
                                <input 
                                    type="radio"  
                                    name="billing[address_id]" 
                                    id="billing[address_id]" 
                                    rules="required"
                                    v-model="address.billing.address_id" 
                                    :value="addresses.id"
                                />

                                <p 
                                    v-text="`${addresses.first_name} ${addresses.last_name}`" 
                                    class="text-[16px] font-medium"
                                >
                                </p>
                                
                                <div class="flex gap-[25px] items-center" >
                                    <div class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white w-max font-medium p-[5px] rounded-[10px] text-center text-[12px]">
                                        Default Address
                                    </div>
                                </div>

                            </div>

                            <div class="text-[#7D7D7D] mt-[25px]">
                                <p>
                                    <span v-if="addresses.company_name != ''">
                                        @{{addresses.company_name}},
                                    </span>

                                        @{{addresses.address1}},
                                        @{{addresses.city}},
                                        @{{addresses.state}},  
                                        
                                    <span v-if="addresses.country">
                                        @{{addresses.country}},
                                    </span>

                                    <span v-if="addresses.postcode">
                                        @{{addresses.postcode}},
                                    </span>

                                        @{{addresses.phone}}
                                </p>
                            </div>
                        </div>

                        <!-- Single card addredd -->
                        <div  
                            class="flex justify-center items-center border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap"
                            @click="addBillingAddress()" 
                        >
                            <div class="flex gap-x-[10px] items-center cursor-pointer">
                                <span class="icon-plus text-[30px] p-[10px] border border-black rounded-full"></span>
                                <p>Add new address</p>
                            </div>
                        </div>
                    </div>

                    @if ($cart->haveStockableItems())
                        <div class="mb-4">
                            <label class="block text-[16px] mb-[15px] mt-[30px]" for="billing[use_for_shipping]"></label>
                                <input
                                    type="checkbox"
                                    name="billing[use_for_shipping]"
                                    id="billing[use_for_shipping]"
                                    v-model="address.billing.use_for_shipping"
                                />
            
                                {{ __('shop::app.checkout.onepage.use_for_shipping') }}
                        </div>
                    @endif
                </div>

                <div v-if="this.newBillingAddress" class="rounded">
                    <div>
                        <div class="flex justify-between items-center">
                            <h2 class="text-[26px] font-medium">1.Billing Address</h2>
                            <span class="icon-arrow-up text-[24px]"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-[30px]"> 
                        <div class="mb-4">
                            <label class="block text-[16px] mb-[15px] mt-[30px]" for="billing[first_name]" > First Name </label>

                            <input
                                type="text"
                                name="billing[first_name]"
                                id="billing[first_name]"
                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                placeholder="First name" 
                                v-model="address.billing.first_name"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-[16px] mb-[15px] mt-[30px]" for="billing[last_name]"> Last Name </label>
        
                            <input
                                type="text"
                                name="billing[last_name]"
                                id="billing[last_name]"
                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                placeholder="Last name" 
                                v-model="address.billing.last_name"
                            />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-[16px] mb-[15px] mt-[30px]"  for="billing[email]"> Email </label>
                        <input
                            type="text"
                            name="billing[email]"
                            id="billing[email]"
                            class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                            placeholder="Email"
                            v-model="address.billing.email" 
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-[16px] mb-[15px] mt-[30px]"  for="billing[company_name]"> Company </label>
                        <input
                            type="text"
                            name="billing[company_name]"
                            id="billing[company_name]"
                            class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                            placeholder="Company Name" 
                            v-model="address.billing.company_name"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-[16px] mb-[15px] mt-[30px]" for="billing_address_0"> Address </label>
                        <input
                            type="text"
                            name="billing[address1][]"
                            id="billing_address_0" 
                            class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                            placeholder="Address" 
                            v-model="address.billing.address1[0]"
                        />
                    </div>

                    @if (
                        core()->getConfigData('customer.address.information.street_lines')
                        && core()->getConfigData('customer.address.information.street_lines') > 0
                    )

                        <div class="mb-4">
                            <label 
                                class="block text-[16px] mb-[15px] mt-[30px]"
                            > 
                                Streat 
                            </label>
                            <input
                                type="text"
                                name="billing[address1][{{ $i }}]"
                                id="billing_address_{{ $i }}"
                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                placeholder="Streat name" 
                                v-model="address.billing.address1[{{$i}}]"
                            />
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-x-[30px]">

                        <div class="relative">
                            <label 
                                class="block text-[16px] mb-[15px] mt-[30px]" 
                                for="billing[country]"
                            > 
                                Country 
                            </label>

                            <select
                                type="text"
                                name="billing[country]"
                                id="billing[country]"
                                class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                rules="required"
                                v-model="address.billing.country"
                            >                       
                                <option value="">Select country</option>

                                @foreach (core()->countries() as $country)
                                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label 
                                class="block text-[16px] mb-[15px] mt-[30px]" 
                                for="billing[city]"
                            > 
                                City 
                            </label>

                            <input
                                type="text"
                                name="billing[city]"
                                id="billing[city]"
                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                rules="required"
                                v-model="address.billing.city"
                                placeholder="City" 
                            />
                        </div>

                        <div class="mb-4">
                            <label 
                                class="block text-[16px] mb-[15px] mt-[30px]" 
                                for="billing[state]"
                            > 
                                State 
                            </label>

                            <input
                                type="text"
                                name="billing[state]"
                                id="billing[state]"
                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                placeholder="State" 
                                v-model="address.billing.state"
                                v-if="! haveStates('billing')"
                            />

                            <select
                                name="billing[state]"
                                id="billing[state]"   
                                class="text-[14px] border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"       
                                v-model="address.billing.state"
                                v-if="haveStates('billing')"
                            >
                            <option value="">select</option>
        
                                <option 
                                    v-for='(state, index) in countryStates[address.billing.country]' 
                                    value="state.code" 
                                    v-text="state.default_name">
                                </option>
                            </select>

                        </div>
                        
                        <div class="mb-4">
                            <label 
                                class="block text-[16px] mb-[15px] mt-[30px]" 
                                for="billing[postcode]"
                            > 
                                Zip Code 
                            </label>

                            <input
                                type="text"
                                name="billing[postcode]"
                                id="billing[postcode]"
                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                rules="required"
                                placeholder="Zip Code" 
                                v-model="address.billing.postcode"
                            />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label 
                            class="block text-[16px] mb-[15px] mt-[30px]" 
                            for="billing[phone]"
                        > 
                            Phone Number 
                        </label>

                        <input
                            type="text"
                            name="billing[phone]"
                            id="billing[phone]"
                            class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                            rules="required|numeric"
                            placeholder="Phone no." 
                            v-model="address.billing.phone"
                        
                        />
                    </div>

                    @if ($cart->haveStockableItems())
                        <div class="mb-4">
                            <label class="block text-[16px] mb-[15px] mt-[30px]" for="billing[use_for_shipping]"></label>
                                <input
                                    type="checkbox"
                                    name="billing[use_for_shipping]"
                                    id="billing[use_for_shipping]"
                                    v-model="address.billing.use_for_shipping"
                                />
            
                                {{ __('shop::app.checkout.onepage.use_for_shipping') }}
                        </div>
                    @endif

                    @auth('customer')
                        <div class="mb-4">
                            <label 
                                class="block text-[16px] mb-[15px] mt-[30px]" 
                                for="billing[save_as_address]"
                            >
                            </label>

                            <input
                                type="checkbox"
                                id="billing[save_as_address]"
                                name="billing[save_as_address]"
                                v-model="address.billing.save_as_address"
                                @change="saveAddress"
                            />
                                Save this address
                        </div>
                    @endauth
                    </div>
                </div>
                
            @if ($cart->haveStockableItems())
                    <div class="">
                        <div class="" v-if="! address.billing.use_for_shipping && ! this.newShippingAddress">
                            <div class="flex justify-between items-center">
                                <h2 class="text-[26px] font-medium">2.Shipping Address</h2>
                                <span class="icon-arrow-up text-[24px]"></span>
                            </div>

                            <div class="grid mt-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr]" >
                                <!-- Single card addredd -->
                                <div class="border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap"  v-for='(addresses, index) in this.allAddress'>
                                    <div class="flex justify-between items-center">
                                        <input 
                                            type="radio"  
                                            name="shipping[address_id]"
                                            id="shipping[address_id]"
                                            :value="addresses.id"
                                            rules="required"
                                            v-model="address.shipping.address_id"
                                        />
                                        <p 
                                            v-text="`${addresses.first_name} ${addresses.last_name}`" 
                                            class="text-[16px] font-medium"
                                        >
                                        </p>
                                        
                                        <div class="flex gap-[25px] items-center" >
                                            <div
                                                class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white w-max font-medium p-[5px] rounded-[10px] text-center text-[12px]">
                                                Default Address
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-[#7D7D7D] mt-[25px]">
                                        <p>
                                            <span v-if="addresses.company_name != ''">
                                                @{{addresses.company_name}},
                                            </span>
            
                                                @{{addresses.address1}},
                                                @{{addresses.city}},
                                                @{{addresses.state}},  
                                                
                                            <span v-if="addresses.country">
                                                @{{addresses.country}},
                                            </span>
            
                                            <span v-if="addresses.postcode">
                                                @{{addresses.postcode}},
                                            </span>
            
                                                @{{addresses.phone}}
                                        </p>
                                    </div>
                                </div>
                                <!-- Single card addredd -->
                                <div  
                                    class="flex justify-center items-center border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap"
                                    @click="addShippingAddress()" 
                                >
                                    <div class="flex gap-x-[10px] items-center cursor-pointer">
                                        <span class="icon-plus text-[30px] p-[10px] border border-black rounded-full"></span>
                                        <p>Add new address</p>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div v-if="! address.billing.use_for_shipping && this.newShippingAddress" class="rounded">
                            <div class="flex justify-between items-center">
                                <h2 class="text-[26px] font-medium">2.Shipping Address</h2>
                                <span class="icon-arrow-up text-[24px]"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-x-[30px]"> 
                                <div class="mb-4">
                                    <label class="block text-[16px] mb-[15px] mt-[30px]" for="shipping[first_name]"  > First Name </label>
            
                                    <input
                                        type="text"
                                        name="shipping[first_name]"
                                        id="shipping[first_name]"
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        placeholder="First name" 
                                        v-model="address.shipping.first_name"
                                    />
                                </div>
            
                                <div class="mb-4">
                                    <label class="block text-[16px] mb-[15px] mt-[30px]" for="shipping[last_name]"> Last Name </label>
                
                                    <input
                                        type="text"
                                        name="shipping[last_name]"
                                        id="shipping[last_name]"
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        placeholder="Last name" 
                                        v-model="address.shipping.last_name"
                                    />
                                </div>
                            </div>
            
                            <div class="mb-4">
                                <label class="block text-[16px] mb-[15px] mt-[30px]" for="shipping[email]"> Email </label>
                                <input
                                    type="email"
                                    name="shipping[email]"
                                    id="shipping[email]"
                                    class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                    rules="required|email"
                                    placeholder="Email" 
                                    v-model="address.shipping.email"
                                />
                            </div>
            
                            <div class="mb-4">
                                <label class="block text-[16px] mb-[15px] mt-[30px]" for="shipping_address_0" > Address </label>
                                <input
                                    type="text"
                                    name="shipping[address1][]"
                                    id="shipping_address_0"
                                    class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                    placeholder="Address" 
                                    v-model="address.shipping.address1[0]"
                                />
                            </div>
            
                            @if (
                                core()->getConfigData('customer.address.information.street_lines')
                                && core()->getConfigData('customer.address.information.street_lines') > 0
                            )
            
                                <div class="mb-4">
                                    <label 
                                        class="block text-[16px] mb-[15px] mt-[30px]"
                                    > 
                                        Streat 
                                    </label>
                                    <input
                                        type="text"
                                        name="shipping[address1][{{ $i }}]"
                                        id="shipping_address_{{ $i }}"
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        placeholder="Streat name" 
                                        v-model="address.shipping.address1[{{$i}}]"
                                    />
                                </div>
                            @endif
            
                            <div class="grid grid-cols-2 gap-x-[30px]">
            
                                <div class="relative">
                                    <label 
                                        class="block text-[16px] mb-[15px] mt-[30px]" 
                                        for="shipping[country]" 
                                    > 
                                        Country 
                                    </label>
            
                                    <select
                                        type="text"
                                        name="shipping[country]"
                                        id="shipping[country]"
                                        class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required"
                                        v-model="address.shipping.country"
                                    >                       
                                        <option value="">Select country</option>
            
                                        @foreach (core()->countries() as $country)
                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
            
                                <div class="mb-4">
                                    <label 
                                        class="block text-[16px] mb-[15px] mt-[30px]" 
                                        for="shipping[city]"
                                    > 
                                        City 
                                    </label>
            
                                    <input
                                        type="text"
                                        name="shipping[city]"
                                        id="shipping[city]"
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required"
                                        v-model="address.shipping.city"
                                        placeholder="City" 
                                    />
                                </div>
            
                                <div class="mb-4">
                                    <label 
                                        class="block text-[16px] mb-[15px] mt-[30px]" 
                                        for="shipping[state]" 
                                    > 
                                        State 
                                    </label>
            
                                    <input
                                        type="text"
                                        name="shipping[state]"
                                        id="shipping[state]"
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        placeholder="State" 
                                        v-model="address.shipping.state"
                                        v-if="! haveStates('billing')"
                                    />

                                    <select
                                        name="shipping[state]"
                                        id="shipping[state]"
                                        class="text-[14px] border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline" 
                                        v-model="address.shipping.state"
                                        v-if="haveStates('shipping')"
                                    >
                                        <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>
                    
                                        <option 
                                            v-for='(state, index) in countryStates[address.shipping.country]' 
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label 
                                        class="block text-[16px] mb-[15px] mt-[30px]" 
                                        for="shipping[postcode]"
                                    > 
                                        Zip Code 
                                    </label>
            
                                    <input
                                        type="text"
                                        name="shipping[postcode]"
                                        id="shipping[postcode]"
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required"
                                        placeholder="Zip Code" 
                                        v-model="address.shipping.postcode"
                                    />
                                </div>
                            </div>
            
                            <div class="mb-4">
                                <label 
                                    class="block text-[16px] mb-[15px] mt-[30px]" 
                                    for="shipping[phone]"
                                > 
                                    Phone Number 
                                </label>
            
                                <input
                                    type="text"
                                    name="shipping[phone]"
                                    id="shipping[phone]"
                                    class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                    rules="required|numeric"
                                    placeholder="Phone no." 
                                    v-model="address.shipping.phone"
                                />
                            </div>
            
                            @auth('customer')
                                <div class="mb-4">
                                    <label 
                                        class="block text-[16px] mb-[15px] mt-[30px]" 
                                        for="shipping[save_as_address]"
                                    >
                                    </label>
            
                                    <input
                                        type="checkbox"
                                        id="shipping[save_as_address]"
                                        name="shipping[save_as_address]"
                                        v-model="address.shipping.save_as_address"
                                    />
                                        Save this address
                                </div>
                            @endauth
                        </div>
                    </div>
            {{-- @endif --}}
        </form>
    </script>

    <script type="module">

        let customerAddress = '';

        @auth('customer')
                @if(auth('customer')->user()->addresses)
                    customerAddress = @json(auth('customer')->user()->addresses);
                    customerAddress.email = "{{ auth('customer')->user()->email }}";
                    customerAddress.first_name = "{{ auth('customer')->user()->first_name }}";
                    customerAddress.last_name = "{{ auth('customer')->user()->last_name }}";
                @endif
        @endauth

        app.component('v-checkout-address', {
            template: '#v-checkout-address-template',

            data: function()  {
                return {
                    newBillingAddress: false,

                    newShippingAddress: false,

                    address: {
                        billing: {
                            address1: [],

                            use_for_shipping: true,
                        },

                        shipping: {
                            address1: []
                        },
                    },

                    allAddress: {},

                    countryStates: @json(core()->groupedStatesByCountries()),

                    country: @json(core()->countries()),
                }
            },

            created: function() {
                if(! customerAddress) {
                    this.newShippingAddress = true;
                    this.newBillingAddress = true;
                } else {
                    this.address.billing.first_name = this.address.shipping.first_name = customerAddress.first_name;
                    this.address.billing.last_name = this.address.shipping.last_name = customerAddress.last_name;
                    this.address.billing.email = this.address.shipping.email = customerAddress.email;

                    if (customerAddress.length < 1) {
                        this.newShippingAddress = true;
                        this.newBillingAddress = true;
                    } else {
                        this.allAddress = customerAddress;
                    }
                }
            },

            methods: {
                haveStates: function(addressType) {
                    if (this.countryStates[this.address[addressType].country] && this.countryStates[this.address[addressType].country].length)
                        return true;

                    return false;
                },

                addBillingAddress: function() {
                    this.newBillingAddress = true;
                    this.address.billing.address_id = null;
                },

                addShippingAddress: function() {
                    this.newShippingAddress = true;
                    this.address.shipping.address_id = null;
                },

                saveAddress: async function() {
                    let self = this;
                    this.disable_button = true;
                    this.saveAddressCheckbox = document.getElementsByName("billing[save_as_address]");
                    
                    if (this.saveAddressCheckbox.prop('checked') == true) {
                        this.saveAddressCheckbox.attr('disabled', 'disabled');
                        this.saveAddressCheckbox.prop('checked', true);
                    }

                    if (this.saveAddressCheckbox) {
                        this.saveAddressCheckbox = true;
                    }
                    
                    if (this.allAddress.length > 0) {
                        let address = this.allAddress.forEach(address => {
                            if (address.id == this.address.billing.address_id) {
                                this.address.billing.address1 = [address.address1];
                            }
                            
                            if (address.id == this.address.shipping.address_id) {
                                this.address.shipping.address1 = [address.address1];
                            }
                        });
                    }
                    
                    this.$http.post("{{ route('shop.checkout.save_address') }}", this.address)
                        .then(function(response) {
                            self.disable_button = false;

                            if (self.step_numbers[response.data.jump_to_section] == 2)
                                shippingHtml = Vue.compile(response.data.html)
                            else
                                paymentHtml = Vue.compile(response.data.html)

                            self.completed_step = self.step_numbers[response.data.jump_to_section] - 1;
                            self.current_step = self.step_numbers[response.data.jump_to_section];

                            shippingMethods = response.data.shippingMethods;
                            paymentMethods  = response.data.paymentMethods;

                            self.getOrderSummary();
                        })
                        .catch(function (error) {
                            self.disable_button = false;

                            self.handleErrorResponse(error.response, 'address-form')
                        })
                },
            },

            mounted: function() {
               
            }
        });
    </script>
@endPushOnce

