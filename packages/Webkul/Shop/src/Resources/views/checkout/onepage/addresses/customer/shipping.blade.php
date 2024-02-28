<div
    class="mt-8"
    v-if="cart.have_stockable_items && customer.applied.useDifferentAddressForShipping"
>
    <!-- Addresses Cards -->
    <template v-if="! customer.updateOrCreateShippingAddress.isEnabled">
        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.accordion.before') !!}

        <x-shop::accordion class="!border-b-0">
            <x-slot:header class="! py-4 !px-0">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-medium max-sm:text-xl">
                        @lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')
                    </h2>
                </div>
            </x-slot>

            <x-slot:content class="!p-0 mt-8">
                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.form.before') !!}

                <x-shop::form
                    v-slot="{ meta, errors, values, handleSubmit }"
                    as="div"
                    ref="customerShippingAddressForm"
                >
                    <form @submit="handleSubmit($event, storeCustomerShippingAddressToCart)">
                        <div class="grid gap-5 grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                            <!-- Shipping Address ID -->
                            <template v-if="! isAddressEmpty(customer.cart.shippingAddress)">
                                <x-shop::form.control-group.control
                                    type="hidden"
                                    name="shipping_address_id"
                                    ::value="customer.applied.selectedShippingAddressId"
                                />
                            </template>

                            <!-- Existing Addresses -->
                            <div
                                class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                                v-for="(address, index) in customerShippingAddresses"
                            >
                                <v-field
                                    type="radio"
                                    name="shipping_address_id"
                                    :value="address.id"
                                    :id="`shipping_address_id_${address.id}`"
                                    class="hidden peer"
                                    rules="required"
                                    label="@lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')"
                                    v-slot="{ field }"
                                >
                                    <input
                                        type="radio"
                                        name="shipping_address_id"
                                        :value="address.id"
                                        :id="`shipping_address_id_${address.id}`"
                                        class="hidden peer"
                                        v-bind="field"
                                        :checked="address.id === customer.applied.selectedShippingAddressId"
                                    />
                                </v-field>

                                <label
                                    class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                    :for="`shipping_address_id_${address.id}`"
                                >
                                </label>

                                <!-- Edit Address Button -->
                                <span
                                    class="icon-edit absolute ltr:right-14 rtl:left-14 top-5 text-2xl cursor-pointer"
                                    @click="openUpdateOrCreateCustomerAddressForm(address, 'shipping')"
                                >
                                </span>

                                <!-- Details -->
                                <label
                                    :for="`shipping_address_id_${address.id}`"
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

                            <!-- Add New Address Button -->
                            <div
                                class="flex justify-center items-center max-w-[414px] p-5 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap cursor-pointer"
                                @click="openUpdateOrCreateCustomerAddressForm({}, 'shipping')"
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

                        <!-- Error Message Block -->
                        <v-error-message
                            class="text-red-500 text-xs italic"
                            name="shipping_address_id"
                        >
                        </v-error-message>

                        <!-- Proceed Button -->
                        <div
                            class="flex justify-end mt-4"
                            v-if="customerBillingAddresses.length && customerShippingAddresses.length"
                        >
                            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.proceed_button.before') !!}

                            <x-shop::button
                                class="primary-button py-3 px-11 rounded-2xl"
                                :title="trans('shop::app.checkout.onepage.addresses.shipping.proceed')"
                                :loading="false"
                                v-if="! isLoading"
                            />

                            <x-shop::button
                                class="primary-button py-3 px-11 rounded-2xl"
                                :title="trans('shop::app.checkout.onepage.addresses.shipping.proceed')"
                                :loading="true"
                                :disabled="true"
                                v-else
                            />

                            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.proceed_button.after') !!}
                        </div>
                    </form>
                </x-shop::form>

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.form.after') !!}
            </x-slot>
        </x-shop::accordion>

        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.accordion.after') !!}
    </template>

    <!-- Shipping Address Form -->
    <template v-else>
        <x-shop::accordion class="!border-b-0">
            <x-slot:header class="!p-0">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-medium max-sm:text-xl">
                        @lang('shop::app.checkout.onepage.addresses.shipping.shipping-address')
                    </h2>
                </div>
            </x-slot>

            <x-slot:content class="!p-0 mt-8">
                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.back_button.before') !!}

                <!-- Back Button -->
                <div>
                    <a
                        class="flex justify-end"
                        href="javascript:void(0)"
                        @click="closeUpdateOrCreateCustomerAddressForm('shipping')"
                    >
                        <span class="icon-arrow-left text-2xl"></span>

                        <span>@lang('shop::app.checkout.onepage.addresses.shipping.back')</span>
                    </a>
                </div>

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.back_button.after') !!}

                <!-- Shipping Address Form -->
                <x-shop::form
                    v-slot="{ meta, errors, values, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, updateOrCreateCustomerAddress)">
                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.form.before') !!}

                        <!-- Address ID -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.control
                                type="hidden"
                                name="type"
                                value="shipping"
                            />

                            <x-shop::form.control-group.control
                                type="hidden"
                                name="shipping.id"
                                ::value="customer.updateOrCreateShippingAddress.params?.id"
                            />
                        </x-shop::form.control-group>

                        <!-- Company Name -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label>
                                @lang('shop::app.checkout.onepage.addresses.shipping.company-name')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping.company_name"
                                ::value="customer.updateOrCreateShippingAddress.params?.companyName"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.company-name')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.company-name')"
                            />

                            <x-shop::form.control-group.error control-name="shipping.company_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.company_name.after') !!}

                        <div class="grid grid-cols-2 gap-x-5">
                            <!-- First Name -->
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-0 required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.first-name')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping.first_name"
                                    ::value="customer.updateOrCreateShippingAddress.params?.firstName"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.first-name')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.first-name')"
                                />

                                <x-shop::form.control-group.error control-name="shipping.first_name" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.first_name.after') !!}

                            <!-- Last Name -->
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-0 required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.last-name')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping.last_name"
                                    ::value="customer.updateOrCreateShippingAddress.params?.lastName"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.last-name')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.last-name')"
                                />

                                <x-shop::form.control-group.error control-name="shipping.last_name" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.last_name.after') !!}
                        </div>

                        <!-- Email -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.email')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="email"
                                name="shipping.email"
                                ::value="customer.updateOrCreateShippingAddress.params?.email"
                                rules="required|email"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.email')"
                                placeholder="email@example.com"
                            />

                            <x-shop::form.control-group.error control-name="shipping.email" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.email.after') !!}

                        <!-- Street Address -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.street-address')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping.address1.[0]"
                                ::value="customer.updateOrCreateShippingAddress.params?.address1"
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

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.address1.after') !!}

                        <div class="grid grid-cols-2 gap-x-5">
                            <!-- Country -->
                            <x-shop::form.control-group class="!mb-4">
                                <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.country')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="select"
                                    name="shipping.country"
                                    ::value="customer.updateOrCreateShippingAddress.params?.country"
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

                            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.country.after') !!}

                            <!-- State -->
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} !mt-0">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.state')
                                </x-shop::form.control-group.label>

                                <template v-if="haveStates(values.shipping?.country)">
                                    <x-shop::form.control-group.control
                                        type="select"
                                        name="shipping.state"
                                        rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                        :label="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                        :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                    >
                                        <option value="">
                                            @lang('shop::app.checkout.onepage.addresses.shipping.select-state')
                                        </option>

                                        <option
                                            v-for='(state, index) in states[values.shipping?.country]'
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </x-shop::form.control-group.control>
                                </template>

                                <template v-else>
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="shipping.state"
                                        ::value="customer.updateOrCreateShippingAddress.params?.state"
                                        rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                        :label="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                        :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.state')"
                                    />
                                </template>

                                <x-shop::form.control-group.error control-name="shipping.state" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.state.after') !!}
                        </div>

                        <div class="grid grid-cols-2 gap-x-5">
                            <!-- City -->
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="!mt-0 required">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.city')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping.city"
                                    ::value="customer.updateOrCreateShippingAddress.params?.city"
                                    rules="required"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.city')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.city')"
                                />

                                <x-shop::form.control-group.error control-name="shipping.city" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.city.after') !!}

                            <!-- Postcode -->
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} !mt-0">
                                    @lang('shop::app.checkout.onepage.addresses.shipping.postcode')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    name="shipping.postcode"
                                    ::value="customer.updateOrCreateShippingAddress.params?.postcode"
                                    rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}"
                                    :label="trans('shop::app.checkout.onepage.addresses.shipping.postcode')"
                                    :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.postcode')"
                                />

                                <x-shop::form.control-group.error control-name="shipping.postcode" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.postcode.after') !!}
                        </div>

                        <!-- Phone Number -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="!mt-0 required">
                                @lang('shop::app.checkout.onepage.addresses.shipping.telephone')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="shipping.phone"
                                ::value="customer.updateOrCreateShippingAddress.params?.phone"
                                rules="required|numeric"
                                :label="trans('shop::app.checkout.onepage.addresses.shipping.telephone')"
                                :placeholder="trans('shop::app.checkout.onepage.addresses.shipping.telephone')"
                            />

                            <x-shop::form.control-group.error control-name="shipping.phone" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.phone.after') !!}

                        <!-- Checkbox For Save Address -->
                        <div
                            class="flex gap-x-1.5 items-center mt-5 text-sm text-[#6E6E6E] select-none"
                            v-if="! (customer.updateOrCreateShippingAddress.params.id > -1)"
                        >
                            <v-field
                                type="checkbox"
                                name="shipping.save_address"
                                v-slot="{ field }"
                                value="1"
                            >
                                <input
                                    type="checkbox"
                                    name="shipping.save_address"
                                    v-bind="field"
                                    id="shipping.save_address"
                                    class="sr-only peer"
                                />
                            </v-field>

                            <label
                                class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                for="shipping.save_address"
                            >
                            </label>

                            <label
                                for="shipping.save_address"
                                class="cursor-pointer"
                            >
                                @lang('shop::app.checkout.onepage.addresses.shipping.save-address')
                            </label>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end mt-4">
                            <x-shop::button
                                class="primary-button py-3 px-11 rounded-2xl"
                                :title="trans('shop::app.checkout.onepage.addresses.shipping.save')"
                                :loading="false"
                                v-if="! isLoading"
                            />

                            <x-shop::button
                                class="primary-button py-3 px-11 rounded-2xl"
                                :title="trans('shop::app.checkout.onepage.addresses.shipping.save')"
                                :loading="true"
                                :disabled="true"
                                v-else
                            />
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.form.after') !!}
                    </form>
                </x-shop::form>

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.update_or_create.after') !!}
            </x-slot>
        </x-shop::accordion>
    </template>
</div>
