{!! view_render_event('bagisto.shop.checkout.addresses.before') !!}

<v-checkout-addresses ref="vCheckoutAddress"></v-checkout-addresses>

{!! view_render_event('bagisto.shop.checkout.addresses.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-checkout-addresses-template">
        <template v-if="isAddressLoading">
            <x-shop::shimmer.checkout.onepage.address></x-shop::shimmer.checkout.onepage.address>
        </template>
        
        <template v-else>
            <div class="mt-[30px]">
                <div v-if="! address.billing.isNew">
                    <x-shop::accordion class="!border-b-[0px]">
                        <x-slot:header >
                            <div class="flex justify-between items-center">
                                <h2 class="text-[26px] font-medium max-sm:text-[20px]">
                                    @lang('1. Billing Address')
                                </h2>
                            </div>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <div class="grid mt-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px]">
                                <div 
                                    class="border border-[#e5e5e5] max-w-[414px] rounded-[12px] p-[0px] max-sm:flex-wrap relative select-none cursor-pointer"
                                    v-for="(addresses, index) in addresses"
                                    @change="storeBilliingAddress(addresses)"
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
                                    >
                                    </label>

                                    <label 
                                        :for="'billing_address_id_' + addresses.id"
                                        class="block p-[20px] rounded-[12px] cursor-pointer"
                                    >
                                        <div class="flex justify-between items-center">
                                            <p class="text-[16px] font-medium">
                                                @{{ addresses.first_name }} @{{ addresses.last_name }}
                                                <span v-if="addresses.company_name">(@{{ addresses.company_name }})</span>
                                            </p>
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
                                    class="flex justify-center items-center border border-[#e5e5e5] rounded-[12px] p-[20px] max-w-[414px] max-sm:flex-wrap"
                                    @click="addNewBillingAddress"
                                >
                                    <div class="flex gap-x-[10px] items-center cursor-pointer">
                                        <span class="icon-plus text-[30px] p-[10px] border border-black rounded-full"></span>
                                        <p class="text-[16px]">@lang('Add new address')</p>
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
                                <h2 class="text-[26px] font-medium max-sm:text-[20px]">
                                    @lang('Billing Address')
                                </h2>
                            </div>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <div>
                                <a 
                                    class="flex justify-end"
                                    href="javascript:void(0)" 
                                    v-if="addresses.length > 0"
                                    @click="address.billing.isNew = ! address.billing.isNew"
                                >
                                    <span class="icon-arrow-left text-[24px]"></span>

                                    <span>@lang('Back')</span>
                                </a>
                            </div>

                            <x-shop::form
                                v-slot="{ meta, errors, handleSubmit }"
                                as="div"
                            >
                                <form @submit="handleSubmit($event, handleBillingAddress)">
                                    <x-shop::form.control-group>
                                        <x-shop::form.control-group.label>
                                            @lang('Company name')
                                        </x-shop::form.control-group.label>
                            
    
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
                                            @auth('customer')
                                                <div 
                                                    class="select-none flex gap-x-[15px]"
                                                    v-if="address.billing.canUseForShipping"
                                                >
                                                    <input 
                                                        type="checkbox"
                                                        name="billing[is_save_as_address]"
                                                        id="billing[is_save_as_address]"
                                                        class="hidden peer"
                                                        v-model="address.billing.isSaved"
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
    
                                    <div class="flex justify-end mt-4 mb-4">
                                        <button
                                            type="submit"
                                            class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                        >
                                            @lang('Confirm')
                                        </button>
                                    </div>
                                </form>
                            </x-shop::form>
                        </x-slot:content>
                    </x-shop::accordion>
                </div>

                <div 
                    class="mt-[30px]"
                    v-if="! address.shipping.isNew"
                >
                    <x-shop::accordion class="!border-b-[0px]">
                        <x-slot:header class="suraj">
                            <div class="flex justify-between items-center">
                                <h2 class="text-[26px] font-medium max-sm:text-[20px]">
                                    @lang('Shipping Address')
                                </h2>
                            </div>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <div class="grid mt-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px]">
                                <div 
                                    class="border border-[#e5e5e5] max-w-[414px] rounded-[12px] p-[0px] max-sm:flex-wrap relative select-none cursor-pointer"
                                    v-for="(addresses, index) in addresses"
                                >
                                    <v-field
                                        type="radio"
                                        name="shipping[address_id]"
                                        :id="'shipping_address_id_' + addresses.id"
                                        :value="addresses.id"
                                        rules="required"
                                        v-model="address.shipping.address_id"
                                        class="hidden peer"
                                    >
                                    </v-field>

                                    <label 
                                        class="icon-radio-unselect text-[24px] text-navyBlue absolute right-[20px] top-[20px] peer-checked:icon-radio-select cursor-pointer"
                                        :for="'shipping_address_id_' + addresses.id"
                                    >
                                    </label>

                                    <label 
                                        :for="'shipping_address_id_' + addresses.id"
                                        class="block p-[20px] rounded-[12px] cursor-pointer"
                                    >
                                        <div class="flex justify-between items-center">
                                            <p class="text-[16px] font-medium">
                                                @{{ addresses.first_name }} @{{ addresses.last_name }}
                                                <span v-if="addresses.company_name">(@{{ addresses.company_name }})</span>
                                            </p>
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
                                    class="flex justify-center items-center border border-[#e5e5e5] rounded-[12px] p-[20px] max-w-[414px] max-sm:flex-wrap"
                                    @click="addNewshippingAddress"
                                >
                                    <div class="flex gap-x-[10px] items-center cursor-pointer">
                                        <span class="icon-plus text-[30px] p-[10px] border border-black rounded-full"></span>
                                        <p class="text-[16px]">@lang('Add new address')</p>
                                    </div>
                                </div>
                            </div>
                        </x-slot:content>
                    </x-shop::accordion>
                </div>

                <div 
                    class="mt-[30px]"
                    v-else
                >
                    <x-shop::accordion>
                        <x-slot:header>
                            <div class="flex justify-between items-center">
                                <h2 class="text-[26px] font-medium max-sm:text-[20px]">@lang('Shipping Address')</h2>
                            </div>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <x-shop::form
                                v-slot="{ meta, errors, handleSubmit }"
                                as="div"
                            >
                                <form @submit="handleSubmit($event, handleShippingAddress)">
                                    <div>
                                        <a 
                                            class="flex justify-end"
                                            href="javascript:void(0)" 
                                            v-if="addresses.length > 0"
                                            @click="address.shipping.isNew = ! address.shipping.isNew"
                                        >
                                            <span class="icon-arrow-left text-[24px]"></span>
        
                                            <span>@lang('Back')</span>
                                        </a>
                                    </div>
        
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

                                    <div class="mt-[30px] pb-[15px]">
                                        <div class="grid gap-[10px]">
                                            @auth('customer')
                                                <div 
                                                    class="select-none flex gap-x-[15px]"
                                                >
                                                    <input 
                                                        type="checkbox"
                                                        name="shipping[is_save_as_address]"
                                                        id="shipping[is_save_as_address]"
                                                        class="hidden peer"
                                                        v-model="address.shipping.isSaved"
                                                    >
    
                                                    <label
                                                        class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white  cursor-pointer"
                                                        for="shipping[is_save_as_address]"
                                                    ></label>
    
                                                    <label for="shipping[is_save_as_address]">@lang('Save this address')</label>
                                                </div>
                                            @endauth
                                        </div>
                                    </div>

                                    <div 
                                        class="flex justify-end mt-4 mb-4"
                                    >
                                        <button
                                            type="submit"
                                            class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                        >
                                            @lang('Confirm')
                                        </button>
                                    </div>
                                </form>
                            </x-shop::form>
                        </x-slot:content>
                    </x-shop::accordion>
                </div>


                

                {{-- <div 
                    class="mt-[30px]"
                    v-if="address.shipping.isNew"
                >
                    Show Card
                </div>

                <div
                    class="mt-[30px]"
                    v-if="address.shipping.isNew"
                >
                    Show form
                </div> --}}

                {{-- <div>
                    <div class="select-none mt-[20px] text-[14px] text-[#7D7D7D] flex gap-x-[15px]">
                        <input
                            type="checkbox"
                            id="canUseForShipping"
                            name="is_use_for_shipping"
                            class="hidden peer"
                            v-model="address.sameAsbillingAddress"
                        />
                
                        <label 
                            class="icon-uncheck text-[20px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white cursor-pointer"
                            for="canUseForShipping"
                        >
                        </label>
                        
                        <label 
                            for="canUseForShipping"
                            class="cursor-pointer"
                        >
                            @lang('address is the same as my billing address')
                        </label>
                    </div>
                
                    <div 
                        class="flex justify-end mt-4 mb-4"
                    >
                        <button
                            class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                            @click="store"
                        >
                            @lang('Confirm')
                        </button>
                    </div>
                </div> --}}
            </div>
        </template>
    </script>

    <script type="module">
         app.component('v-checkout-addresses', {
            template: '#v-checkout-addresses-template',

            data() {
                return  {
                    address: {
                        billing: {
                            address1: [''],

                            isNew: false,

                            isSaved: false,

                            canUseForShipping: true,
                        },

                        shipping: {
                            address1: [''],

                            isSaved: false,

                            isNew: false,
                        },
                    },

                    addresses: [],

                    countries: [],

                    states: [],

                    isAddressLoading: false,
                }
            }, 
            
            created() {
                this.getCustomerAddresses();

                this.getCountryStates();

                this.getCountries();
            },

            methods: {
                resetAddressForm() {
                    this.address = {
                        billing: this.resetBillingAddressForm(),

                        shipping: this.resetShippingAddressForm(),
                    };
                },

                resetBillingAddressForm() {
                    return {
                        address1: [''],

                        isNew: false,

                        isSaved: false,

                        canUseForShipping: true,
                    };
                },

                resetShippingAddressForm() {
                    return {
                        address1: [''],

                        isSaved: false,

                        isNew: false,
                    };
                },

                getCustomerAddresses() {
                    this.$axios.get("{{ route('api.shop.customers.account.addresses.index') }}")
                        .then(response => {
                            this.addresses = response.data.data.map(address => {
                                return {
                                    ...address,
                                    isNew: false,
                                    isSaved: true,
                                };
                            });
                        })
                        .catch(function (error) {});
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

                handleBillingAddress() {
                    if (this.address.billing.isNew && ! this.address.billing.isSaved) {
                        this.address.billing.isNew = false;
                        
                        this.address.billing.address1 = this.address.billing.address1[0];

                        this.addresses.push({
                            ...this.address.billing,
                            isNew: true,
                            isSaved: false,
                        });
                        
                        this.resetBillingAddressForm();
                    } else if (this.address.billing.isNew && this.address.billing.isSaved) {
                        this.$axios.post('{{ route("api.shop.customers.account.addresses.store") }}', this.address.billing)
                            .then(response => {
                                // this.$parent.$refs.vShippingMethod.shippingMethods = response.data.data.shippingMethods;

                                // this.$parent.$refs.vShippingMethod.isShippingLoading = false;
                                
                                // this.$parent.$refs.vShippingMethod.isShowShippingMethod = true;
                                this.address.billing.isNew = false;

                                this.resetBillingAddressForm();
                                
                                this.getCustomerAddresses();
                            })
                            .catch(error => {                 
                                console.log(error);
                            });
                    }
                },

                handleShippingAddress() {
                    if (this.address.shipping.isNew && ! this.address.shipping.isSaved) {
                        this.address.shipping.isNew = false;
                        
                        this.address.shipping.address1 = this.address.shipping.address1[0];

                        this.addresses.push({
                            ...this.address.shipping,
                            isNew: true,
                            isSaved: false,
                        });
                        
                        this.resetShippingAddressForm();
                    } else if (this.address.shipping.isNew && this.address.shipping.isSaved) {
                        this.$axios.post('{{ route("api.shop.customers.account.addresses.store") }}', this.address.shipping)
                            .then(response => {
                                // this.$parent.$refs.vShippingMethod.shippingMethods = response.data.data.shippingMethods;

                                // this.$parent.$refs.vShippingMethod.isShippingLoading = false;
                                
                                // this.$parent.$refs.vShippingMethod.isShowShippingMethod = true;
                                this.address.shipping.isNew = false;

                                this.resetShippingAddressForm();
                                
                                this.getCustomerAddresses();
                            })
                            .catch(error => {                 
                                console.log(error);
                            });
                    }
                },

                store() {
                    if (this.address.billing.isNew && ! this.address.billing.isSaved) {
                        this.address.billing.isNew = false;

                        this.tempBillingAddress = this.address.billing;
                        
                        this.address.billing.address1 = this.address.billing.address1[0];

                        this.addresses.push(this.address.billing);
                        
                        this.resetAddressForm();
                    } else {

                        if (! this.address.billing.isNew) {
                            this.address.billing = this.tempBillingAddress

                            this.address.billing.isSaved = true;
                        }

                        this.$axios.post('{{ route("shop.checkout.onepage.addresses.store") }}', this.address)
                            .then(response => {
                                this.$parent.$refs.vShippingMethod.shippingMethods = response.data.data.shippingMethods;

                                this.$parent.$refs.vShippingMethod.isShippingLoading = false;
                                
                                this.$parent.$refs.vShippingMethod.isShowShippingMethod = true;
                                
                                this.getCustomerAddresses();
                            })
                            .catch(error => {                 
                                console.log(error);
                            })
                    }
                },

                storeBilliingAddress(selectedBillingAddress) {},

                storeShippingAddress(selectedShippingAddress) {},

                isHaveStates(addressType) {
                    if (
                        this.states[this.address[addressType].country]
                        && this.states[this.address[addressType].country].length
                    ) {
                        return true;
                    }

                    return false;
                },
             
                addNewBillingAddress() {
                    this.resetBillingAddressForm();

                    this.address.billing.isNew = true;

                    // this.$parent.$refs.vShippingMethod.isShowShippingMethod = false;

                    // this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;
                },

                addNewshippingAddress() {
                    this.resetShippingAddressForm();

                    this.address.shipping.isNew = true;

                    // this.$parent.$refs.vShippingMethod.isShowShippingMethod = false;

                    // this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;
                },
            }
        });
    </script>
@endPushOnce