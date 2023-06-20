<v-checkout-addresses ref="vCheckoutAddress"></v-checkout-addresses>

@pushOnce('scripts')
    <script type="text/x-template" id="v-checkout-addresses-template">
        <div>
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, store)">
                    <div v-if="! address.billing.isNew">
                        {{-- Addressess list --}}
                        <x-shop::accordion>
                            <x-slot:header>
                                <div class="flex justify-between items-center">
                                    <h2 class="text-[26px] font-medium max-sm:text-[20px]">@lang('Billing Address')</h2>
                                </div>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <div class="grid mt-[30px] mb-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px]">
                                    <div 
                                        class="border border-[#e5e5e5] max-w-[414px] rounded-[12px] p-[0px] max-sm:flex-wrap relative select-none cursor-pointer"
                                        v-for="(addresses, index) in availableAddresses"
                                        @change="showBillingMethods(addresses)"
                                    >
                                        <v-field
                                            type="radio"
                                            name="billing[address_id]"
                                            :id="'billing_address_id_' + addresses.id"
                                            :value="addresses.id"
                                            rules="required"
                                            v-model="address.billing.address_id"
                                            class="hidden peer"
                                        >
                                        </v-field>

                                        <label 
                                            class="icon-radio-unselect text-[24px] text-navyBlue absolute right-[20px] top-[20px] peer-checked:icon-radio-select cursor-pointer"
                                            :for="'billing_address_id_' + addresses.id"
                                        ></label>

                                        <label 
                                            :for="'billing_address_id_' + addresses.id"
                                            class="block p-[20px] rounded-[12px] cursor-pointer"
                                        >
                                            <div class="flex justify-between items-center">
                                                <p class="text-[16px] font-medium">
                                                    @{{ addresses.first_name }} @{{ addresses.last_name }}
                                                    <span v-if="addresses.company_name">(@{{ addresses.company_name }})</span>
                                                </p>
                                            
                                                <div 
                                                    class="flex gap-[25px] items-center"
                                                    v-if="addresses.default_address"
                                                >
                                                    <div class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white w-max font-medium p-[5px] rounded-[10px] text-center text-[12px]">
                                                        @lang('Default Address')
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="text-[#7D7D7D] mt-[25px] text-[14px] text-[14px]">
                                                @{{ addresses.address1 }} @{{ addresses.addresses2 }},
                                                @{{ addresses.city }}, 
                                                @{{ addresses.state }}, @{{ addresses.country }}, 
                                                @{{ addresses.postcode }}
                                            </p>
                                        </label>
                                    </div>

                                    <div
                                        class="flex justify-center items-center border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap max-w-[414px] cursor-pointer gap-[10px]"
                                        @click="addNewBillingAddress"
                                    >
                                        <div class="flex gap-x-[10px] items-center">
                                            <span class="icon-plus text-[30px] p-[30px] border border-black rounded-full"></span>
                                            <p class="text-[16px]">@lang('Add new Address')</p>
                                        </div>
                                    </div>
                                </div>
                            </x-slot:content>
                        </x-shop::accordion>
                    </div>

                    <div v-else>
                        <x-shop::accordion>
                            <x-slot:header>
                                <div class="flex justify-between items-center">
                                    <h2 class="text-[26px] font-medium max-sm:text-[20px]">@lang('Billing Address')</h2>
                                </div>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <x-shop::form.control-group>

                                    <div class="flex text-right justify-between">
                                        <x-shop::form.control-group.label>
                                            @lang('Company name')
                                        </x-shop::form.control-group.label>
                                        
                                        @if(count(auth('customer')->user()->addresses))
                                            <a 
                                                class="flex"
                                                href="javascript:void(0)" 
                                                @click="address.billing.isNew = ! address.billing.isNew"
                                                v-if="address.billing.isNew"
                                            >
                                                <span class="icon-arrow-left text-[24px]"></span>
                                                @lang('Back')
                                            </a>
                                        @endif
                                    </div>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[company_name]"
                                        label="Company name"
                                        placeholder="Company name"
                                        v-model="address.billing.company_name"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[company_name]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            

                                <div class="grid grid-cols-2 gap-x-[20px]">
                                    <x-shop::form.control-group>
                                        <x-shop::form.control-group.label class="!mt-[0px]">
                                            @lang('First name')
                                        </x-shop::form.control-group.label>
                
                                        <x-shop::form.control-group.control
                                            type="text"
                                            name="billing[first_name]"
                                            label="First name"
                                            rules="required"
                                            placeholder="First name"
                                            v-model="address.billing.first_name"
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
                                            label="Last name"
                                            rules="required"
                                            placeholder="Last name"
                                            v-model="address.billing.last_name"
                                        >
                                        </x-shop::form.control-group.control>
                
                                        <x-shop::form.control-group.error
                                            control-name="billing[last_name]"
                                        >
                                        </x-shop::form.control-group.error>
                                    </x-shop::form.control-group>
                                </div>
            
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('Email')
                                    </x-shop::form.control-group.label>
            
                                    <x-shop::form.control-group.control
                                        type="email"
                                        name="billing[email]"
                                        rules="required|email"
                                        label="Email"
                                        placeholder="email@example.com"
                                        v-model="address.billing.email"
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
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required"
                                        label="Street address"
                                        placeholder="Street address"
                                        v-model="address.billing.address1[0]"
                                    >
                                    </x-shop::form.control-group.control>
            
                                    <x-shop::form.control-group.error
                                        control-name="billing[address1][]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
            

                                <div class="grid grid-cols-2 gap-x-[20px]">
                                    <x-shop::form.control-group
                                        class="!mb-4"
                                    >
                                        <x-shop::form.control-group.label class="!mt-[0px]">
                                            @lang('Country')
                                        </x-shop::form.control-group.label>
                
                                        <x-shop::form.control-group.control
                                            type="select"
                                            name="billing[country]"
                                            class="!text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="Country"
                                            placeholder="Country"
                                            v-model="address.billing.country"
                                        >
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
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="State"
                                            placeholder="State"
                                            v-model="address.billing.state"
                                            v-if="! isHaveStates('billing')"
                                        >
                                        </x-shop::form.control-group.control>

                                        <x-shop::form.control-group.control
                                            type="select"
                                            name="billing[state]"
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="State"
                                            placeholder="State"
                                            v-model="address.billing.state"
                                            v-if="isHaveStates('billing')"
                                        >
                                            <option value="">@lang('Select state')</option>

                                            <option 
                                                v-for='(state, index) in states[address.billing.country]' 
                                                :value="state.code" 
                                            >
                                                @{{ state.default_name }}
                                            </option>
                                        </x-shop::form.control-group.control>
                
                                        <x-shop::form.control-group.error
                                            control-name="billing[state]"
                                        >
                                        </x-shop::form.control-group.error>
                                    </x-shop::form.control-group>
                                </div>
            
                                <div class="grid grid-cols-2 gap-x-[20px]">
                                    <x-shop::form.control-group>
                                        <x-shop::form.control-group.label class="!mt-[0px]">
                                            @lang('City')
                                        </x-shop::form.control-group.label>
            
                                        <x-shop::form.control-group.control
                                            type="text"
                                            name="billing[city]"
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="City"
                                            placeholder="City"
                                            v-model="address.billing.city"
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
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="Zip/Postcode"
                                            placeholder="Zip/Postcode"
                                            v-model="address.billing.postcode"
                                        >
                                        </x-shop::form.control-group.control>
                
                                        <x-shop::form.control-group.error
                                            control-name="billing[postcode]"
                                        >
                                        </x-shop::form.control-group.error>
                                    </x-shop::form.control-group>
                                </div>

                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="!mt-[0px]">
                                        @lang('Telephone')
                                    </x-shop::form.control-group.label>
            
                                    
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[phone]"
                                        class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        rules="required|numeric"
                                        label="Telephone"
                                        placeholder="Telephone"
                                        v-model="address.billing.phone"
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
                                                    name="billing[is_use_for_shipping]"
                                                    id="billing[is_use_for_shipping]" 
                                                    class="hidden peer"
                                                    v-model="address.billing.is_use_for_shipping"
                                                >

                                                <label
                                                    class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white  cursor-pointer"
                                                    for="billing[is_use_for_shipping]"
                                                ></label>

                                                <label for="billing[is_use_for_shipping]" @click="addNewShippingAddress">@lang('Ship to this address')</label>
                                            </div>
                                        @endif
        
                                        @auth('customer')
                                            <div 
                                                class="select-none flex gap-x-[15px]"
                                                v-if="address.billing.is_use_for_shipping"
                                            >
                                                <input 
                                                    type="checkbox"
                                                    name="billing[is_save_as_address]"
                                                    id="billing[is_save_as_address]"
                                                    class="hidden peer"
                                                    v-model="address.billing.is_save_as_address"
                                                >

                                                <label
                                                    class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white  cursor-pointer"
                                                    for="billing[is_save_as_address]"
                                                ></label>

                                                <label for="billing[is_save_as_address]">@lang('Save this address')</label>
                                            </div>
                                        @endauth
                                    </div>
                                </div>

                                <div 
                                    class="flex justify-end mt-4 mb-4"
                                    v-if="address.billing.is_use_for_shipping"
                                >
                                    <button
                                        type="submit"
                                        class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                    >
                                        @lang('Confirm')
                                    </button>
                                </div>
                            </x-slot:content>
                            
                        </x-shop::accordion>

                        @if ($cart->haveStockableItems())
                            <div 
                                v-if="
                                    ! address.billing.is_use_for_shipping 
                                    && address.shipping.isNew
                                "
                            >
                                <x-shop::accordion>
                                    <x-slot:header>
                                        <div class="flex justify-between items-center">
                                            <h2 class="text-[26px] font-medium max-sm:text-[20px]">@lang('Shipping Address')</h2>
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
                                                label="Company name"
                                                placeholder="Company name"
                                                v-model="address.shipping.company_name"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[company_name]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>

                                        <div class="grid grid-cols-2 gap-x-[20px]">
                                            <x-shop::form.control-group>
                                                <x-shop::form.control-group.label class="!mt-[0px]">
                                                    @lang('First name')
                                                </x-shop::form.control-group.label>
                                            
                                                <x-shop::form.control-group.control
                                                    type="text"
                                                    name="shipping[first_name]"
                                                    label="First name"
                                                    rules="required"
                                                    placeholder="First name"
                                                    v-model="address.shipping.first_name"
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
                                                    label="Last name"
                                                    rules="required"
                                                    placeholder="Last name"
                                                    v-model="address.shipping.last_name"
                                                >
                                                </x-shop::form.control-group.control>
                                            
                                                <x-shop::form.control-group.error
                                                    control-name="shipping[last_name]"
                                                >
                                                </x-shop::form.control-group.error>
                                            </x-shop::form.control-group>
                                        </div>
                                        
                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('Email')
                                            </x-shop::form.control-group.label>
                                        
                                            <x-shop::form.control-group.control
                                                type="email"
                                                name="shipping[email]"
                                                rules="required|email"
                                                label="Email"
                                                placeholder="email@example.com"
                                                v-model="address.shipping.email"
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
                                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                rules="required"
                                                label="Street address"
                                                placeholder="Street address"
                                                v-model="address.shipping.address1[0]"
                                            >
                                            </x-shop::form.control-group.control>
                                        
                                            <x-shop::form.control-group.error
                                                control-name="shipping[address1][]"
                                            >
                                            </x-shop::form.control-group.error>
                                        </x-shop::form.control-group>
                                        
                                        <div class="grid grid-cols-2 gap-x-[20px]">
                                            <x-shop::form.control-group
                                                class="!mb-4"
                                            >
                                                <x-shop::form.control-group.label class="!mt-[0px]">
                                                    @lang('Country')
                                                </x-shop::form.control-group.label>
                                            
                                                <x-shop::form.control-group.control
                                                    type="select"
                                                    name="shipping[country]"
                                                    class="!text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="Country"
                                                    placeholder="Country"
                                                    v-model="address.shipping.country"
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
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="State"
                                                    placeholder="State"
                                                    v-model="address.shipping.state"
                                                    v-if="! isHaveStates('shipping')"
                                                >
                                                </x-shop::form.control-group.control>

                                                <x-shop::form.control-group.control
                                                    type="select"
                                                    name="shipping[state]"
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="State"
                                                    placeholder="State"
                                                    v-model="address.shipping.state"
                                                    v-if="isHaveStates('shipping')"
                                                >
                                                    <option value="">@lang('Select state')</option>

                                                    <option 
                                                        v-for='(state, index) in states[address.shipping.country]' 
                                                        :value="state.code"
                                                    >
                                                        @{{ state.default_name }}
                                                    </option>
                                                </x-shop::form.control-group.control>
                                            
                                                <x-shop::form.control-group.error
                                                    control-name="shipping[state]"
                                                >
                                                </x-shop::form.control-group.error>
                                            </x-shop::form.control-group>
                                        </div>

                                        <div class="grid grid-cols-2 gap-x-[20px]">
                                            <x-shop::form.control-group>
                                                <x-shop::form.control-group.label class="!mt-[0px]">
                                                    @lang('City')
                                                </x-shop::form.control-group.label>
                                            
                                                <x-shop::form.control-group.control
                                                    type="text"
                                                    name="shipping[city]"
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="City"
                                                    placeholder="City"
                                                    v-model="address.shipping.city"
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
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="Zip/Postcode"
                                                    placeholder="Zip/Postcode"
                                                    v-model="address.shipping.postcode"
                                                >
                                                </x-shop::form.control-group.control>
                                            
                                                <x-shop::form.control-group.error
                                                    control-name="shipping[postcode]"
                                                >
                                                </x-shop::form.control-group.error>
                                            </x-shop::form.control-group>
                                        </div>

                                        <x-shop::form.control-group>
                                            <x-shop::form.control-group.label class="!mt-[0px]">
                                                @lang('Telephone')
                                            </x-shop::form.control-group.label>
                                        
                                            
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="shipping[phone]"
                                                class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                rules="required|numeric"
                                                label="Telephone"
                                                placeholder="Telephone"
                                                v-model="address.shipping.phone"
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
                                                            name="billing[is_save_as_address]"
                                                            id="billing[is_save_as_address]"
                                                            class="hidden peer"
                                                            v-model="address.billing.is_save_as_address"
                                                        >
                
                                                        <span class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white"></span>
                                                        
                                                        <label for="billing[is_save_as_address]">@lang('Save this address')</label>
                                                    </div>
                                                @endauth
                                            </div>
                                        </div>

                                        <div
                                            class="flex justify-end mt-4 mb-4"
                                            v-if="! address.billing.is_use_for_shipping"
                                        >
                                            <button
                                                type="submit"
                                                class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                            >
                                                @lang('Confirm')
                                            </button>
                                        </div>
                                    </x-slot:content>
                                </x-shop::accordion>
                            </div>
                        @endif
                    </div>
                </form>
            </x-shop::form>
        </div>
    </script>

    <script type="module">
         app.component('v-checkout-addresses', {
            template: '#v-checkout-addresses-template',

            data() {
                return  {
                    address: {
                        billing: {
                            address1: [''],

                            is_use_for_shipping: true,

                            isNew: false,
                        },

                        shipping: {
                            address1: [''],

                            isNew: false,
                        },
                    },

                    availableAddresses: [],

                    countries: [],

                    states: [],
                }
            }, 
            
            created() {
                this.getCustomerAddress();

                this.getCountryStates();

                this.getCountries();
            },

            methods: {
                init() {
                    if (! this.availableAddresses) {
                        this.address.shipping.isNew = true;

                        this.address.billing.isNew = true;
                    } else {
                        this.address.billing.first_name = this.address.shipping.first_name = "{{ auth('customer')->user()->first_name }}";
                        this.address.billing.last_name = this.address.shipping.last_name = "{{ auth('customer')->user()->last_name }}";
                        this.address.billing.email = this.address.shipping.email = "{{ auth('customer')->user()->email }}";

                        if (! this.availableAddresses.length) {
                            this.address.shipping.isNew = true;

                            this.address.billing.isNew = true;
                        } else {
                            for (let country in this.countries) {
                                for (let code in this.availableAddresses) {
                                    if (this.availableAddresses[code].country) {
                                        if (this.availableAddresses[code].country == this.countries[country].code) {
                                            this.availableAddresses[code]['country'] = this.countries[country].code;
                                        }
                                    }
                                }
                            }
                        }
                    }
                },

                resetPaymentAndShipping() {
                    this.$parent.$refs.vShippingMethod.isShowShippingMethod = false;

                    this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;

                    this.$parent.$refs.vReview.isShowReviewSummary = false;
                },

                assignAddress() {
                    if (this.availableAddresses.length > 0) {
                        let address = this.availableAddresses.forEach(address => {
                            if (address.id == this.address.billing.address_id) {
                                this.address.billing.address1 = [address.address1];

                                if (address.email) {
                                    this.address.billing.email = address.email;
                                }

                                if (address.first_name) {
                                    this.address.billing.first_name = address.first_name;
                                }

                                if (address.last_name) {
                                    this.address.billing.last_name = address.last_name;
                                }

                                if (address.country) {
                                    this.address.billing.country = address.country;
                                }
                            }

                            if (address.id == this.address.shipping.address_id) {
                                this.address.shipping.address1 = [address.address1];

                                if (address.email) {
                                    this.address.shipping.email = address.email;
                                }

                                if (address.first_name) {
                                    this.address.shipping.first_name = address.first_name;
                                }

                                if (address.last_name) {
                                    this.address.shipping.last_name = address.last_name;
                                }

                                if (address.country) {
                                    this.address.shipping.country = address.country;
                                }
                            }
                        });
                    }
                },

                store() {
                    this.assignAddress();
                    
                    if (this.address.billing.is_use_for_shipping) {
                        this.address.shipping = this.address.billing;
                    }
                    
                    if (! this.address.billing.is_save_as_address) {
                        /*
                            * If the user not click on the save this address checkbox then the address1 array will take first index of address array
                            */
                        this.address.billing.address1 = this.address.billing.address1[0];

                        this.availableAddresses.push(this.address.billing);
                    } else {
                        this.$axios.post('{{ route("shop.checkout.save_address") }}', this.address)
                            .then(response => {
                                this.$parent.$refs.vShippingMethod.shippingMethods = response.data.data.shippingMethods;
                                
                                this.$parent.$refs.vShippingMethod.isShowShippingMethod = true;
                                
                                this.getCustomerAddress();
                            })
                            .catch(error => {                 
                                console.log(error);
                            })
                    }

                    this.address.billing.isNew = false
                },

                getCountries() {
                    this.$axios.get("{{ route('shop.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(function (error) {});
                },

                getCountryStates() {
                    this.$axios.get("{{ route('shop.countries.states') }}")
                        .then(response => {
                            this.states = response.data.data;
                        })
                        .catch(function (error) {});
                },

                getCustomerAddress() {
                    this.$axios.get("{{ route('api.shop.customers.account.addresses.index') }}")
                        .then(response => {
                            this.availableAddresses = response.data.data;

                            this.init();
                        })
                        .catch(function (error) {});
                },

                addNewShippingAddress: function () {
                    this.address.shipping.isNew = true;

                    this.address.shipping.address_id = null;

                    this.resetPaymentAndShipping();
                },

                addNewBillingAddress() {
                    this.address.billing.isNew = true;

                    this.address.billing.address_id = null;

                    this.$parent.$refs.vShippingMethod.isShowShippingMethod = false;

                    this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;
                },

                isHaveStates(addressType) {
                    if (
                        this.states[this.address[addressType].country]
                        && this.states[this.address[addressType].country].length
                    ) {
                        return true;
                    }

                    return false;
                },

                showBillingMethods(address) {
                    this.$parent.getOrderSummary();

                    let selectedAddress = this.availableAddresses.find(data => data.id == address.id);
                    
                    this.address.billing.is_save_as_address = true;

                    this.resetPaymentAndShipping();

                    this.store();
                }
            }
        });
    </script>
@endPushOnce