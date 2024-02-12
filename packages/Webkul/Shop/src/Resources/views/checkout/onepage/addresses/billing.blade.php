<template v-if="! addNewBillingAddress">
    <x-shop::accordion class="!border-b-0">
        <x-slot:header class="!p-0">
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
                                :id="`selectedAddresses.billing_address_id${address.id}`"
                                :name="`selectedAddresses.billing_address_id${address.id}`"
                                class="hidden peer"
                                label="@lang('shop::app.checkout.onepage.addresses.billing.billing-address')"
                                rules="required"
                                :value="address.id"
                                :checked="address.id"
                                v-model="selectedAddresses.billing_address_id"
                            />

                            <label 
                                class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                :for="`selectedAddresses.billing_address_id${address.id}`"
                            >
                            </label>

                            <label 
                                :for="`selectedAddresses.billing_address_id${address.id}`"
                                class="block p-5 rounded-xl cursor-pointer"
                            >
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

                        <div 
                            class="flex justify-center items-center max-w-[414px] p-5 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap cursor-pointer"
                            @click="addNewBillingAddress=true;"
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

                    <div 
                        class="flex gap-x-1.5 mt-5 text-sm text-[#6E6E6E] select-none"
                        v-if="selectedAddresses.billing_address_id"
                    >
                        <input
                            type="checkbox"
                            name="is_use_for_shipping"
                            id="isUsedForShipping"
                            class="hidden peer"
                            label="@lang('shop::app.checkout.onepage.addresses.billing.billing-address')"
                            v-model="shippingAddress.sameAsBilling"
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
        </x-slot>
    </x-shop::accordion>
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
                    @click="addNewBillingAddress = false;"
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
            >
                <form @submit="handleSubmit($event, storeBilling)">
                    {!! view_render_event('bagisto.shop.checkout.onepage.billing_address_form.before') !!}

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('shop::app.checkout.onepage.addresses.billing.company-name')
                        </x-shop::form.control-group.label>
            
                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.company_name"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.company-name')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.billing.company-name')"
                        />

                        <x-shop::form.control-group.error control-name="billing.company_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.company_name.after') !!}

                    <div class="grid grid-cols-2 gap-x-5">
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.billing.first-name')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.first_name"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.first-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.first-name')"
                            />
    
                            <x-shop::form.control-group.error control-name="billing.first_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.first_name.after') !!}

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.billing.last-name')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.last_name"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.last-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.last-name')"
                            />
    
                            <x-shop::form.control-group.error control-name="billing.last_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.last_name.after') !!}
                    </div>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="billing.email"
                            rules="required|email"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.email')"
                            placeholder="email@example.com"
                        />

                        <x-shop::form.control-group.error control-name="billing.email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.email.after') !!}

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.street-address')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.address1.[0]"
                            rules="required|address"
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
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.street-address')"
                                />
                            @endfor
                        @endif
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.address1.after') !!}

                    <div class="grid grid-cols-2 gap-x-5">
                        <x-shop::form.control-group class="!mb-4">
                            <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.country')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="select"
                                name="billing.country"
                                rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
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

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.state')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.state"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                v-if="! haveStates('billing')"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.state')"
                            />

                            <x-shop::form.control-group.control
                                type="select"
                                name="billing.state"
                                rules="required"
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
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.billing.city')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.city"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.city')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.city')"
                            />

                            <x-shop::form.control-group.error control-name="billing.city" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.city.after') !!}
    
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.postcode')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.postcode"
                                rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.postcode')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.postcode')"
                            />

                            <x-shop::form.control-group.error control-name="billing.postcode" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.postcode.after') !!}
                    </div>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.telephone')
                        </x-shop::form.control-group.label>
                        
                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.phone"
                            rules="required|numeric"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.telephone')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.billing.telephone')"
                        />

                        <x-shop::form.control-group.error control-name="billing.phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.phone.after') !!}

                    <div class="grid gap-2.5 pb-4">
                        @auth('customer')
                            <div class="flex">
                                <v-field
                                    type="hidden"
                                    name="billing.default_address"
                                    value="0"
                                >
                                </v-field>

                                <v-field
                                    type="checkbox"
                                    class="hidden peer"
                                    id="billing.default_address"
                                    name="billing.default_address"
                                    value="1"
                                >
                                </v-field>
                                
                                <label
                                    class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                    for="billing.default_address"
                                ></label>
                                
                                <label
                                    for="billing.default_address"
                                    class="ltr:ml-1 rtl:mr-1 cursor-pointer"
                                >
                                    @lang('shop::app.checkout.onepage.addresses.billing.save-address')
                                </label>
                            </div>
                        @endauth
                    </div>

                    <div class="flex justify-end mt-4">
                        <button
                            type="submit"
                            class="block py-3 px-11 bg-navyBlue text-white text-base w-max font-medium rounded-2xl text-center cursor-pointer"
                        >
                            @lang('shop::app.checkout.onepage.addresses.billing.confirm')
                        </button>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.billing_address_form.after') !!}
                </form>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing_address.after') !!}
        </x-slot>
    </x-shop::accordion>
</template>