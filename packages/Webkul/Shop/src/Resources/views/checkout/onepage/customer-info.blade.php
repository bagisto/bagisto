<v-checkout-address></v-checkout-address>

@pushOnce('scripts')
    <script type="text/x-template" id="v-checkout-address-template">
       	<!-- Billing Address -->
        <div class="">
            <div class="flex justify-between items-center">
                <h2 class="text-[26px] font-medium">1.Billing Address</h2>
                <span class="icon-arrow-up text-[24px]"></span>
            </div>
            <div class="" v-if="! this.newBillingAddress">
                <div class="grid mt-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr]" >
                    <!-- Single card addredd -->
                    <div class="border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap"  v-for='(addresses, index) in this.allAddress'>
                        <div class="flex justify-between items-center">
                            <input 
                                type="radio"  
                                name="billing[address_id]" 
                                id="billing[address_id]" 
                                value="addresses.id" 
                                rules="required"
                                v-model="address.billing.address_id" 
                            />
                            <p 
                                v-text="`${allAddress.first_name} ${allAddress.last_name}`" 
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
                        @click="addBillingAddress()" 
                    >
                        <div class="flex gap-x-[10px] items-center cursor-pointer">
                            <span class="icon-plus text-[30px] p-[10px] border border-black rounded-full"></span>
                            <p>Add new address</p>
                        </div>
                    </div>
                </div>
            </div>

            <form v-else class="rounded">
                <div class="grid grid-cols-2 gap-x-[30px]"> 
                    <div class="mb-4">
                        <label class="block text-[16px] mb-[15px] mt-[30px]" for="billing[first_name]" > First Name </label>

                        <input
                            type="text"
                            name="billing[first_name]"
                            id="billing[first_name]"
                            class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                            {{-- v-model="address.billing.first_name" --}}
                            placeholder="First name" 
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-[16px] mb-[15px] mt-[30px]" for="billing[last_name]"> Last Name </label>
    
                        <input
                            type="text"
                            name="billing[last_name]"
                            id="billing[last_name]"
                            class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                            {{-- v-model="address.billing.last_name" --}}
                            placeholder="Last name" 
                        />
                    </div>
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
                            {{-- v-if="! haveStates('billing')" --}}
                        />
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

                @auth('customer')
                    <div>
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
                        />
                            Save this address
                    </div>
                @endauth

            </form>

            <p class="flex gap-x-[10px] mt-[20px] text-[14px] text-light-black">
                <span class="icon-uncheck text-[20px]"></span> 
                address is the same as my billing address
            </p>
        </div>
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

                    address: {
                        billing: {
                            address1: [''],

                            use_for_shipping: true,
                        },

                        shipping: {
                            address1: ['']
                        },
                    },

                    allAddress: {},
                }
            },

            created: function() {

                if(! customerAddress) {
                    this.new_shipping_address = true;
                    this.newBillingAddress = true;
                } else {
                    this.address.billing.first_name = this.address.shipping.first_name = customerAddress.first_name;
                    this.address.billing.last_name = this.address.shipping.last_name = customerAddress.last_name;
                    this.address.billing.email = this.address.shipping.email = customerAddress.email;

                    if (customerAddress.length < 1) {
                        this.new_shipping_address = true;
                        this.newBillingAddress = true;
                    } else {
                        this.allAddress = customerAddress;
                    }
                }
            },

            methods: {
                addBillingAddress: function() {
        
                    this.newBillingAddress = true;
                },
            },

            mounted: function() {
               
            }
        });
    </script>
@endPushOnce

