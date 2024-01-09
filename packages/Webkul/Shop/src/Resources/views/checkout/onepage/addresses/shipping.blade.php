<div v-if="! forms.billing.isUsedForShipping">
    <template v-if="! forms.shipping.isNew">
        {!! view_render_event('bagisto.shop.checkout.onepage.shipping.accordion.before') !!}

        <x-shop::accordion class="!border-b-0 mt-8">
            <x-slot:header class="!p-0">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-medium max-sm:text-xl">
                        @lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')
                    </h2>
                </div>
            </x-slot:header>
        
            <x-slot:content class="!p-0 mt-8">
                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping.before') !!}

                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, store)">
                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping.before') !!}

                        <div class="grid grid-cols-2 gap-5 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                            <div 
                                class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                                v-for="(address, index) in addresses.shipping"
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
                                    class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                    :for="'shipping_address_id_' + address.id"
                                >
                                </label>

                                <label 
                                    :for="'shipping_address_id_' + address.id"
                                    class="block p-5 rounded-xl cursor-pointer"
                                >
                                    <div class="flex justify-between items-center">
                                        <p class="text-base font-medium">
                                            @{{ address.first_name }} @{{ address.last_name }}

                                            <span v-if="address.company_name">(@{{ address.company_name }})</span>
                                        </p>
                                    </div>

                                    <p class="mt-6 text-[#6E6E6E] text-sm">
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
                                class="flex justify-center items-center max-w-[414px] p-5 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap cursor-pointer"
                                @click="showNewShippingAddressForm"
                            >
                                <div
                                    class="flex gap-x-2.5 items-center"
                                    role="button"
                                    tabindex="0"
                                >
                                    <span
                                        class="icon-plus p-2.5 text-3xl  border border-black rounded-full"
                                        role="presentation"
                                    ></span>

                                    <p class="text-base">@lang('shop::app.checkout.onepage.addresses.shipping.add-new-address')</p>
                                </div>
                            </div>
                        </div>

                        <v-error-message
                            class="text-red-500 text-xs italic"
                            name="shipping[address_id]"
                        >
                        </v-error-message>


                        <template v-if="meta.valid">
                            <div v-if="! forms.billing.isNew && ! forms.shipping.isNew && ! forms.billing.isUsedForShipping && addresses.shipping.length">
                                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping.confirm_button.before') !!}

                                <div class="flex justify-end mt-4">
                                    <x-shop::button
                                        class="primary-button py-3 px-11 rounded-2xl"
                                        :title="trans('shop::app.checkout.onepage.addresses.shipping.confirm')"
                                        :loading="false"
                                        ref="storeAddress"
                                        @click="store"
                                    >
                                    </x-shop::button>
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping.confirm_button.after') !!}
                            </div>
                        </template>
    
                        <template v-else>
                            <div v-if="! forms.billing.isNew && ! forms.shipping.isNew && ! forms.billing.isUsedForShipping">
                                <div class="flex justify-end mt-4">
                                    <button
                                        type="submit"
                                        class="primary-button py-3 px-11 rounded-2xl"
                                    >
                                        @lang('shop::app.checkout.onepage.addresses.shipping.confirm')
                                    </button>
                                </div>
                            </div>
                        </template>
                        
                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping.after') !!}
                    </form>
                </x-shop::form>

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping.before') !!}
            </x-slot:content>
        </x-shop::accordion>

        {!! view_render_event('bagisto.shop.checkout.onepage.shipping.accordion.after') !!}
    </template>

    <template v-else>
        <x-shop::accordion class="!border-b-0 mt-8">
            <x-slot:header class="!p-0">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-medium max-sm:text-xl">
                        @lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')
                    </h2>
                </div>
            </x-slot:header>
        
            <x-slot:content class="!p-0 mt-8">
                {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address.before') !!}

                <!-- Shipping address form -->
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, handleShippingAddressForm)">
                        <div>
                            <a 
                                class="flex justify-end"
                                href="javascript:void(0)" 
                                v-if="addresses.shipping.length > 0"
                                @click="forms.shipping.isNew = ! forms.shipping.isNew"
                            >
                                <span class="icon-arrow-left text-2xl"></span>

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

                        <div class="grid grid-cols-2 gap-x-5">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-0 required">
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
                                <x-shop::form.control-group.label class="!mt-0 required">
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
                            <x-shop::form.control-group.label class="!mt-0 required">
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
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.street-address')
                            </x-shop::form.control-group.label>
                        
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping[address1][]"
                                rules="required|address"
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
                        
                        <div class="grid grid-cols-2 gap-x-5">
                            <x-shop::form.control-group
                                class="!mb-4"
                            >
                                <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.country')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="select"
                                    name="shipping[country]"
                                    rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
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
                                <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} !mt-0">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.state')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[state]"
                                    rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                    v-model="forms.shipping.address.state"
                                    v-if="! haveStates('shipping')"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.control
                                    type="select"
                                    name="shipping[state]"
                                    rules="{{ core()->isStateRequired() ? 'required' : '' }}"
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

                        <div class="grid grid-cols-2 gap-x-5">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-0 required">
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
                                <x-shop::form.control-group.label class="!mt-0 required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.postcode')
                                </x-shop::form.control-group.label>
                            
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping[postcode]"
                                    rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}"
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
                            <x-shop::form.control-group.label class="!mt-0 required">
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

                        <div class="grid gap-2.5 pb-4">
                            @auth('customer')
                                <div class="flex gap-x-1.5 items-center text-md text-[#6E6E6E] select-none">
                                    <input 
                                        type="checkbox"
                                        name="shipping[is_save_as_address]"
                                        id="shipping[is_save_as_address]"
                                        class="hidden peer"
                                        v-model="forms.shipping.address.isSaved"
                                    >

                                    <label
                                        class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                        for="shipping[is_save_as_address]"
                                    >
                                    </label>

                                    <label for="shipping[is_save_as_address]">
                                        @lang('shop::app.checkout.onepage.addresses.shipping.save-address')
                                    </label>
                                </div>
                            @endauth
                        </div>

                        <div 
                            class="flex justify-end mt-4"
                        >
                            <button
                                type="submit"
                                class="block w-max px-11 py-3 bg-navyBlue rounded-2xl text-white text-base font-medium text-center cursor-pointer"
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
    </template>
</div>