<template v-if="! addNewBillingAddress">
    {!! view_render_event('bagisto.shop.checkout.onepage.billing.accordion.before') !!}

    <x-shop::accordion class="!border-b-0">
        <x-slot:header class="! py-4 !px-0">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-medium max-sm:text-xl">
                    @lang('shop::app.checkout.onepage.addresses.billing.billing-address')
                </h2>
            </div>
        </x-slot>
    
        <x-slot:content class="!p-0 mt-8">
            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.before') !!}

            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, updateCartAddress)">
                    <div class="grid gap-5 grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                        <div 
                            class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                            v-for="(address, index) in savedBillingAddresses"
                        >
                            <v-field
                                type="radio"
                                class="hidden peer"
                                :id="`selectedAddresses.billing_address_id${address.id}`"
                                name="selectedAddresses.billing_address_id"
                                rules="required"
                                v-model="selectedBillingAddressId"
                                :value="address.id"
                                label="@lang('shop::app.checkout.onepage.addresses.billing.billing-address')"
                                :checked="selectedBillingAddressId"
                            />

                            <label 
                                class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                :for="`selectedAddresses.billing_address_id${address.id}`"
                            >
                            </label>

                            <!-- Edit Icon -->
                            <span
                                class="icon-edit absolute ltr:right-14 rtl:left-14 top-5 text-2xl cursor-pointer"
                                @click="addNewBillingAddress=true;tempBillingAddress=address;isAddressEditable=true;isLoading=false;"
                            ></span>

                            <!-- Deatils -->
                            <label 
                                :for="`selectedAddresses.billing_address_id${address.id}`"
                                class="block p-5 rounded-xl cursor-pointer"
                            >
                                <span class="icon-checkout-address text-6xl text-navyBlue"></span>

                                <div class="flex justify-between items-center">
                                    <p class="text-base font-medium">
                                        @{{ address.first_name }} @{{ address.last_name }}
                                        
                                        <span v-if="address.company_name">(@{{ address.company_name }})</span>
                                    </p>
                                </div>

                                <p class="mt-6 text-sm text-[#6E6E6E]">
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

                        <!-- New Address Add Button -->
                        <div 
                            class="flex justify-center items-center max-w-[414px] p-5 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap cursor-pointer"
                            @click="addNewBillingAddress=true;tempBillingAddress={};isAddressEditable=false;isLoading=false;"
                        >
                            <div
                                class="flex gap-x-2.5 items-center"
                                role="button"
                                tabindex="0"
                            >
                                <span
                                    class="icon-plus p-2.5 border border-black rounded-full text-3xl"
                                    role="presentation"
                                ></span>

                                <p class="text-base">@lang('shop::app.checkout.onepage.addresses.billing.add-new-address')</p>
                            </div>
                        </div>
                    </div>

                    <v-error-message
                        class="text-red-500 text-xs italic"
                        name="selectedAddresses.billing_address_id"
                    >
                    </v-error-message>

                    <!-- Checkbox for enabling the same address for shipping also. -->
                    <div 
                        class="flex gap-x-1.5 items-center mt-5 text-sm text-[#6E6E6E] select-none"
                        v-if="selectedBillingAddressId"
                        @click="isLoading=false;"
                    >
                        <input
                            type="checkbox"
                            class="hidden peer"
                            id="isUsedForShipping"
                            name="is_use_for_shipping"
                            v-model="shippingIsSameAsBilling"
                            label="@lang('shop::app.checkout.onepage.addresses.billing.billing-address')"
                        />
                
                        <label 
                            class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
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
                </form>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.after') !!}

            <!-- Confirm Button -->
            <div
                class="flex justify-end mt-4"
                v-if="
                (selectedBillingAddressId || selectedShippingAddressId)
                && ! toggleShippingForm
                && ! addNewBillingAddress
                && shippingIsSameAsBilling
                "
            >
                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.confirm_button.before') !!}

                <x-shop::button
                    v-if="!isLoading"
                    type="button"
                    class="primary-button py-3 px-11 rounded-2xl"
                    :title="trans('shop::app.checkout.onepage.addresses.billing.confirm')"
                    :loading="false"
                    @click="proceed"
                />

                <x-shop::button
                    type="button"
                    class="primary-button py-3 px-11 rounded-2xl"
                    v-else
                    :title="trans('shop::app.checkout.onepage.addresses.billing.confirm')"
                    :loading="true"
                    :disabled="true"
                />

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.confirm_button.after') !!}
            </div>
        </x-slot>   
    </x-shop::accordion>

    {!! view_render_event('bagisto.shop.checkout.onepage.billing.accordion.after') !!}
