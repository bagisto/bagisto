<x-shop::layouts>
    {{-- Checkout component --}}
    {{-- Todo (@suraj-webkul): need change translation of this page.  --}}
    {{-- @translations --}}
    <v-checkout></v-checkout>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-checkout-template">
            <div class="container px-[60px] max-lg:px-[30px]">
                <div class="grid grid-cols-[1fr_auto] gap-[30px]">
                    <div class="grid mt-[30px]">
                        {{-- Customer addresses component --}}
                        <v-checkout-addresses ref="vCheckoutAddress"></v-checkout-addresses>

                        {{-- Shipping method component --}}
                        <v-shipping-method ref="vShippingMethod"></v-shipping-method>

                        {{-- Payment method component --}}
                        <v-payment-method ref="vPaymentMethod"></v-payment-method>

                        {{-- Order/Review Summary --}}
                        <v-review-summary ref="vReview"></v-review-summary>
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
                        <div v-if="! address.billing.isNew">
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
                                            ::value="address.billing.company_name"
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
                                                ::value="address.billing.first_name"
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
                                                ::value="address.billing.last_name"
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
                                            ::value="address.billing.email"
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
                                            ::value="address.billing.address1[0]"
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
                                                ::value="address.billing.country"
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
                                                ::value="address.billing.state"
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
                                                ::value="address.billing.state"
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
                                                ::value="address.billing.city"
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
                                                ::value="address.billing.postcode"
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
                                            ::value="address.billing.phone"
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
                                                        name="billing[isUseForShipping]"
                                                        ::value="address.billing.isUseForShipping"
                                                        id="billing[isUseForShipping]" 
                                                        class="hidden peer"
                                                        v-model="address.billing.isUseForShipping"
                                                    >

                                                    <label
                                                        class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white  cursor-pointer"
                                                        for="billing[isUseForShipping]"
                                                    ></label>

                                                    <label for="billing[isUseForShipping]" @click="addNewShippingAddress">@lang('Ship to this address')</label>
                                                </div>
                                            @endif
            
                                            @auth('customer')
                                                <div 
                                                    class="select-none flex gap-x-[15px]"
                                                    v-if="address.billing.isUseForShipping"
                                                >
                                                    <input 
                                                        type="checkbox"
                                                        name="billing[isSaveAsAddress]"
                                                        ::value="address.billing.isSaveAsAddress"
                                                        id="billing[isSaveAsAddress]"
                                                        class="hidden peer"
                                                        v-model="address.billing.isSaveAsAddress"
                                                    >

                                                    <label
                                                        class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white  cursor-pointer"
                                                        for="billing[isSaveAsAddress]"
                                                    ></label>

                                                    <label for="billing[isSaveAsAddress]">@lang('Save this address')</label>
                                                </div>
                                            @endauth
                                        </div>
                                    </div>

                                    <div 
                                        class="flex justify-end mt-4 mb-4"
                                        v-if="address.billing.isUseForShipping"
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
                                        ! address.billing.isUseForShipping 
                                        && address.shipping.isNew
                                    "
                                >
                                    <x-shop::accordion>
                                        <x-slot:header>
                                            <div class="flex justify-between items-center">
                                                <h2 class="text-[26px] font-medium">@lang('Shipping Address')</h2>
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
                                                        ::value="address.shipping.first_name"
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
                                                        ::value="address.shipping.last_name"
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
                                                    ::value="address.shipping.email"
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
                                                    ::value="address.shipping.address1[0]"
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
                                                        ::value="address.shipping.country"
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
                                                        ::value="address.shipping.state"
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
                                                        ::value="address.shipping.state"
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
                                                        ::value="address.shipping.city"
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
                                                        ::value="address.shipping.postcode"
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
                                                    ::value="address.shipping.phone"
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
                                                                name="billing[isSaveAsAddress]"
                                                                ::value="address.billing.isSaveAsAddress"
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

                                            <div
                                                class="flex justify-end mt-4 mb-4"
                                                v-if="! address.billing.isUseForShipping"
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

        <script type="text/x-template" id="v-shipping-method-template">
            <div v-if="isShowShippingMethod">
                <x-shop::accordion>
                    <x-slot:header>
                        <div class="flex justify-between mt-2 items-center">
                            <h2 class="text-[26px] font-medium">@lang('Shipping methods')</h2>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="flex">
                            <div
                                class="relative max-w-[218px] select-none m-2"
                                v-for="shippingMethod in shippingMethods"
                            >
                                <div v-for="rate in shippingMethod.rates">
                                    <input 
                                        type="radio"
                                        name="shipping_method"
                                        :id="rate.method"
                                        :value="rate.method"
                                        class="hidden peer"
                                        @change="save(rate.method)"
                                    >

                                    <label 
                                        class="icon-radio-unselect text-[24px] text-navyBlue absolute right-[20px] top-[20px] peer-checked:icon-radio-select cursor-pointer"
                                        :for="rate.method"
                                    >
                                    </label>

                                    <label 
                                        class="block border border-[#E9E9E9] p-[20px] rounded-[12px] h-[190px] cursor-pointer"
                                        :for="rate.method"
                                    >
                                        <span class="icon-flate-rate text-[60px] text-navyBlue"></span>

                                        <p class="text-[25px] font-semibold mt-[5px]">@{{ rate.base_price }}</p>
                                        
                                        <p class="text-[12px] font-medium mt-[10px]">
                                            <span class="font-medium">@{{ rate.method_title }}</span> - @{{ rate.method_description }}
                                        </p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-shop::accordion>
            </div>
        </script>

        <script type="text/x-template" id="v-payment-method-template">
            <div v-if="isShowPaymentMethod">
                <x-shop::accordion>
                    <x-slot:header>
                        <div class="flex justify-between mt-2 items-center">
                            <h2 class="text-[26px] font-medium">@lang('Payment methods')</h2>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="flex flex-wrap gap-[29px] mt-[30px] mb-5">
                            <div 
                                class="relative"
                                v-for="(payment, index) in paymentMethods"
                            >
                                <input 
                                    type="radio" 
                                    name="payment[method]" 
                                    :value="payment.payment"
                                    :id="payment.method"
                                    class="hidden peer"    
                                    @change="paymentMethodSelected(payment)"
                                >

                                <label :for="payment.method" class="icon-radio-unselect text-[24px] text-navyBlue absolute right-[20px] top-[20px] peer-checked:icon-radio-select"></label>

                                <label :for="payment.method" class="block border border-[#E9E9E9] p-[20px] rounded-[12px] w-[190px]">
                                    <img class="mx-w-[55px] max-h-[45px]" src="http://192.168.15.143/Velocity/resources/images/payapl.svg" alt="" title="">
                                    <p class="text-[14px] font-semibold mt-[5px]">@{{ payment.method_title }} </p>
                                    <p class="text-[12px] font-medium mt-[10px]">@{{ payment.description }}</p>
                                </label>
                                {{-- Toto implement the additionalDetails --}}
                                {{-- \Webkul\Payment\Payment::getAdditionalDetails($payment['method'] --}}
                            </div>
                        </div>
                    </x-slot:content>
                </x-shop::accordion>
            </div>
        </script>

        <script type="text/x-template" id="v-review-summary-template">
            <div v-if="isShowReviewSummary">
                @{{ reviewHtml }}
            </div>
        </script>

        <script type="module">
            app.component('v-review-summary', {
                template: '#v-review-summary-template',

                data() {
                    return  {
                        isShowReviewSummary: false,

                        reviewHtml: {},
                    }
                }
            })  

            app.component('v-payment-method', {
                template: '#v-payment-method-template',

                data() {
                    return {
                        paymentMethods: [],

                        isShowPaymentMethod: false,
                    }
                },

                methods: {
                    paymentMethodSelected(selectedPaymentMethod) {
                        this.$axios.post("{{ route('shop.checkout.save_payment') }}", {
                                'payment': selectedPaymentMethod
                            })
                            .then(response => {
                                this.$parent.$refs.vReview.reviewHtml = response.data.cart;

                                this.$parent.$refs.vReview.isShowReviewSummary = true;
                            })
                            .catch(error => {
                            });
                    }
                }
            })

            app.component('v-shipping-method', {
                template: '#v-shipping-method-template',

                data() {
                    return {
                        shippingMethods: [],

                        isShowShippingMethod: false
                    }
                },

                methods: {
                    save(selectedShippingMethod) {
                        this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;

                        this.$axios.post("{{ route('shop.checkout.save_shipping') }}", {
                                shipping_method: selectedShippingMethod,
                            })
                            .then(response => {
                                this.$parent.$refs.vPaymentMethod.paymentMethods = response.data.paymentMethods;
                                    
                                this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = true;
                            })
                            .catch(error => {})
                    },
                }
            })

            app.component('v-checkout', {
                template: '#v-checkout-template',

                data() {
                    return {}
                },
            });

            app.component('v-checkout-addresses', {
                template: '#v-checkout-addresses-template',

                data() {
                    return  {
                        address: {
                            billing: {
                                address1: [''],

                                isUseForShipping: true,

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
                        if (! this.address.billing.isSaveAsAddress) {
                            this.availableAddresses.push(this.address.billing);
                        } else {

                            this.assignAddress();

                            this.$axios.post('{{ route("shop.checkout.save_address") }}', this.address)
                                .then(response => {
                                    this.$parent.$refs.vShippingMethod.shippingMethods = response.data.data.shippingMethods;
                                    
                                    this.$parent.$refs.vShippingMethod.isShowShippingMethod = true
                                    
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
                        let selectedAddress = this.availableAddresses.find(data => data.id == address.id);
                        
                        this.address.billing.isSaveAsAddress = true;

                        this.resetPaymentAndShipping();

                        this.store();
                    }
                }
            })
        </script>
    @endPushOnce

</x-shop::layouts>
