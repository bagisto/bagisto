<!-- Addresses Cards -->
<template v-if="! customer.updateOrCreateBillingAddress.isEnabled">
    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.accordion.before') !!}

    <x-shop::accordion class="!border-b-0">
        <x-slot:header class="! py-4 !px-0">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-medium max-sm:text-xl">
                    @lang('shop::app.checkout.onepage.addresses.billing.billing-address')
                </h2>
            </div>
        </x-slot>

        <x-slot:content class="!p-0 mt-8">
            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.form.before') !!}

            <x-shop::form
                v-slot="{ meta, errors, values, handleSubmit }"
                as="div"
                ref="customerBillingAddressForm"
            >
                <form @submit="handleSubmit($event, storeCustomerBillingAddressToCart)">
                    <div class="grid gap-5 grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                        <!-- Billing Address ID -->
                        <template v-if="! isAddressEmpty(customer.cart.billingAddress)">
                            <x-shop::form.control-group.control
                                type="hidden"
                                name="billing_address_id"
                                ::value="customer.applied.selectedBillingAddressId"
                            />
                        </template>

                        <!-- Existing Addresses -->
                        <div
                            class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                            v-for="(address, index) in customerBillingAddresses"
                        >
                            <v-field
                                type="radio"
                                name="billing_address_id"
                                :value="address.id"
                                :id="`billing_address_id_${address.id}`"
                                class="hidden peer"
                                rules="required"
                                label="@lang('shop::app.checkout.onepage.addresses.billing.billing-address')"
                                v-slot="{ field }"
                            >
                                <input
                                    type="radio"
                                    name="billing_address_id"
                                    :value="address.id"
                                    :id="`billing_address_id_${address.id}`"
                                    class="hidden peer"
                                    v-bind="field"
                                    :checked="address.id === customer.applied.selectedBillingAddressId"
                                />
                            </v-field>

                            <label
                                class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                :for="`billing_address_id_${address.id}`"
                            >
                            </label>

                            <!-- Edit Icon -->
                            <span
                                class="icon-edit absolute ltr:right-14 rtl:left-14 top-5 text-2xl cursor-pointer"
                                @click="openUpdateOrCreateCustomerAddressForm(address)"
                            ></span>

                            <!-- Details -->
                            <label
                                :for="`billing_address_id_${address.id}`"
                                class="block p-5 rounded-xl cursor-pointer"
                            >
                                <span class="icon-checkout-address text-6xl text-navyBlue"></span>

                                <div class="flex justify-between items-center">
                                    <p class="text-base font-medium">
                                        @{{ address.firstName }} @{{ address.lastName }}

                                        <span v-if="address.companyName">(@{{ address.companyName }})</span>
                                    </p>
                                </div>

                                <p class="mt-6 text-sm text-[#6E6E6E]">
                                    <template v-if="address.address1">
                                        @{{ address.address1 }},
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
                            @click="openUpdateOrCreateCustomerAddressForm({})"
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

                    <!-- Error Message Block -->
                    <v-error-message
                        class="text-red-500 text-xs italic"
                        name="billing_address_id"
                    >
                    </v-error-message>

                    <!-- Checkbox For Different Address -->
                    <div
                        class="flex gap-x-1.5 items-center mt-5 text-sm text-[#6E6E6E] select-none"
                        v-if="cart.have_stockable_items"
                    >
                        <div v-if="customer.applied.useDifferentAddressForShipping">
                            <x-shop::form.control-group.control
                                type="hidden"
                                name="use_different_address_for_shipping"
                                ::value="!! customer.applied.useDifferentAddressForShipping"
                            />
                        </div>

                        <v-field
                            type="checkbox"
                            name="use_different_address_for_shipping"
                            v-slot="{ field }"
                            :value="true"
                        >
                            <input
                                type="checkbox"
                                name="use_different_address_for_shipping"
                                id="use_different_address_for_shipping"
                                class="sr-only peer"
                                v-bind="field"
                                :checked="!! customer.applied.useDifferentAddressForShipping"
                                @click="customer.applied.useDifferentAddressForShipping = ! customer.applied.useDifferentAddressForShipping;"
                            />
                        </v-field>

                        <label
                            class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                            for="use_different_address_for_shipping"
                        >
                        </label>

                        <label
                            for="use_different_address_for_shipping"
                            class="cursor-pointer"
                        >
                            @lang('shop::app.checkout.onepage.addresses.billing.use-different-address-for-shipping')
                        </label>
                    </div>

                    <!-- Proceed Button -->
                    <div
                        class="flex justify-end mt-4"
                        v-if="customerBillingAddresses.length && ! customer.applied.useDifferentAddressForShipping || ! cart.have_stockable_items"
                    >
                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.proceed_button.before') !!}

                        <x-shop::button
                            class="primary-button py-3 px-11 rounded-2xl"
                            :title="trans('shop::app.checkout.onepage.addresses.billing.proceed')"
                            :loading="false"
                            v-if="! isLoading"
                        />

                        <x-shop::button
                            class="primary-button py-3 px-11 rounded-2xl"
                            :title="trans('shop::app.checkout.onepage.addresses.billing.proceed')"
                            :loading="true"
                            :disabled="true"
                            v-else
                        />

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.proceed_button.after') !!}
                    </div>
                </form>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.form.after') !!}
        </x-slot>
    </x-shop::accordion>

    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.accordion.after') !!}
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
            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.back_button.before') !!}

            <!-- Back Button -->
            <div>
                <a
                    class="flex justify-end"
                    href="javascript:void(0)"
                    @click="closeUpdateOrCreateCustomerAddressForm()"
                >
                    <span class="icon-arrow-left text-2xl"></span>

                    <span>@lang('shop::app.checkout.onepage.addresses.billing.back')</span>
                </a>
            </div>

            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.back_button.after') !!}

            <!-- Billing Address Form -->
            <x-shop::form
                v-slot="{ meta, errors, values, handleSubmit }"
                as="div"
                id="modalForm"
            >
                <form @submit="handleSubmit($event, updateOrCreateCustomerAddress)">
                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.form.before') !!}

                    <!-- Address ID -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.control
                            type="hidden"
                            name="type"
                            value="billing"
                        />

                        <x-shop::form.control-group.control
                            type="hidden"
                            name="billing.id"
                            ::value="customer.updateOrCreateBillingAddress.params?.id"
                        />
                    </x-shop::form.control-group>

                    <!-- Company Name -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('shop::app.checkout.onepage.addresses.billing.company-name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.company_name"
                            ::value="customer.updateOrCreateBillingAddress.params?.companyName"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.company-name')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.billing.company-name')"
                        />

                        <x-shop::form.control-group.error control-name="billing.company_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.company_name.after') !!}

                    <div class="grid grid-cols-2 gap-x-5">
                        <!-- First Name -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.billing.first-name')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.first_name"
                                ::value="customer.updateOrCreateBillingAddress.params?.firstName"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.first-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.first-name')"
                            />

                            <x-shop::form.control-group.error control-name="billing.first_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.first_name.after') !!}

                        <!-- Last Name -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.billing.last-name')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.last_name"
                                ::value="customer.updateOrCreateBillingAddress.params?.lastName"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.last-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.last-name')"
                            />

                            <x-shop::form.control-group.error control-name="billing.last_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.last_name.after') !!}
                    </div>

                    <!-- Email -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="billing.email"
                            ::value="customer.updateOrCreateBillingAddress.params?.email"
                            rules="required|email"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.email')"
                            placeholder="email@example.com"
                        />

                        <x-shop::form.control-group.error control-name="billing.email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.email.after') !!}

                    <!-- Street Address -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.street-address')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.address1.[0]"
                            ::value="customer.updateOrCreateBillingAddress.params?.address1"
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

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.address1.after') !!}

                    <div class="grid grid-cols-2 gap-x-5">
                        <!-- Country -->
                        <x-shop::form.control-group class="!mb-4">
                            <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.country')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="select"
                                name="billing.country"
                                ::value="customer.updateOrCreateBillingAddress.params?.country"
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

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.country.after') !!}

                        <!-- State -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.state')
                            </x-shop::form.control-group.label>

                            <template v-if="haveStates(values.billing?.country)">
                                <x-shop::form.control-group.control
                                    type="select"
                                    name="billing.state"
                                    rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                >
                                    <option value="">
                                        @lang('shop::app.checkout.onepage.addresses.billing.select-state')
                                    </option>

                                    <option
                                        v-for='(state, index) in states[values.billing?.country]'
                                        :value="state.code"
                                    >
                                        @{{ state.default_name }}
                                    </option>
                                </x-shop::form.control-group.control>
                            </template>

                            <template v-else>
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="billing.state"
                                    ::value="customer.updateOrCreateBillingAddress.params?.state"
                                    rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                    :label="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.billing.state')"
                                />
                            </template>

                            <x-shop::form.control-group.error control-name="billing.state" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.state.after') !!}
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
                                ::value="customer.updateOrCreateBillingAddress.params?.city"
                                rules="required"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.city')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.city')"
                            />

                            <x-shop::form.control-group.error control-name="billing.city" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.city.after') !!}

                        <!-- Postcode -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} !mt-0">
                                @lang('shop::app.checkout.onepage.addresses.billing.postcode')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="billing.postcode"
                                ::value="customer.updateOrCreateBillingAddress.params?.postcode"
                                rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.onepage.addresses.billing.postcode')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.billing.postcode')"
                            />

                            <x-shop::form.control-group.error control-name="billing.postcode" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.postcode.after') !!}
                    </div>

                    <!-- Phone Number -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="!mt-0 required">
                            @lang('shop::app.checkout.onepage.addresses.billing.telephone')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="billing.phone"
                            ::value="customer.updateOrCreateBillingAddress.params?.phone"
                            rules="required|numeric"
                            :label="trans('shop::app.checkout.onepage.addresses.billing.telephone')"
                            :placeholder="trans('shop::app.checkout.onepage.addresses.billing.telephone')"
                        />

                        <x-shop::form.control-group.error control-name="billing.phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.phone.after') !!}

                    <!-- Checkbox For Save Address -->
                    <div
                        class="flex gap-x-1.5 items-center mt-5 text-sm text-[#6E6E6E] select-none"
                        v-if="! (customer.updateOrCreateBillingAddress.params.id > -1)"
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

                    <!-- Save Button -->
                    <div class="flex justify-end mt-4">
                        <x-shop::button
                            class="primary-button py-3 px-11 rounded-2xl"
                            :title="trans('shop::app.checkout.onepage.addresses.billing.save')"
                            :loading="false"
                            v-if="! isLoading"
                        />

                        <x-shop::button
                            class="primary-button py-3 px-11 rounded-2xl"
                            :title="trans('shop::app.checkout.onepage.addresses.billing.save')"
                            :loading="true"
                            :disabled="true"
                            v-else
                        />
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.update_or_create.form.after') !!}
                </form>
            </x-shop::form>
        </x-slot>
    </x-shop::accordion>
</template>
