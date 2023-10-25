<div>
    <div v-if="! forms.billing.isNew">
        <x-shop::accordion class="!border-b-[0px]">
            <x-slot:header >
                <div class="flex justify-between items-center">
                    <h2 class="text-[26px] font-medium max-sm:text-[20px]">
                        @lang('shop::app.checkout.onepage.addresses.billing.billing-address')
                    </h2>
                </div>
            </x-slot:header>
        
            <x-slot:content>
                <v-form 
                    @submit.preventDefault 
                    v-slot="{ meta, errors }"
                >
                    <div class="grid gap-[20px] grid-cols-2 mt-[30px] max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px]">
                        <div 
                            class="relative max-w-[414px] p-[0px] border border-[#e5e5e5] rounded-[12px] max-sm:flex-wrap select-none cursor-pointer"
                            v-for="(address, index) in addresses"
                        >
                            <v-field
                                type="radio"
                                name="billing[address_id]"
                                :value="address.id"
                                :id="'billing_address_id_' + address.id"
                                class="hidden peer"
                                :rules="{ required: ! isTempAddress }"
                                label="@lang('shop::app.checkout.onepage.addresses.billing.billing-address')"
                                v-model="forms.billing.address.address_id"
                                :checked="address.isDefault"
                                @change="resetPaymentAndShippingMethod"
                            />

                            <label 
                                class="icon-radio-unselect absolute ltr:right-[20px] rtl:left-[20px] top-[20px] text-[24px] text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                :for="'billing_address_id_' + address.id"
                            >
                            </label>

                            <label 
                                :for="'billing_address_id_' + address.id"
                                class="block p-[20px] rounded-[12px] cursor-pointer"
                            >
                                <div class="flex justify-between items-center">
                                    <p class="text-[16px] font-medium">
                                        @{{ address.first_name }} @{{ address.last_name }}
                                        
                                        <span v-if="address.company_name">(@{{ address.company_name }})</span>
                                    </p>
                                </div>

                                <p class="mt-[25px] text-[14px] text-[#6E6E6E]">
                                    <template v-if="typeof address.address1 === 'string'">
                                        @{{ address.address1 }},
                                    </template>

                                    <template v-else>
                                        @{{ address.address1.join(', ') }}
                                    </template>

                                    <template v-if="address.address2">
                                        @{{ address.address2 }},
                                    </template>

                                    @{{ address.city }}, 
                                    @{{ address.state }}, @{{ address.country }}, 
                                    @{{ address.postcode }}
                                </p>
                            </label>
                        </div>

                        <div 
                            class="flex justify-center items-center max-w-[414px] p-[20px] border border-[#e5e5e5] rounded-[12px] max-sm:flex-wrap"
                            @click="showNewBillingAddressForm"
                        >
                            <div class="flex gap-x-[10px] items-center cursor-pointer">
                                <span class="icon-plus p-[10px] border border-black rounded-full text-[30px]"></span>

                                <p class="text-[16px]">@lang('shop::app.checkout.onepage.addresses.billing.add-new-address')</p>
                            </div>
                        </div>
                    </div>

                    <v-error-message
                        class="text-red-500 text-xs italic"
                        name="billing[address_id]"
                    >
                    </v-error-message>

                    <div class="flex gap-x-[15px] mt-[20px] text-[14px] text-[#6E6E6E] select-none">
                        <input
                            type="checkbox"
                            id="isUsedForShipping"
                            name="is_use_for_shipping"
                            class="hidden peer"
                            v-model="forms.billing.isUsedForShipping"
                        />
                
                        <label 
                            class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                            for="isUsedForShipping"
                        >
                        </label>
                        
                        <label 
                            for="isUsedForShipping"
                            class="cursor-pointer"
                        >
                            @lang('shop::app.checkout.onepage.addresses.billing.same-billing-address')
                        </label>
                    </div>


                    <template v-if="meta.valid">
                        <div v-if="! forms.billing.isNew && ! forms.shipping.isNew && forms.billing.isUsedForShipping">
                            <div class="flex justify-end mt-4 mb-4">
                                <button
                                    class="block py-[11px] px-[43px] bg-navyBlue rounded-[18px] text-white text-base w-max font-medium text-center cursor-pointer"
                                    @click="store"
                                >
                                    @lang('shop::app.checkout.onepage.addresses.billing.confirm')
                                </button>
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <div v-if="! forms.billing.isNew && ! forms.shipping.isNew && forms.billing.isUsedForShipping">
                            <div class="flex justify-end mt-4 mb-4">
                                <button
                                    type="submit"
                                    class="blockpy-[11px] px-[43px] bg-navyBlue rounded-[18px] text-white text-base w-max font-medium text-center cursor-pointer"
                                >
                                    @lang('shop::app.checkout.onepage.addresses.billing.confirm')
                                </button>
                            </div>
                        </div>
                    </template> 
                </v-form>
            </x-slot:content>
        </x-shop::accordion>
    </div>

    <div v-else>
        <x-shop::accordion>
            <x-slot:header>
                <div class="flex justify-between items-center">
                    <h2 class="text-[26px] font-medium max-sm:text-[20px]">
                        @lang('shop::app.checkout.onepage.addresses.billing.billing-address')
                    </h2>
                </div>
            </x-slot:header>
        
            <x-slot:content>
                <div>
                    <a 
                        class="flex justify-end"
                        href="javascript:void(0)" 
                        v-if="addresses.length > 0"
                        @click="forms.billing.isNew = ! forms.billing.isNew"
                    >
                        <span class="icon-arrow-left text-[24px]"></span>

                        <span>@lang('shop::app.checkout.onepage.addresses.billing.back')</span>
                    </a>
                </div>

                {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.before') !!}

                {{-- Billing address form --}}
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, handleBillingAddressForm)">
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label>
                                @lang('shop::app.checkout.onepage.addresses.billing.company-name')
                            </x-shop::form.control-group.label>
                
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing[company_name]"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.company-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.company-name')"
                                v-model="forms.billing.address.company_name"
                            >
                            </x-shop::form.control-group.control>
    
                            <x-shop::form.control-group.error
                                control-name="billing[company_name]"
                            >
                            </x-shop::form.control-group.error>
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.company_name.after') !!}

                        <div class="grid grid-cols-2 gap-x-[20px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.billing.first-name')
                                </x-shop::form.control-group.label>
        
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="billing[first_name]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.first-name')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.first-name')"
                                    v-model="forms.billing.address.first_name"
                                >
                                </x-shop::form.control-group.control>
        
                                <x-shop::form.control-group.error
                                    control-name="billing[first_name]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.first_name.after') !!}


                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.billing.last-name')
                                </x-shop::form.control-group.label>
        
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="billing[last_name]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.last-name')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.last-name')"
                                    v-model="forms.billing.address.last_name"
                                >
                                </x-shop::form.control-group.control>
        
                                <x-shop::form.control-group.error
                                    control-name="billing[last_name]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.last_name.after') !!}
                        </div>
    
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-[0px] required">
                                @lang('shop::app.checkout.onepage.addresses.billing.email')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="email"
                                name="billing[email]"
                                rules="required|email"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.email')"
                                placeholder="email@example.com"
                                v-model="forms.billing.address.email"
                            >
                            </x-shop::form.control-group.control>
    
                            <x-shop::form.control-group.error
                                control-name="billing[email]"
                            >
                            </x-shop::form.control-group.error>
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.email.after') !!}
    
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-[0px] required">
                                @lang('shop::app.checkout.onepage.addresses.billing.street-address')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing[address1][]"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                                v-model="forms.billing.address.address1[0]"
                            >
                            </x-shop::form.control-group.control>

                            <x-shop::form.control-group.error
                                class="mb-2"
                                control-name="billing[address1][]"
                            >
                            </x-shop::form.control-group.error>

                            @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                                @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="billing[address1][{{ $i }}]"
                                        :label="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                                        :placeholder="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                                        v-model="forms.billing.address.address1[{{$i}}]"
                                    >
                                    </x-shop::form.control-group.control>
                                @endfor
                            @endif
                        </x-shop::form.control-group>
    
                        {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.address1.after') !!}

                        <div class="grid grid-cols-2 gap-x-[20px]">
                            <x-shop::form.control-group
                                class="!mb-4"
                            >
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.billing.country')
                                </x-shop::form.control-group.label>
        
                                <x-shop::form.control-group.control
                                    type="select"
                                    name="billing[country]"
                                    class="py-2 mb-2"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.country')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.country')"
                                    v-model="forms.billing.address.country"
                                >
                                    <option
                                        v-for="country in countries"
                                        :value="country.code"
                                        v-text="country.name"
                                    >
                                    </option>
                                </x-shop::form.control-group.control>
        
                                <x-shop::form.control-group.error
                                    control-name="billing[country]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.country.after') !!}
    
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.billing.state')
                                </x-shop::form.control-group.label>
        
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="billing[state]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                    v-model="forms.billing.address.state"
                                    v-if="! haveStates('billing')"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.control
                                    type="select"
                                    name="billing[state]"
                                    class="py-2 mb-2"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                    v-model="forms.billing.address.state"
                                    v-if="haveStates('billing')"
                                >
                                    <option value="">@lang('shop::app.checkout.onepage.addresses.billing.select-state')</option>

                                    <option 
                                        v-for='(state, index) in states[forms.billing.address.country]' 
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

                            {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.state.after') !!}

                        </div>
    
                        <div class="grid grid-cols-2 gap-x-[20px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.billing.city')
                                </x-shop::form.control-group.label>
    
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="billing[city]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.city')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.city')"
                                    v-model="forms.billing.address.city"
                                >
                                </x-shop::form.control-group.control>
    
                                <x-shop::form.control-group.error
                                    control-name="billing[city]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.city.after') !!}
        
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.billing.postcode')
                                </x-shop::form.control-group.label>
        
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="billing[postcode]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.postcode')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.postcode')"
                                    v-model="forms.billing.address.postcode"
                                >
                                </x-shop::form.control-group.control>
        
                                <x-shop::form.control-group.error
                                    control-name="billing[postcode]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.postcode.after') !!}

                        </div>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-[0px] required">
                                @lang('shop::app.checkout.onepage.addresses.billing.telephone')
                            </x-shop::form.control-group.label>
                            
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing[phone]"
                                rules="required|numeric"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.telephone')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.telephone')"
                                v-model="forms.billing.address.phone"
                            >
                            </x-shop::form.control-group.control>
    
                            <x-shop::form.control-group.error
                                control-name="billing[phone]"
                            >
                            </x-shop::form.control-group.error>
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.phone.after') !!}

                        <div class="mt-[30px] pb-[15px]">
                            <div class="grid gap-[10px]">
                                @auth('customer')
                                    <div class="select-none flex gap-x-[15px]">
                                        <input 
                                            type="checkbox"
                                            name="billing[default_address]"
                                            id="billing[default_address]"
                                            class="hidden peer"
                                            v-model="forms.billing.address.isSaved"
                                        >

                                        <label
                                            class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                            for="billing[default_address]"
                                        >
                                        </label>

                                        <label for="billing[default_address]">
                                            @lang('shop::app.checkout.onepage.addresses.billing.save-address')
                                        </label>
                                    </div>
                                @endauth
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 mb-4">
                            <button
                                type="submit"
                                class="block py-[11px] px-[43px] bg-navyBlue text-white text-base w-max font-medium rounded-[18px] text-center cursor-pointer"
                            >
                                @lang('shop::app.checkout.onepage.addresses.billing.confirm')
                            </button>
                        </div>
                    </form>
                </x-shop::form>

                {!! view_render_event('bagisto.shop.checkout.onepage.billing_address.after') !!}

            </x-slot:content>
        </x-shop::accordion>
    </div>
</div>
