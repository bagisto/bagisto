<template v-if="! shippingAddress.sameAsBilling && ! shippingAddress.isShowShippingForm">
    <x-shop::accordion class="!border-b-0 !mt-8">
        <x-slot:header class="!p-0">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-medium max-sm:text-xl">
                    @lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')
                </h2>
            </div>
        </x-slot>
    
        <x-slot:content class="!p-0 mt-8">
            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.before') !!}
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, updateCartAddress)">
                    <div class="grid gap-5 grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                        <div 
                            class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                            v-for="(address, index) in savedShippingAddresses"
                        >
                            <input
                                type="radio"
                                :id="`selectedAddresses.shipping_address_id${address.id}`"
                                name="selectedAddresses.shipping_address_id"
                                :value="address.id"
                                class="hidden peer"
                                label="@lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')"
                                rules="required"
                                :checked="selectedAddresses.shipping.id"
                                v-model="selectedAddresses.shipping.id"
                            />

                            <label 
                                class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                :for="`selectedAddresses.shipping_address_id${address.id}`"
                            >
                            </label>

                            <label 
                                :for="`selectedAddresses.shipping_address_id${address.id}`"
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
                            @click="shippingAddress.isShowShippingForm=true;"
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

                                <p class="text-base">@lang('shop::app.checkout.onepage.addresses.shipping.add-new-address')</p>
                            </div>
                        </div>
                    </div>

                    <v-error-message
                        class="text-red-500 text-xs italic"
                        name="selectedAddresses.shipping_address_id"
                    >
                    </v-error-message>
                </form>
            </x-shop::form>
            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.after') !!}
        </x-slot>
    </x-shop::accordion>
</template>

<!-- shipping Address Form -->
<template v-if="! shippingAddress.sameAsBilling && shippingAddress.isShowShippingForm">
    <x-shop::accordion class="!border-b-0">
        <x-slot:header class="!p-0">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-medium max-sm:text-xl">
                    @lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')
                </h2>
            </div>
        </x-slot>
    
        <x-slot:content class="!p-0 mt-8">
            <!-- Back Button -->
            <div>
                <a 
                    class="flex justify-end"
                    href="javascript:void(0)" 
                    @click="shippingAddress.isShowShippingForm = false;"
                >
                    <span class="icon-arrow-left text-2xl"></span>

                    <span>@lang('shop::app.checkout.onepage.addresses.shipping.back')</span>
                </a>
            </div>

            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.before') !!}

            <!-- shipping address form -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, store)">
                    {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address_form.before') !!}
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.control
                            type="hidden"
                            name="type"
                            value="shipping"
                        />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('shop::app.checkout.onepage.addresses.shipping.company-name')
                        </x-shop::form.control-group.label>
            
                        <x-shop::form.control-group.control
                            type="text"
                            name="shipping.company_name"
                            :label="trans('shop::app.checkout.onepage.addresses.shipping.company-name')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.company-name')"
                        />

                        <x-shop::form.control-group.error control-name="shipping.company_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.company_name.after') !!}

                    <div class="grid grid-cols-2 gap-x-5">
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.first-name')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping.first_name"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.first-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.first-name')"
                            />
    
                            <x-shop::form.control-group.error control-name="shipping.first_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.first_name.after') !!}

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.last-name')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping.last_name"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.last-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.last-name')"
                            />
    
                            <x-shop::form.control-group.error control-name="shipping.last_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.last_name.after') !!}
                    </div>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.shipping.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="shipping.email"
                            rules="required|email"
                            :label="trans('shop::app.checkout.onepage.addresses.shipping.email')"
                            placeholder="email@example.com"
                        />

                        <x-shop::form.control-group.error control-name="shipping.email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.email.after') !!}

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.shipping.street-address')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="shipping.address1.[0]"
                            rules="required|address"
                            :label="trans('shop::app.checkout.onepage.addresses.shipping.street-address')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.street-address')"
                        />

                        <x-shop::form.control-group.error
                            class="mb-2"
                            control-name="shipping.address1.[0]"
                        />

                        @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                            @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping.address1.[{{ $i }}]"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.street-address')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.street-address')"
                                />
                            @endfor
                        @endif
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.address1.after') !!}

                    <div class="grid grid-cols-2 gap-x-5">
                        <x-shop::form.control-group class="!mb-4">
                            <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.shipping.country')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="select"
                                name="shipping.country"
                                rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.country')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.country')"
                            >
                                <option value="">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.select-country')
                                </option>

                                <option
                                    v-for="country in countries"
                                    :value="country.code"
                                    v-text="country.name"
                                >
                                </option>
                            </x-shop::form.control-group.control>
    
                            <x-shop::form.control-group.error control-name="shipping.country" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.country.after') !!}

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.shipping.state')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping.state"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                v-if="! haveStates('shipping')"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                            />

                            <x-shop::form.control-group.control
                                type="select"
                                name="shipping.state"
                                rules="required"
                                v-if="haveStates('shipping')"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                            >
                                <option value="">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.select-state')
                                </option>

                                <option 
                                    v-for='(state, index) in states[forms.shipping.address.country]' 
                                    :value="state.code" 
                                >
                                    @{{ state.default_name }}
                                </option>
                            </x-shop::form.control-group.control>
    
                            <x-shop::form.control-group.error control-name="shipping.state" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.state.after') !!}
                    </div>

                    <div class="grid grid-cols-2 gap-x-5">
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.city')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping.city"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.city')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.city')"
                            />

                            <x-shop::form.control-group.error control-name="shipping.city" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.city.after') !!}
    
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.shipping.postcode')
                            </x-shop::form.control-group.label>
    
                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping.postcode"
                                rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.postcode')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.postcode')"
                            />

                            <x-shop::form.control-group.error control-name="shipping.postcode" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.postcode.after') !!}
                    </div>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.shipping.telephone')
                        </x-shop::form.control-group.label>
                        
                        <x-shop::form.control-group.control
                            type="text"
                            name="shipping.phone"
                            rules="required|numeric"
                            :label="trans('shop::app.checkout.onepage.addresses.shipping.telephone')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.telephone')"
                        />

                        <x-shop::form.control-group.error control-name="shipping.phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.phone.after') !!}

                    <div class="flex justify-end mt-4">
                        <button
                            type="submit"
                            class="block py-3 px-11 bg-navyBlue text-white text-base w-max font-medium rounded-2xl text-center cursor-pointer"
                        >
                            @lang('shop::app.checkout.onepage.addresses.shipping.confirm')
                        </button>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.shipping_address_form.after') !!}
                </form>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.after') !!}
        </x-slot>
    </x-shop::accordion>
</template>