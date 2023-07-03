<div v-if="! forms.billing.isUsedForShipping">
    <div 
        class="mt-[30px]"
        v-if="! forms.shipping.isNew"
    >
        <x-shop::accordion class="!border-b-[0px]">
            <x-slot:header class="suraj">
                <div class="flex justify-between items-center">
                    <h2 class="text-[26px] font-medium max-sm:text-[20px]">
                        @lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')
                    </h2>
                </div>
            </x-slot:header>
        
            <x-slot:content>
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, store)">
                        <div class="grid mt-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px]">
                            <div 
                                class="border border-[#e5e5e5] max-w-[414px] rounded-[12px] p-[0px] max-sm:flex-wrap relative select-none cursor-pointer"
                                v-for="(address, index) in addresses"
                            >
                                <v-field
                                    type="radio"
                                    name="shipping[address_id]"
                                    :id="'shipping_address_id_' + address.id"
                                    :value="address.id"
                                    :rules="{ required: ! isTempAddress }"
                                    label="Shipping Address"
                                    v-model="forms.shipping.address.address_id"
                                    class="hidden peer"
                                    @change="resetPaymentAndShippingMethod"
                                    :checked="address.isDefault"
                                />
                                
                                <label 
                                    class="icon-radio-unselect text-[24px] text-navyBlue absolute right-[20px] top-[20px] peer-checked:icon-radio-select cursor-pointer"
                                    :for="'shipping_address_id_' + address.id"
                                >
                                </label>

                                <label 
                                    :for="'shipping_address_id_' + address.id"
                                    class="block p-[20px] rounded-[12px] cursor-pointer"
                                >
                                    <div class="flex justify-between items-center">
                                        <p class="text-[16px] font-medium">
                                            @{{ address.first_name }} @{{ address.last_name }}
                                            <span v-if="address.company_name">(@{{ address.company_name }})</span>
                                        </p>
                                    </div>

                                    <p class="text-[#7D7D7D] mt-[25px] text-[14px] text-[14px]">
                                        <template v-if="typeof address.address1 === 'string'">
                                            @{{ address.address1 }}
                                        </template>
                                        
                                        <template v-else>
                                            @{{ address.address1.join(', ') }}
                                        </template>
                                        @{{ address.city }}, 
                                        @{{ address.state }}, @{{ address.country }}, 
                                        @{{ address.postcode }}
                                    </p>
                                </label>
                            </div>

                            <div 
                                class="flex justify-center items-center border border-[#e5e5e5] rounded-[12px] p-[20px] max-w-[414px] max-sm:flex-wrap"
                                @click="showNewShippingAddressForm"
                            >
                                <div class="flex gap-x-[10px] items-center cursor-pointer">
                                    <span class="icon-plus text-[30px] p-[10px] border border-black rounded-full"></span>
                                    <p class="text-[16px]">@lang('Add new address')</p>
                                </div>
                            </div>
                        </div>

                        <v-error-message
                            class="text-red-500 text-xs italic"
                            name="shipping[address_id]"
                        >
                        </v-error-message>


                        <template v-if="meta.valid">
                            <div v-if="! forms.billing.isNew && ! forms.shipping.isNew && ! forms.billing.isUsedForShipping">
                                <div class="flex justify-end mt-4 mb-4">
                                    <button
                                        class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                        @click="store"
                                    >
                                        @lang('Confirm')
                                    </button>
                                </div>
                            </div>
                        </template>
    
                        <template v-else>
                            <div v-if="! forms.billing.isNew && ! forms.shipping.isNew && ! forms.billing.isUsedForShipping">
                                <div class="flex justify-end mt-4 mb-4">
                                    <button
                                        type="submit"
                                        class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                    >
                                        @lang('Confirm')
                                    </button>
                                </div>
                            </div>
                        </template> 
                    </form>
                </x-shop::form>
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
                    <h2 class="text-[26px] font-medium max-sm:text-[20px]">
                        @lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')
                    </h2>
                </div>
            </x-slot:header>
        
            <x-slot:content>
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, handleShippingAddressForm)">
                        <div>
                            <a 
                                class="flex justify-end"
                                href="javascript:void(0)" 
                                v-if="addresses.length > 0"
                                @click="forms.shipping.isNew = ! forms.shipping.isNew"
                            >
                                <span class="icon-arrow-left text-[24px]"></span>

                                <span>@lang('shop::app.checkout.onepage.addresses.shipping.back')</span>
                            </a>
                        </div>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label>
                                @lang('shop::app.checkout.onepage.addresses.shipping.company-name')
                            </x-shop::form.control-group.label>
                        
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping[company_name]"
                                label="Company name"
                                placeholder="Company name"
                                v-model="forms.shipping.address.company_name"
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
                                    @lang('shop::app.checkout.onepage.addresses.shipping.first-name')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[first_name]"
                                    label="First name"
                                    rules="required"
                                    placeholder="First name"
                                    v-model="forms.shipping.address.first_name"
                                >
                                </x-shop::form.control-group.control>
                            
                                <x-shop::form.control-group.error
                                    control-name="shipping[first_name]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>
                            
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px]">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.last-name')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[last_name]"
                                    label="Last name"
                                    rules="required"
                                    placeholder="Last name"
                                    v-model="forms.shipping.address.last_name"
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
                                @lang('shop::app.checkout.onepage.addresses.shipping.email')
                            </x-shop::form.control-group.label>
                        
                            <x-shop::form.control-group.control
                                type="email"
                                name="shipping[email]"
                                rules="required|email"
                                label="Email"
                                placeholder="email@example.com"
                                v-model="forms.shipping.address.email"
                            >
                            </x-shop::form.control-group.control>
                        
                            <x-shop::form.control-group.error
                                control-name="shipping[email]"
                            >
                            </x-shop::form.control-group.error>
                        </x-shop::form.control-group>
                        
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-[0px]">
                                @lang('shop::app.checkout.onepage.addresses.shipping.street-address')
                            </x-shop::form.control-group.label>
                        
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping[address1][]"
                                class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                rules="required"
                                label="Street address"
                                placeholder="Street address"
                                v-model="forms.shipping.address.address1[0]"
                            >
                            </x-shop::form.control-group.control>

                            <x-shop::form.control-group.error
                                class="mb-2"
                                control-name="shipping[address1][]"
                            >
                            </x-shop::form.control-group.error>

                            @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                                @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="shipping[address1][{{ $i }}]"
                                        class="text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                        label="Street address"
                                        placeholder="Street address"
                                        v-model="forms.shipping.address.address1[{{$i}}]"
                                    >
                                    </x-shop::form.control-group.control>
                                @endfor
                            @endif
                        </x-shop::form.control-group>
                        
                        <div class="grid grid-cols-2 gap-x-[20px]">
                            <x-shop::form.control-group
                                class="!mb-4"
                            >
                                <x-shop::form.control-group.label class="!mt-[0px]">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.country')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="select"
                                    name="shipping[country]"
                                    class="!text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline mb-2"
                                    rules="required"
                                    label="Country"
                                    placeholder="Country"
                                    v-model="forms.shipping.address.country"
                                >
                                    <option value="">@lang('shop::app.checkout.onepage.addresses.shipping.select-country')</option>
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
                                    @lang('shop::app.checkout.onepage.addresses.shipping.state')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[state]"
                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                    rules="required"
                                    label="State"
                                    placeholder="State"
                                    v-model="forms.shipping.address.state"
                                    v-if="! haveStates('shipping')"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.control
                                    type="select"
                                    name="shipping[state]"
                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline mb-2"
                                    rules="required"
                                    label="State"
                                    placeholder="State"
                                    v-model="forms.shipping.address.state"
                                    v-if="haveStates('shipping')"
                                >
                                    <option value="">@lang('shop::app.checkout.onepage.addresses.shipping.select-state')</option>

                                    <option 
                                        v-for='(state, index) in states[forms.shipping.address.country]' 
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
                                    @lang('shop::app.checkout.onepage.addresses.shipping.city')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[city]"
                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                    rules="required"
                                    label="City"
                                    placeholder="City"
                                    v-model="forms.shipping.address.city"
                                >
                                </x-shop::form.control-group.control>
                            
                                <x-shop::form.control-group.error
                                    control-name="shipping[city]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>
                            
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px]">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.postcode')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[postcode]"
                                    class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                    rules="required"
                                    label="Zip/Postcode"
                                    placeholder="Zip/Postcode"
                                    v-model="forms.shipping.address.postcode"
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
                                @lang('shop::app.checkout.onepage.addresses.shipping.telephone')
                            </x-shop::form.control-group.label>
                        
                            
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping[phone]"
                                class="text-[14px] bg-white border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline"
                                rules="required|numeric"
                                label="Telephone"
                                placeholder="Telephone"
                                v-model="forms.shipping.address.phone"
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
                                    <div class="select-none flex gap-x-[15px]">
                                        <input 
                                            type="checkbox"
                                            name="shipping[is_save_as_address]"
                                            id="shipping[is_save_as_address]"
                                            class="hidden peer"
                                            v-model="forms.shipping.address.isSaved"
                                        >

                                        <label
                                            class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                            for="shipping[is_save_as_address]"
                                        >
                                        </label>

                                        <label for="shipping[is_save_as_address]">
                                            @lang('shop::app.checkout.onepage.addresses.shipping.save-address')
                                        </label>
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
                                @lang('shop::app.checkout.onepage.addresses.shipping.confirm')
                            </button>
                        </div>
                    </form>
                </x-shop::form>
            </x-slot:content>
        </x-shop::accordion>
    </div>
</div>