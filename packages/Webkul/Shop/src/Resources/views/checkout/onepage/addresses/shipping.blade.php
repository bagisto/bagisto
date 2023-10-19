<div v-if="! forms.billing.isUsedForShipping">
    <div 
        class="mt-[30px]"
        v-if="! forms.shipping.isNew"
    >

        {!! view_render_event('bagisto.shop.checkout.onepage.shipping.accordion.before') !!}

        <x-shop::accordion class="!border-b-[0px]">
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
                    <form @submit="handleSubmit($event, store)">
                        <div class="grid grid-cols-2 mt-[30px] gap-[20px] max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px]">
                            <div 
                                class="relative max-w-[414px] p-[0px] border border-[#e5e5e5] rounded-[12px] max-sm:flex-wrap select-none cursor-pointer"
                                v-for="(address, index) in addresses"
                            >
                                <v-field
                                    type="radio"
                                    name="shipping[address_id]"
                                    :value="address.id"
                                    :id="'shipping_address_id_' + address.id"
                                    class="hidden peer"
                                    :rules="{ required: ! isTempAddress }"
                                    label="@lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')"
                                    v-model="forms.shipping.address.address_id"
                                    :checked="address.isDefault"
                                    @change="resetPaymentAndShippingMethod"
                                />
                                
                                <label 
                                    class="icon-radio-unselect absolute ltr:right-[20px] rtl:left-[20px] top-[20px] text-[24px] text-navyBlue peer-checked:icon-radio-select cursor-pointer"
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

                                    <p class="mt-[25px] text-[#6E6E6E] text-[14px]">
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
                                @click="showNewShippingAddressForm"
                            >
                                <div class="flex gap-x-[10px] items-center cursor-pointer">
                                    <span class="icon-plus p-[10px] text-[30px]  border border-black rounded-full"></span>

                                    <p class="text-[16px]">@lang('shop::app.checkout.onepage.addresses.shipping.add-new-address')</p>
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
                                        class="block py-[11px] px-[43px] bg-navyBlue text-white text-base w-max font-medium rounded-[18px] text-center cursor-pointer"
                                        @click="store"
                                    >
                                        @lang('shop::app.checkout.onepage.addresses.shipping.confirm')
                                    </button>
                                </div>
                            </div>
                        </template>
    
                        <template v-else>
                            <div v-if="! forms.billing.isNew && ! forms.shipping.isNew && ! forms.billing.isUsedForShipping">
                                <div class="flex justify-end mt-4 mb-4">
                                    <button
                                        type="submit"
                                        class="block w-max py-[11px] px-[43px] bg-navyBlue text-white text-base font-medium rounded-[18px] text-center cursor-pointer"
                                    >
                                        @lang('shop::app.checkout.onepage.addresses.shipping.confirm')
                                    </button>
                                </div>
                            </div>
                        </template> 
                    </form>
                </x-shop::form>
            </x-slot:content>
        </x-shop::accordion>

        {!! view_render_event('bagisto.shop.checkout.onepage.shipping.accordion.after') !!}

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

                {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.before') !!}

                {{-- Shipping address form --}}
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

                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.control.before') !!}

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label>
                                @lang('shop::app.checkout.onepage.addresses.shipping.company-name')
                            </x-shop::form.control-group.label>
                        
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping[company_name]"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.company-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.company-name')"
                                v-model="forms.shipping.address.company_name"
                            >
                            </x-shop::form.control-group.control>
                        
                            <x-shop::form.control-group.error
                                control-name="shipping[company_name]"
                            >
                            </x-shop::form.control-group.error>
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.company_name.after') !!}

                        <div class="grid grid-cols-2 gap-x-[20px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.first-name')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[first_name]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.first-name')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.first-name')"
                                    v-model="forms.shipping.address.first_name"
                                >
                                </x-shop::form.control-group.control>
                            
                                <x-shop::form.control-group.error
                                    control-name="shipping[first_name]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.first_name.after') !!}
                            
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.last-name')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[last_name]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.last-name')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.last-name')"
                                    v-model="forms.shipping.address.last_name"
                                >
                                </x-shop::form.control-group.control>
                            
                                <x-shop::form.control-group.error
                                    control-name="shipping[last_name]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.last_name.after') !!}

                        </div>
                        
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-[0px] required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.email')
                            </x-shop::form.control-group.label>
                        
                            <x-shop::form.control-group.control
                                type="email"
                                name="shipping[email]"
                                rules="required|email"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.email')"
                                placeholder="email@example.com"
                                v-model="forms.shipping.address.email"
                            >
                            </x-shop::form.control-group.control>
                        
                            <x-shop::form.control-group.error
                                control-name="shipping[email]"
                            >
                            </x-shop::form.control-group.error>
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.email.after') !!}
                        
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-[0px] required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.street-address')
                            </x-shop::form.control-group.label>
                        
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping[address1][]"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.street-address')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.street-address')"
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
                                        :label="trans('shop::app.checkout.onepage.addresses.shipping.street-address')"
                                        :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.street-address')"
                                        v-model="forms.shipping.address.address1[{{$i}}]"
                                    >
                                    </x-shop::form.control-group.control>
                                @endfor
                            @endif
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.street_address.after') !!}
                        
                        <div class="grid grid-cols-2 gap-x-[20px]">
                            <x-shop::form.control-group
                                class="!mb-4"
                            >
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.country')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="select"
                                    name="shipping[country]"
                                    class="py-2 mb-2"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.country')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.country')"
                                    v-model="forms.shipping.address.country"
                                >
                                    <option value="">@lang('shop::app.checkout.onepage.addresses.shipping.select-country')</option>

                                    <option
                                        v-for="country in countries"
                                        :value="country.code"
                                        v-text="country.name"
                                    >
                                    </option>
                                </x-shop::form.control-group.control>
                            
                                <x-shop::form.control-group.error
                                    control-name="shipping[country]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.country.after') !!}
                            
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.state')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[state]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                    v-model="forms.shipping.address.state"
                                    v-if="! haveStates('shipping')"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.control
                                    type="select"
                                    name="shipping[state]"
                                    class="py-2 mb-2"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.state')"
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

                            {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.state.after') !!}

                        </div>

                        <div class="grid grid-cols-2 gap-x-[20px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.city')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[city]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.city')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.city')"
                                    v-model="forms.shipping.address.city"
                                >
                                </x-shop::form.control-group.control>
                            
                                <x-shop::form.control-group.error
                                    control-name="shipping[city]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.city.after') !!}
                            
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-[0px] required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.postcode')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[postcode]"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.postcode')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.postcode')"
                                    v-model="forms.shipping.address.postcode"
                                >
                                </x-shop::form.control-group.control>
                            
                                <x-shop::form.control-group.error
                                    control-name="shipping[postcode]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.postcode.after') !!}

                        </div>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-[0px] required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.telephone')
                            </x-shop::form.control-group.label>
                            
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping[phone]"
                                rules="required|numeric"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.telephone')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.telephone')"
                                v-model="forms.shipping.address.phone"
                            >
                            </x-shop::form.control-group.control>
                        
                            <x-shop::form.control-group.error
                                control-name="shipping[phone]"
                            >
                            </x-shop::form.control-group.error>
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.phone.after') !!}

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
                                class="block w-max px-[43px] py-[11px] bg-navyBlue rounded-[18px] text-white text-base font-medium text-center cursor-pointer"
                            >
                                @lang('shop::app.checkout.onepage.addresses.shipping.confirm')
                            </button>
                        </div>
                    </form>

                    {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.control.after') !!}

                </x-shop::form>

                {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.after') !!}

            </x-slot:content>
        </x-shop::accordion>
    </div>
</div>