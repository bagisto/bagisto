@php
    $isCustomer = auth()->guard('customer')->check();
@endphp

    @if (isset($shipping) && $shipping)
        <div :class="`col-12 form-field ${errors.has('address-form.shipping[first_name]') ? 'has-error' : ''}`">
            <label for="shipping[first_name]" class="mandatory">
                {{ __('shop::app.checkout.onepage.first-name') }}
            </label>

            <input
                type="text"
                class="control"
                v-validate="'required'"
                id="shipping[first_name]"
                name="shipping[first_name]"
                v-model="address.shipping.first_name"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[first_name]')">
                @{{ errors.first('address-form.shipping[first_name]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.shipping[last_name]') ? 'has-error' : ''}`">
            <label for="shipping[last_name]" class="mandatory">
                {{ __('shop::app.checkout.onepage.last-name') }}
            </label>

            <input
                type="text"
                class="control"
                v-validate="'required'"
                id="shipping[last_name]"
                name="shipping[last_name]"
                v-model="address.shipping.last_name"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[last_name]')">
                @{{ errors.first('address-form.shipping[last_name]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.shipping[email]') ? 'has-error' : ''}`">
            <label for="shipping[email]" class="mandatory">
                {{ __('shop::app.checkout.onepage.email') }}
            </label>

            <input
                type="text"
                class="control"
                id="shipping[email]"
                name="shipping[email]"
                v-validate="'required|email'"
                v-model="address.shipping.email"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[email]')">
                @{{ errors.first('address-form.shipping[email]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.shipping[address1][]') ? 'has-error' : ''}`">
            <label for="shipping_address_0" class="mandatory">
                {{ __('shop::app.checkout.onepage.address1') }}
            </label>

            <input
                type="text"
                class="control"
                v-validate="'required'"
                id="shipping_address_0"
                name="shipping[address1][]"
                v-model="address.shipping.address1[0]"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[address1][]')">
                @{{ errors.first('address-form.shipping[address1][]') }}
            </span>
        </div>

        @if (
            core()->getConfigData('customer.settings.address.street_lines')
            && core()->getConfigData('customer.settings.address.street_lines') > 1
        )
            <div class="col-12 form-field">
                @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                    <input
                        type="text"
                        class="control"
                        id="shipping_address_{{ $i }}"
                        name="shipping[address1][{{ $i }}]"
                        v-model="address.shipping.address1[{{$i}}]" />
                @endfor
            </div>
        @endif

        <div :class="`col-12 form-field ${errors.has('address-form.shipping[city]') ? 'has-error' : ''}`">
            <label for="shipping[city]" class="mandatory">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input
                type="text"
                class="control"
                id="shipping[city]"
                name="shipping[city]"
                v-validate="'required'"
                v-model="address.shipping.city"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[city]')">
                @{{ errors.first('address-form.shipping[city]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.shipping[country]') ? 'has-error' : ''}`">
            <label for="shipping[country]" class="mandatory">
                {{ __('shop::app.checkout.onepage.country') }}
            </label>

            <select
                type="text"
                id="shipping[country]"
                v-validate="'required'"
                name="shipping[country]"
                class="control styled-select"
                v-model="address.shipping.country"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">

                <option value=""></option>

                @foreach (core()->countries() as $country)
                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </select>
            <i class="select-icon rango-arrow-down"></i>

            <span class="control-error" v-if="errors.has('address-form.shipping[country]')">
                @{{ errors.first('address-form.shipping[country]') }}
            </span>
        </div>


        <div :class="`col-12 form-field ${errors.has('address-form.shipping[state]') ? 'has-error' : ''}`">
            <label for="shipping[state]" class="mandatory">
                {{ __('shop::app.checkout.onepage.state') }}
            </label>


            <input
                type="text"
                class="control"
                id="shipping[state]"
                name="shipping[state]"
                v-validate="'required'"
                v-if="!haveStates('shipping')"
                v-model="address.shipping.state"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;" />

            <select
                id="shipping[state]"
                name="shipping[state]"
                v-validate="'required'"
                class="control styled-select"
                v-if="haveStates('shipping')"
                v-model="address.shipping.state"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;">

                <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                <option v-for='(state, index) in countryStates[address.shipping.country]' :value="state.code">
                    @{{ state.default_name }}
                </option>
            </select>
            <i v-if="haveStates('shipping')" class="select-icon rango-arrow-down"></i>

            <span class="control-error" v-if="errors.has('address-form.shipping[state]')">
                @{{ errors.first('address-form.shipping[state]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.shipping[postcode]') ? 'has-error' : ''}`">
            <label for="shipping[postcode]" class="mandatory">
                {{ __('shop::app.checkout.onepage.postcode') }}
            </label>

            <input
                type="text"
                class="control"
                id="shipping[postcode]"
                v-validate="'required'"
                name="shipping[postcode]"
                v-model="address.shipping.postcode"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[postcode]')">
                @{{ errors.first('address-form.shipping[postcode]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.shipping[phone]') ? 'has-error' : ''}`">
            <label for="shipping[phone]" class="mandatory">
                {{ __('shop::app.checkout.onepage.phone') }}
            </label>

            <input
                type="text"
                class="control"
                id="shipping[phone]"
                name="shipping[phone]"
                v-validate="'required'"
                v-model="address.shipping.phone"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.shipping[phone]')">
                @{{ errors.first('address-form.shipping[phone]') }}
            </span>
        </div>

        @auth('customer')
            <div class="mb10">
                <span class="checkbox fs16 display-inbl no-margin">
                    <input
                        class="ml0"
                        type="checkbox"
                        id="shipping[save_as_address]"
                        name="shipping[save_as_address]"
                        v-model="address.shipping.save_as_address"/>

                    <span class="ml-5">
                        {{ __('shop::app.checkout.onepage.save_as_address') }}
                    </span>
                </span>
            </div>
        @endauth

    @elseif (isset($billing) && $billing)
        <div :class="`col-12 form-field ${errors.has('address-form.billing[email]') ? 'has-error' : ''}`">
            <label for="billing[email]" class="mandatory">
                {{ __('shop::app.checkout.onepage.email') }}
            </label>

            <input
                type="text"
                class="control"
                id="billing[email]"
                name="billing[email]"
                @blur="isCustomerExist"
                v-validate="'required|email'"
                v-model="address.billing.email"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[email]')">
                @{{ errors.first('address-form.billing[email]') }}
            </span>
        </div>

        {{--  for customer login checkout   --}}
        @if (! $isCustomer)
            @include('shop::checkout.onepage.customer-checkout')
        @endif

        <div :class="`col-12 form-field ${errors.has('address-form.billing[first_name]') ? 'has-error' : ''}`">
            <label for="billing[first_name]" class="mandatory">
                {{ __('shop::app.checkout.onepage.first-name') }}
            </label>

            <input
                type="text"
                class="control"
                v-validate="'required'"
                id="billing[first_name]"
                name="billing[first_name]"
                v-model="address.billing.first_name"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[first_name]')">
                @{{ errors.first('address-form.billing[first_name]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.billing[last_name]') ? 'has-error' : ''}`">
            <label for="billing[last_name]" class="mandatory">
                {{ __('shop::app.checkout.onepage.last-name') }}
            </label>

            <input
                type="text"
                v-validate="'required'"
                class="control"
                id="billing[last_name]"
                name="billing[last_name]"
                v-model="address.billing.last_name"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[last_name]')">
                @{{ errors.first('address-form.billing[last_name]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.billing[address1][]') ? 'has-error' : ''}`">
            <label for="billing_address_0" class="mandatory">
                {{ __('shop::app.checkout.onepage.address1') }}
            </label>

            <input
                type="text"
                class="control"
                v-validate="'required'"
                id="billing_address_0"
                name="billing[address1][]"
                v-model="address.billing.address1[0]"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[address1][]')">
                @{{ errors.first('address-form.billing[address1][]') }}
            </span>
        </div>

        @if (
            core()->getConfigData('customer.settings.address.street_lines')
            && core()->getConfigData('customer.settings.address.street_lines') > 1
        )
            <div class="col-12 form-field" style="margin-top: -25px;">
                @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                    <input
                        type="text"
                        class="control"
                        id="billing_address_{{ $i }}"
                        name="billing[address1][{{ $i }}]"
                        v-model="address.billing.address1[{{$i}}]" />
                @endfor
            </div>
        @endif

        <div :class="`col-12 form-field ${errors.has('address-form.billing[city]') ? 'has-error' : ''}`">
            <label for="billing[city]" class="mandatory">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input
                type="text"
                class="control"
                id="billing[city]"
                name="billing[city]"
                v-validate="'required'"
                v-model="address.billing.city"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[city]')">
                @{{ errors.first('address-form.billing[city]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.billing[country]') ? 'has-error' : ''}`">
            <label for="billing[country]" class="mandatory">
                {{ __('shop::app.checkout.onepage.country') }}
            </label>

            <select
                type="text"
                id="billing[country]"
                name="billing[country]"
                v-validate="'required'"
                class="control styled-select"
                v-model="address.billing.country"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">

                <option value=""></option>

                @foreach (core()->countries() as $country)
                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </select>
            <i class="select-icon rango-arrow-down"></i>

            <span class="control-error" v-if="errors.has('address-form.billing[country]')">
                @{{ errors.first('address-form.billing[country]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.billing[state]') ? 'has-error' : ''}`">
            <label for="billing[state]" class="mandatory">
                {{ __('shop::app.checkout.onepage.state') }}
            </label>

            <input
                type="text"
                class="control"
                id="billing[state]"
                name="billing[state]"
                v-validate="'required'"
                v-if="!haveStates('billing')"
                v-model="address.billing.state"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;" />

            <select
                id="billing[state]"
                name="billing[state]"
                v-validate="'required'"
                v-if="haveStates('billing')"
                class="control styled-select"
                v-model="address.billing.state"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;">

                <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                <option v-for='(state, index) in countryStates[address.billing.country]' :value="state.code">
                    @{{ state.default_name }}
                </option>
            </select>
            <div class="select-icon-container" v-if="haveStates('billing')">
                <i class="select-icon rango-arrow-down"></i>
            </div>

            <span class="control-error" v-if="errors.has('address-form.billing[state]')">
                @{{ errors.first('address-form.billing[state]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.billing[postcode]') ? 'has-error' : ''}`">
            <label for="billing[postcode]" class="mandatory">
                {{ __('shop::app.checkout.onepage.postcode') }}
            </label>

            <input
                type="text"
                class="control"
                id="billing[postcode]"
                v-validate="'required'"
                name="billing[postcode]"
                v-model="address.billing.postcode"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[postcode]')">
                @{{ errors.first('address-form.billing[postcode]') }}
            </span>
        </div>

        <div :class="`col-12 form-field ${errors.has('address-form.billing[phone]') ? 'has-error' : ''}`">
            <label for="billing[phone]" class="mandatory">
                {{ __('shop::app.checkout.onepage.phone') }}
            </label>

            <input
                type="text"
                class="control"
                id="billing[phone]"
                name="billing[phone]"
                v-validate="'required'"
                v-model="address.billing.phone"
                @change="validateForm('address-form')"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;" />

            <span class="control-error" v-if="errors.has('address-form.billing[phone]')">
                @{{ errors.first('address-form.billing[phone]') }}
            </span>
        </div>

        @if ($cart->haveStockableItems())
            <div class="mb10">
                <span class="checkbox fs16 display-inbl no-margin">
                    <input
                        class="ml0"
                        type="checkbox"
                        @change="validateForm('address-form')"
                        id="billing[use_for_shipping]"
                        name="billing[use_for_shipping]"
                        v-model="address.billing.use_for_shipping" />

                    <span class="ml-5">
                        {{ __('shop::app.checkout.onepage.use_for_shipping') }}
                    </span>
                </span>
            </div>
        @endif

        @auth('customer')
            <div class="mb10">
                <span class="checkbox fs16 display-inbl no-margin">
                    <input
                        class="ml0"
                        type="checkbox"
                        id="billing[save_as_address]"
                        name="billing[save_as_address]" v-model="address.billing.save_as_address"/>

                    <span class="ml-5">
                        {{ __('shop::app.checkout.onepage.save_as_address') }}
                    </span>
                </span>
            </div>
            @php
            @endphp
        @endauth
    @endif