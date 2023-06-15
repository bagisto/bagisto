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
                    <form
                        ref="formData"
                        @submit="handleSubmit($event, store)"
                    >
                        <div v-if="! isNewBillingAddress">
                            {{-- Addressess list --}}
                            <x-shop::accordion>
                                <x-slot:header>
                                    <div class="flex justify-between items-center">
                                        <h2 class="text-[26px] font-medium">@lang('Billing Address')</h2>
                                    </div>
                                </x-slot:header>
                            
                                <x-slot:content>
                                    <div class="grid mt-[30px] mb-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr]">
                                        <div 
                                            class="border border-[#e5e5e5] rounded-[12px] p-[0px] max-sm:flex-wrap relative select-none cursor-pointer"
                                            v-for="(addresses, index) in customerAddress"
                                        >
                                            <v-field
                                                type="radio"
                                                name="billing[address_id]"
                                                :id="'billing_address_id_' + addresses.id"
                                                :value="addresses.id"
                                                rules="required"
                                                v-model="address.billing.address_id"
                                                class="hidden peer"
                                                @change="validateForm"
                                            >
                                            </v-field>

                                            <span class="icon-radio-unselect text-[24px] text-navyBlue absolute right-[20px] top-[20px] peer-checked:icon-radio-select"></span>

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

                                                <p class="text-[#7D7D7D] mt-[25px]">
                                                    @{{ addresses.address1 }} @{{ addresses.addresses2 }},
                                                    @{{ addresses.city }}, 
                                                    @{{ addresses.state }}, @{{ addresses.country }}, 
                                                    @{{ addresses.postcode }}
                                                </p>
                                            </label>
                                        </div>

                                        <div 
                                            class="flex justify-center items-center border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap cursor-pointer"
                                            @click="addNewBillingAddress"
                                        >
                                            <div class="flex gap-x-[10px] items-center">
                                                <span class="icon-plus text-[30px] p-[30px] border border-black rounded-full"></span>
                                                <p>@lang('Add new Address')</p>
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
                                        <h2 class="text-[26px] font-medium">@lang('Billing Address')</h2>
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
                                                    @click="isNewBillingAddress = ! isNewBillingAddress"
                                                    v-if="isNewBillingAddress"
                                                >
                                                    <span class="icon-arrow-left text-[24px]"></span>
                                                    @lang('Back')
                                                </a>
                                            @endif
                                        </div>

                                        <x-shop::form.control-group.control
                                            type="text"
                                            name="billing[company_name]"
                                            ::value="address.billing.company_name"
                                            label="Company name"
                                            placeholder="Company name"
                                            v-model="address.billing.company_name"
                                            @change="validateForm"
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
                                            ::value="address.billing.first_name"
                                            label="First name"
                                            rules="required"
                                            placeholder="First name"
                                            v-model="address.billing.first_name"
                                            @change="validateForm"
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
                                            ::value="address.billing.last_name"
                                            label="Last name"
                                            rules="required"
                                            placeholder="Last name"
                                            v-model="address.billing.last_name"
                                            @change="validateForm"
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
                                            ::value="address.billing.email"
                                            rules="required|email"
                                            label="Email"
                                            placeholder="email@example.com"
                                            v-model="address.billing.email"
                                            @change="validateForm"
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
                                            ::value="address.billing.address1[0]"
                                            class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="Street address"
                                            placeholder="Street address"
                                            v-model="address.billing.address1[0]"
                                            @change="validateForm"
                                        >
                                        </x-shop::form.control-group.control>
                
                                        <x-shop::form.control-group.error
                                            control-name="billing[address1][]"
                                        >
                                        </x-shop::form.control-group.error>
                                    </x-shop::form.control-group>
                
                                    <x-shop::form.control-group
                                        class="!mb-4"
                                    >
                                        <x-shop::form.control-group.label class="!mt-[0px]">
                                            @lang('Country')
                                        </x-shop::form.control-group.label>
                
                                        <x-shop::form.control-group.control
                                            type="select"
                                            name="billing[country]"
                                            ::value="address.billing.country"
                                            class="!text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="Country"
                                            placeholder="Country"
                                            v-model="address.billing.country"
                                            @change="validateForm"
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
                                            ::value="address.billing.state"
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="State"
                                            placeholder="State"
                                            v-model="address.billing.state"
                                            @change="validateForm"
                                            v-if="! isHaveStates('billing')"
                                        >
                                        </x-shop::form.control-group.control>

                                        <x-shop::form.control-group.control
                                            type="select"
                                            name="billing[state]"
                                            ::value="address.billing.state"
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="State"
                                            placeholder="State"
                                            v-model="address.billing.state"
                                            @change="validateForm"
                                            v-if="isHaveStates('billing')"
                                        >
                                            <option value="">@lang('Select state')</option>

                                            <option 
                                                v-for='(state, index) in countryStates[address.billing.country]' 
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
                
                                    <x-shop::form.control-group>
                                        <x-shop::form.control-group.label class="!mt-[0px]">
                                            @lang('City')
                                        </x-shop::form.control-group.label>
            
                                        <x-shop::form.control-group.control
                                            type="text"
                                            name="billing[city]"
                                            ::value="address.billing.city"
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="City"
                                            placeholder="City"
                                            v-model="address.billing.city"
                                            @change="validateForm"
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
                                            ::value="address.billing.postcode"
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required"
                                            label="Zip/Postcode"
                                            placeholder="Zip/Postcode"
                                            v-model="address.billing.postcode"
                                            @change="validateForm"
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
                                            ::value="address.billing.phone"
                                            class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                            rules="required|numeric"
                                            label="Telephone"
                                            placeholder="Telephone"
                                            v-model="address.billing.phone"
                                            @change="validateForm"
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
                                                    <x-shop::form.control-group>
                                                        <x-shop::form.control-group.control
                                                            type="checkbox" 
                                                            name="billing[isUseForShipping]"
                                                            ::value="address.billing.isUseForShipping"
                                                            id="billing[isUseForShipping]" 
                                                            v-model="address.billing.isUseForShipping"
                                                            @change="validateForm"
                                                        >
                                                        </x-shop::form.control-group.control>
                                                    </x-shop::form.control-group>

                                                    <label for="billing[isUseForShipping]">@lang('Ship to this address')</label>
                                                </div>
                                            @endif
            
                                            @auth('customer')
                                                <div class="select-none flex gap-x-[15px]">
                                                    <x-shop::form.control-group>
                                                        <x-shop::form.control-group.control
                                                            type="checkbox"
                                                            name="billing[isSaveAsAddress]"
                                                            ::value="address.billing.isSaveAsAddress"
                                                            id="billing[isSaveAsAddress]"
                                                            v-model="address.billing.isSaveAsAddress"
                                                            @change="validateForm"
                                                        >
                                                        </x-shop::form.control-group.control>
                                                    </x-shop::form.control-group>

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
                                                    ::value="address.shipping.company_name"
                                                    label="Company name"
                                                    placeholder="Company name"
                                                    v-model="address.shipping.company_name"
                                                    @change="validateForm"
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
                                                    ::value="address.shipping.first_name"
                                                    label="First name"
                                                    rules="required"
                                                    placeholder="First name"
                                                    v-model="address.shipping.first_name"
                                                    @change="validateForm"
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
                                                    ::value="address.shipping.last_name"
                                                    label="Last name"
                                                    rules="required"
                                                    placeholder="Last name"
                                                    v-model="address.shipping.last_name"
                                                    @change="validateForm"
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
                                                    ::value="address.shipping.email"
                                                    rules="required|email"
                                                    label="Email"
                                                    placeholder="email@example.com"
                                                    v-model="address.shipping.email"
                                                    @change="validateForm"
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
                                                    ::value="address.shipping.address1[0]"
                                                    class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="Street address"
                                                    placeholder="Street address"
                                                    v-model="address.shipping.address1[0]"
                                                    @change="validateForm"
                                                >
                                                </x-shop::form.control-group.control>
                                            
                                                <x-shop::form.control-group.error
                                                    control-name="shipping[address1][]"
                                                >
                                                </x-shop::form.control-group.error>
                                            </x-shop::form.control-group>
                                            
                                            <x-shop::form.control-group
                                                class="!mb-4"
                                            >
                                                <x-shop::form.control-group.label class="!mt-[0px]">
                                                    @lang('Country')
                                                </x-shop::form.control-group.label>
                                            
                                                <x-shop::form.control-group.control
                                                    type="select"
                                                    name="shipping[country]"
                                                    ::value="address.shipping.country"
                                                    class="!text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="Country"
                                                    placeholder="Country"
                                                    v-model="address.shipping.country"
                                                    @change="validateForm"
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
                                                    ::value="address.shipping.state"
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="State"
                                                    placeholder="State"
                                                    v-model="address.shipping.state"
                                                    @change="validateForm"
                                                    v-if="! isHaveStates('shipping')"
                                                >
                                                </x-shop::form.control-group.control>

                                                <x-shop::form.control-group.control
                                                    type="select"
                                                    name="shipping[state]"
                                                    ::value="address.shipping.state"
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="State"
                                                    placeholder="State"
                                                    v-model="address.shipping.state"
                                                    @change="validateForm"
                                                    v-if="isHaveStates('shipping')"
                                                >
                                                    <option value="">@lang('Select state')</option>

                                                    <option 
                                                        v-for='(state, index) in countryStates[address.shipping.country]' 
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
                                            
                                            <x-shop::form.control-group>
                                                <x-shop::form.control-group.label class="!mt-[0px]">
                                                    @lang('City')
                                                </x-shop::form.control-group.label>
                                            
                                                <x-shop::form.control-group.control
                                                    type="text"
                                                    name="shipping[city]"
                                                    ::value="address.shipping.city"
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="City"
                                                    placeholder="City"
                                                    v-model="address.shipping.city"
                                                    @change="validateForm"
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
                                                    ::value="address.shipping.postcode"
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required"
                                                    label="Zip/Postcode"
                                                    placeholder="Zip/Postcode"
                                                    v-model="address.shipping.postcode"
                                                    @change="validateForm"
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
                                                    ::value="address.shipping.phone"
                                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                                    rules="required|numeric"
                                                    label="Telephone"
                                                    placeholder="Telephone"
                                                    v-model="address.shipping.phone"
                                                    @change="validateForm"
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
                                                                name="billing[isSaveAsAddress]"
                                                                ::value="address.billing.isSaveAsAddress"
                                                                id="billing[isSaveAsAddress]"
                                                                class="hidden peer"
                                                                v-model="address.billing.isSaveAsAddress"
                                                                @change="validateForm"
                                                            >
                    
                                                            <span class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white"></span>
                                                            <label for="billing[isSaveAsAddress]">@lang('Save this address')</label>
                                                        </div>
                                                    @endauth
                                                </div>
                                            </div>
                                        </x-slot:content>
                                    </x-shop::accordion>
                                </div>
                            @endif
                            
                            <button 
                                type="submit"
                                ref="submitButton"
                                class="hidden"
                            >
                            </button>
                        </div>
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

                                company_name: 'Webkul'
                            },

                            shipping: {
                                address1: ['']
                            },
                        },

                        customerAddress: @json(auth('customer')->user()->addresses),

                        isNewShippingAddress: false,

                        isNewBillingAddress: false,

                        countries: [],

                        countryStates: [],
                    }
                }, 
                
                created() {
                    this.fetchCountryStates();

                    this.fetchCountries ();

                    if (! this.customerAddress) {
                        this.isNewShippingAddress = true;

                        this.isNewBillingAddress = true;
                    } else {
                        this.address.billing.first_name = this.address.shipping.first_name = "{{ auth('customer')->user()->first_name }}";
                        this.address.billing.last_name = this.address.shipping.last_name = "{{ auth('customer')->user()->last_name }}";
                        this.address.billing.email = this.address.shipping.email = "{{ auth('customer')->user()->email }}";

                        if (! this.customerAddress.length) {
                            this.isNewShippingAddress = true;

                            this.isNewBillingAddress = true;
                        } else {
                            for (let country in this.countries) {
                                for (let code in this.customerAddress) {
                                    if (this.customerAddress[code].country) {
                                        if (this.customerAddress[code].country == this.countries[country].code) {
                                            this.customerAddress[code]['country'] = this.countries[country].name;
                                        }
                                    }
                                }
                            }
                        }
                    }
                },

                methods: {
                    validateForm() {
                        this.$refs.submitButton.click();
                    },

                    store(params) {
                        this.$axios.post('{{ route("shop.checkout.save_address") }}', this.address, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                console.log(response);
                            })
                            .catch(error => {                 
                                console.log(error);
                            })
                    },

                    fetchCountries() {
                        this.$axios.get("{{ route('shop.countries') }}")
                            .then(response => {
                                this.countries = response.data.data;
                            })
                            .catch(function (error) {});
                    },

                    fetchCountryStates() {
                        this.$axios.get("{{ route('shop.countries.states') }}")
                            .then(response => {
                                this.countryStates = response.data.data;
                            })
                            .catch(function (error) {});
                    },

                    addNewBillingAddress() {
                        this.isNewBillingAddress = true;

                        this.address.billing.address_id = null;
                    },

                    isHaveStates(addressType) {
                        if (
                            this.countryStates[this.address[addressType].country]
                            && this.countryStates[this.address[addressType].country].length
                        ) {
                            return true;
                        }

                        return false;
                    },
                }
            })
        </script>
    @endPushOnce

</x-shop::layouts>