</template>

<!-- Billing Address Form -->
<template v-else>
    <x-shop::accordion class="!border-b-0">
        <x-slot:header class="!p-0">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-medium max-sm:text-xl">
                    @lang('shop::app.checkout.onepage.addresses.billing.billing-address')
                </h2>
            </div>
        </x-slot>
    
        <x-slot:content class="!p-0 mt-8">
            <!-- Back Button -->
            <div>
                <a 
                    class="flex justify-end"
                    href="javascript:void(0)" 
                    @click="addNewBillingAddress=false;tempBillingAddress={};isAddressEditable=false;isLoading=false;"
                >
                    <span class="icon-arrow-left text-2xl"></span>

                    <span>@lang('shop::app.checkout.onepage.addresses.billing.back')</span>
                </a>
            </div>

            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.before') !!}

            <!-- Billing address form -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                id="modalForm"
            >
                <form @submit="handleSubmit($event, store)">
                    {!! view_render_event('bagisto.shop.checkout.onepage.billing_address_form.before') !!}

                    <!-- Hidden Fields -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.control
                            type="hidden"
                            name="type"
                            value="billing"
                        />

                        <x-shop::form.control-group.control
                            type="hidden"
                            name="billing.use_for_shipping"
                            ::value="false"
                        />

                        <x-shop::form.control-group.control
                            type="hidden"
                            name="billing.id"
                            ::value="tempBillingAddress.id"
                        />

                        <x-shop::form.control-group.control
                            type="hidden"
                            name="billing.default_address"
                            value="0"
                        />

                        <x-shop::form.control-group.control
                            type="hidden"
                            name="shipping.address1.[0]"
                        />
                    </x-shop::form.control-group>

                    <!-- Company name -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('shop::app.checkout.onepage.addresses.billing.company-name')
                        </x-shop::form.control-group.label>
            
                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.company_name"
                            ::value="tempBillingAddress.company_name"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.company-name')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.billing.company-name')"
                        />

                        <x-shop::form.control-group.error control-name="billing.company_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.company_name.after') !!}

                    <!-- First Name -->
                    <div class="grid grid-cols-2 gap-x-5">
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.billing.first-name')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.first_name"
                                rules="required"
                                ::value="tempBillingAddress.first_name"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.first-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.first-name')"
                            />
    
                            <x-shop::form.control-group.error control-name="billing.first_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.first_name.after') !!}

                        <!-- Last Name -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.billing.last-name')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.last_name"
                                rules="required"
                                ::value="tempBillingAddress.last_name"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.last-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.last-name')"
                            />
    
                            <x-shop::form.control-group.error control-name="billing.last_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.last_name.after') !!}
                    </div>

                    <!-- Email -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="billing.email"
                            rules="required|email"
                            ::value="tempBillingAddress.email"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.email')"
                            placeholder="email@example.com"
                        />

                        <x-shop::form.control-group.error control-name="billing.email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.email.after') !!}

                    <!-- Street Address -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.street-address')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.address1.[0]"
                            rules="required|address"
                            ::value="Array.isArray(tempBillingAddress.address1) ? tempBillingAddress.address1[0] : tempBillingAddress.address1?.split('\n')[0]"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                        />

                        <x-shop::form.control-group.error
                            class="mb-2"
                            control-name="billing.address1.[0]"
                        />

                        @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                            @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="billing.address1.[{{ $i }}]"
                                    ::value="Array.isArray(tempBillingAddress.address1) ? tempBillingAddress.address1[{{ $i }}] : tempBillingAddress.address1?.split('\n')[{{$i}}]"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                                />
                            @endfor
                        @endif
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.address1.after') !!}

                    <div class="grid grid-cols-2 gap-x-5">
                        <!--Country -->
                        <x-shop::form.control-group class="!mb-4">
                            <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.country')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="select"
                                name="billing.country"
                                rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
                                ::value="tempBillingAddress.country"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.country')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.country')"
                            >
                                <option value="">
                                    @lang('shop::app.checkout.onepage.addresses.billing.select-country')
                                </option>

                                <option
                                    v-for="country in countries"
                                    :value="country.code"
                                    v-text="country.name"
                                >
                                </option>
                            </x-shop::form.control-group.control>
    
                            <x-shop::form.control-group.error control-name="billing.country" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.country.after') !!}

                        <!--State -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.state')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.state"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                ::value="tempBillingAddress.state"
                                v-if="! haveStates('billing')"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.state')"
                            />

                            <x-shop::form.control-group.control
                                type="select"
                                name="billing.state"
                                rules="required"
                                ::value="tempBillingAddress.state"
                                v-if="haveStates('billing')"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.state')"
                            >
                                <option value="">
                                    @lang('shop::app.checkout.onepage.addresses.billing.select-state')
                                </option>

                                <option 
                                    v-for='(state, index) in states[forms.billing.address.country]' 
                                    :value="state.code" 
                                >
                                    @{{ state.default_name }}
                                </option>
                            </x-shop::form.control-group.control>
    
                            <x-shop::form.control-group.error control-name="billing.state" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.state.after') !!}
                    </div>

                    <div class="grid grid-cols-2 gap-x-5">
                        <!-- City -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.billing.city')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.city"
                                rules="required"
                                ::value="tempBillingAddress.city"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.city')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.city')"
                            />

                            <x-shop::form.control-group.error control-name="billing.city" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.city.after') !!}
    
                        <!-- Postcode -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.postcode')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.postcode"
                                rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}"
                                ::value="tempBillingAddress.postcode"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.postcode')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.postcode')"
                            />

                            <x-shop::form.control-group.error control-name="billing.postcode" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.postcode.after') !!}
                    </div>

                    <!-- Phone Number -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.telephone')
                        </x-shop::form.control-group.label>
                        
                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.phone"
                            rules="required|numeric"
                            ::value="tempBillingAddress.phone"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.telephone')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.billing.telephone')"
                        />

                        <x-shop::form.control-group.error control-name="billing.phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.phone.after') !!}

                    <!-- Checkbox for save address -->
                    @auth('customer')
                        <div
                            class="flex gap-x-1.5 items-center mt-5 text-sm text-[#6E6E6E] select-none"
                            v-if="! isAddressEditable"
                        >
                            <v-field
                                type="checkbox"
                                name="billing.save_address"
                                v-slot="{ field }"
                                value="1"
                            >
                                <input
                                    type="checkbox"
                                    name="billing.save_address"
                                    v-bind="field"
                                    id="billing.save_address"
                                    class="sr-only peer"
                                />
                            </v-field>

                            <label 
                                class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                for="billing.save_address"
                            >
                            </label>
                            
                            <label 
                                for="billing.save_address"
                                class="cursor-pointer"
                            >
                                @lang('shop::app.checkout.onepage.addresses.billing.save-address')
                            </label>
                        </div>
                    @endauth

                    <!-- Save Button -->
                    <div class="flex justify-end mt-4">
                        <button
                            type="submit"
                            class="block py-3 px-11 bg-navyBlue text-white text-base w-max font-medium rounded-2xl text-center cursor-pointer"
                            v-if="!isLoading"
                        >
                            @lang('shop::app.checkout.onepage.addresses.billing.save')
                        </button>

                        <x-shop::button
                            type="button"
                            class="primary-button py-3 px-11 rounded-2xl"
                            v-else
                            :title="trans('shop::app.checkout.onepage.addresses.billing.save')"
                            :loading="true"
                        />
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.billing_address_form.after') !!}
                </form>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.after') !!}
        </x-slot>
    </x-shop::accordion>
</template>