<form data-vv-scope="address-form">
    <div class="form-container" v-if="! this.new_billing_address">
        <div class="form-header mb-30">
            <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.billing-address') }}</span>

            <a class="btn btn-lg btn-primary" @click="newBillingAddress()">
                {{ __('shop::app.checkout.onepage.new-address') }}
            </a>
        </div>

        <div class="address-holder">
            <div class="address-card" v-for='(addresses, index) in this.allAddress'>
                <div class="checkout-address-content" style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <label class="radio-container" style="float: right; width: 10%;">
                        <input type="radio" v-validate="'required'" id="billing[address_id]" name="billing[address_id]" :value="addresses.id" v-model="address.billing.address_id" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.billing-address') }}&quot;">

                        <span class="checkmark"></span>
                    </label>

                    <ul class="address-card-list" style="float: right; width: 85%;">
                        <li class="mb-10">
                            <b v-text="`${allAddress.first_name} ${allAddress.last_name}`"></b>
                        </li>

                        <li
                            class="mb-5"
                            v-text="addresses.company_name"
                            v-if="addresses.company_name != ''">
                        </li>

                        <li class="mb-5" v-text="addresses.address1"></li>

                        <li class="mb-5" v-text="addresses.city"></li>

                        <li class="mb-5" v-text="addresses.state"></li>

                        <li class="mb-15">
                            <span v-text="addresses.country" v-if="addresses.country"></span>
                            <span v-text="addresses.postcode" v-if="addresses.postcode"></span>
                        </li>

                        <li>
                            <b>{{ __('shop::app.customer.account.address.index.contact') }}</b> :
                            <span v-text="addresses.phone"></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="message"></div>

            <div class="control-group" :class="[errors.has('address-form.billing[address_id]') ? 'has-error' : '']">
                <span
                    class="control-error"
                    v-text="errors.first('address-form.billing[address_id]')"
                    v-if="errors.has('address-form.billing[address_id]')">
                </span>
            </div>
        </div>

        @if ($cart->haveStockableItems())
            <div class="control-group mt-5">
                <span class="checkbox">
                    <input type="checkbox" id="billing[use_for_shipping]" name="billing[use_for_shipping]" v-model="address.billing.use_for_shipping"/>
                        <label class="checkbox-view" for="billing[use_for_shipping]"></label>

                        {{ __('shop::app.checkout.onepage.use_for_shipping') }}
                </span>
            </div>
        @endif
    </div>

    <div class="form-container" v-if="this.new_billing_address">
        <div class="form-header">
            <h1>{{ __('shop::app.checkout.onepage.billing-address') }}</h1>

            @auth('customer')
                @if(count(auth('customer')->user()->addresses))
                    <a class="btn btn-lg btn-primary" @click="backToSavedBillingAddress()">
                        {{ __('shop::app.checkout.onepage.back') }}
                    </a>
                @endif
            @endauth
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[email]') ? 'has-error' : '']">
            <label for="billing[email]" class="required">
                {{ __('shop::app.checkout.onepage.email') }}
            </label>

            <input
                class="control"
                id="billing[email]"
                type="text"
                name="billing[email]"
                v-model="address.billing.email"
                v-validate="'required|email'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;"
                @blur="isCustomerExist"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[email]')"
                v-if="errors.has('address-form.billing[email]')">
            </span>
        </div>

        @if (! auth()->guard('customer')->check())
            @include('shop::checkout.onepage.customer-checkout')
        @endif

        <div class="control-group" :class="[errors.has('address-form.billing[company_name]') ? 'has-error' : '']">
            <label for="billing[company_name]">
                {{ __('shop::app.checkout.onepage.company-name') }}
            </label>

            <input
                class="control"
                id="billing[company_name]"
                type="text"
                name="billing[company_name]"
                v-model="address.billing.company_name"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.company-name') }}&quot;"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[company_name]')"
                v-if="errors.has('address-form.billing[company_name]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[first_name]') ? 'has-error' : '']">
            <label for="billing[first_name]" class="required">
                {{ __('shop::app.checkout.onepage.first-name') }}
            </label>

            <input
                class="control"
                id="billing[first_name]"
                type="text"
                name="billing[first_name]"
                v-model="address.billing.first_name"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[first_name]')"
                v-if="errors.has('address-form.billing[first_name]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[last_name]') ? 'has-error' : '']">
            <label for="billing[last_name]" class="required">
                {{ __('shop::app.checkout.onepage.last-name') }}
            </label>

            <input
                class="control"
                id="billing[last_name]"
                type="text"
                name="billing[last_name]"
                v-model="address.billing.last_name"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[last_name]')"
                v-if="errors.has('address-form.billing[last_name]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[address1][]') ? 'has-error' : '']">
            <label for="billing_address_0" class="required">
                {{ __('shop::app.checkout.onepage.address1') }}
            </label>

            <input
                class="control"
                id="billing_address_0"
                type="text"
                name="billing[address1][]"
                v-model="address.billing.address1[0]"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[address1][]')"
                v-if="errors.has('address-form.billing[address1][]')">
            </span>
        </div>

        @if (core()->getConfigData('customer.settings.address.street_lines') && core()->getConfigData('customer.settings.address.street_lines') > 1)
            <div class="control-group" style="margin-top: -25px;">
                @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                    <input
                        class="control"
                        id="billing_address_{{ $i }}"
                        type="text"
                        name="billing[address1][{{ $i }}]"
                        v-model="address.billing.address1[{{$i}}]">
                @endfor
            </div>
        @endif

        <div class="control-group" :class="[errors.has('address-form.billing[city]') ? 'has-error' : '']">
            <label for="billing[city]" class="required">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input
                class="control"
                id="billing[city]"
                type="text"
                name="billing[city]"
                v-model="address.billing.city"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[city]')"
                v-if="errors.has('address-form.billing[city]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[country]') ? 'has-error' : '']">
            <label for="billing[country]" class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                {{ __('shop::app.checkout.onepage.country') }}
            </label>

            <select
                class="control"
                id="billing[country]"
                type="text"
                name="billing[country]"
                v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                v-model="address.billing.country"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">
                <option value=""></option>

                @foreach (core()->countries() as $country)
                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </select>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[country]')"
                v-if="errors.has('address-form.billing[country]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[state]') ? 'has-error' : '']">
            <label for="billing[state]" class="{{ core()->isStateRequired() ? 'required' : '' }}">
                {{ __('shop::app.checkout.onepage.state') }}
            </label>

            <input
                class="control"
                id="billing[state]"
                name="billing[state]"
                type="text"
                v-model="address.billing.state"
                v-validate="'{{ core()->isStateRequired() ? 'required' : '' }}'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"
                v-if="! haveStates('billing')"/>

            <select
                class="control"
                id="billing[state]"
                name="billing[state]"
                v-model="address.billing.state"
                v-validate=""
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"
                v-if="haveStates('billing')">
                <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                <option v-for='(state, index) in countryStates[address.billing.country]' :value="state.code" v-text="state.default_name"></option>
            </select>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[state]')"
                v-if="errors.has('address-form.billing[state]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[postcode]') ? 'has-error' : '']">
            <label for="billing[postcode]" class="{{ core()->isPostCodeRequired() ? 'required' : '' }}">
                {{ __('shop::app.checkout.onepage.postcode') }}
            </label>

            <input
                class="control"
                id="billing[postcode]"
                type="text"
                name="billing[postcode]"
                v-model="address.billing.postcode"
                v-validate="'{{ core()->isPostCodeRequired() ? 'required' : '' }}'"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[postcode]')"
                v-if="errors.has('address-form.billing[postcode]')">
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[phone]') ? 'has-error' : '']">
            <label for="billing[phone]" class="required">
                {{ __('shop::app.checkout.onepage.phone') }}
            </label>

            <input
                class="control"
                id="billing[phone]"
                type="text"
                name="billing[phone]"
                v-validate="'required|numeric'"
                v-model="address.billing.phone"
                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"/>

            <span
                class="control-error"
                v-text="errors.first('address-form.billing[phone]')"
                v-if="errors.has('address-form.billing[phone]')"></span>
        </div>

        @if ($cart->haveStockableItems())
            <div class="control-group">
                <span class="checkbox">
                    <input
                        id="billing[use_for_shipping]"
                        type="checkbox"
                        name="billing[use_for_shipping]"
                        v-model="address.billing.use_for_shipping"/>

                    <label class="checkbox-view" for="billing[use_for_shipping]"></label>

                    {{ __('shop::app.checkout.onepage.use_for_shipping') }}
                </span>
            </div>
        @endif

        @auth('customer')
            <div class="control-group">
                <span class="checkbox">
                    <input
                        id="billing[save_as_address]"
                        type="checkbox"
                        name="billing[save_as_address]"
                        v-model="address.billing.save_as_address"/>

                    <label class="checkbox-view" for="billing[save_as_address]"></label>

                    {{ __('shop::app.checkout.onepage.save_as_address') }}
                </span>
            </div>
        @endauth
    </div>

    @if ($cart->haveStockableItems())
        <div class="form-container" v-if="! address.billing.use_for_shipping && ! this.new_shipping_address">
            <div class="form-header mb-30">
                <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.shipping-address') }}</span>

                <a class="btn btn-lg btn-primary" @click="newShippingAddress()">
                    {{ __('shop::app.checkout.onepage.new-address') }}
                </a>
            </div>

            <div class="address-holder">
                <div class="address-card" v-for='(addresses, index) in this.allAddress'>
                    <div class="checkout-address-content" style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                        <label class="radio-container" style="float: right; width: 10%;">
                            <input
                                id="shipping[address_id]"
                                type="radio"
                                name="shipping[address_id]"
                                :value="addresses.id"
                                v-model="address.shipping.address_id"
                                v-validate="'required'"
                                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.shipping-address') }}&quot;">

                            <span class="checkmark"></span>
                        </label>

                        <ul class="address-card-list" style="float: right; width: 85%;">
                            <li class="mb-10">
                                <b v-text="`${addresses.first_name} ${addresses.last_name}`"></b>
                            </li>

                            <li
                                class="mb-5"
                                v-text="addresses.company_name"
                                v-if="addresses.company_name != ''">
                            </li>

                            <li class="mb-5" v-text="addresses.address1"></li>

                            <li class="mb-5" v-text="addresses.city"></li>

                            <li class="mb-5" v-text="addresses.state"></li>

                            <li class="mb-15">
                                <span v-text="addresses.country" v-if="addresses.country"></span>
                                <span v-text="addresses.postcode" v-if="addresses.postcode"></span>
                            </li>

                            <li>
                                <b>{{ __('shop::app.customer.account.address.index.contact') }}</b> :
                                <span v-text="addresses.phone"></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="control-group" :class="[errors.has('address-form.shipping[address_id]') ? 'has-error' : '']">
                    <span
                        class="control-error"
                        v-text="errors.first('address-form.shipping[address_id]')"
                        v-if="errors.has('address-form.shipping[address_id]')">
                    </span>
                </div>
            </div>
        </div>

        <div class="form-container" v-if="! address.billing.use_for_shipping && this.new_shipping_address">
            <div class="form-header">
                <h1>{{ __('shop::app.checkout.onepage.shipping-address') }}</h1>

                @auth('customer')
                    @if(count(auth('customer')->user()->addresses))
                        <a class="btn btn-lg btn-primary" @click="backToSavedShippingAddress()">
                            {{ __('shop::app.checkout.onepage.back') }}
                        </a>
                    @endif
                @endauth
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[first_name]') ? 'has-error' : '']">
                <label for="shipping[first_name]" class="required">
                    {{ __('shop::app.checkout.onepage.first-name') }}
                </label>

                <input
                    class="control"
                    id="shipping[first_name]"
                    type="text"
                    name="shipping[first_name]"
                    v-model="address.shipping.first_name"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"/>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[first_name]')"
                    v-if="errors.has('address-form.shipping[first_name]')">
                </span>
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[last_name]') ? 'has-error' : '']">
                <label for="shipping[last_name]" class="required">
                    {{ __('shop::app.checkout.onepage.last-name') }}
                </label>

                <input
                    class="control"
                    id="shipping[last_name]"
                    type="text"
                    name="shipping[last_name]"
                    v-model="address.shipping.last_name"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"/>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[last_name]')"
                    v-if="errors.has('address-form.shipping[last_name]')">
                </span>
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[email]') ? 'has-error' : '']">
                <label for="shipping[email]" class="required">
                    {{ __('shop::app.checkout.onepage.email') }}
                </label>

                <input
                    class="control"
                    id="shipping[email]"
                    type="text"
                    name="shipping[email]"
                    v-validate="'required|email'"
                    v-model="address.shipping.email"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;"/>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[email]')"
                    v-if="errors.has('address-form.shipping[email]')">
                </span>
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[address1][]') ? 'has-error' : '']">
                <label for="shipping_address_0" class="required">
                    {{ __('shop::app.checkout.onepage.address1') }}
                </label>

                <input
                    class="control"
                    id="shipping_address_0"
                    type="text"
                    name="shipping[address1][]"
                    v-model="address.shipping.address1[0]"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;"/>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[address1][]')"
                    v-if="errors.has('address-form.shipping[address1][]')">
                </span>
            </div>

            @if (core()->getConfigData('customer.settings.address.street_lines') && core()->getConfigData('customer.settings.address.street_lines') > 1)
                <div class="control-group" style="margin-top: -25px;">
                    @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                        <input
                            class="control"
                            id="shipping_address_{{ $i }}"
                            type="text"
                            name="shipping[address1][{{ $i }}]"
                            v-model="address.shipping.address1[{{$i}}]">
                    @endfor
                </div>
            @endif

            <div class="control-group" :class="[errors.has('address-form.shipping[city]') ? 'has-error' : '']">
                <label for="shipping[city]" class="required">
                    {{ __('shop::app.checkout.onepage.city') }}
                </label>

                <input
                    class="control"
                    id="shipping[city]"
                    type="text"
                    name="shipping[city]"
                    v-model="address.shipping.city"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[city]')"
                    v-if="errors.has('address-form.shipping[city]')">
                </span>
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[country]') ? 'has-error' : '']">
                <label for="shipping[country]" class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                    {{ __('shop::app.checkout.onepage.country') }}
                </label>

                <select
                    class="control"
                    id="shipping[country]"
                    type="text"
                    name="shipping[country]"
                    v-model="address.shipping.country"
                    v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">
                    <option value=""></option>

                    @foreach (core()->countries() as $country)
                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                    @endforeach
                </select>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[country]')"
                    v-if="errors.has('address-form.shipping[country]')">
                </span>
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[state]') ? 'has-error' : '']">
                <label for="shipping[state]" class="{{ core()->isStateRequired() ? 'required' : '' }}">
                    {{ __('shop::app.checkout.onepage.state') }}
                </label>

                <input
                    class="control"
                    id="shipping[state]"
                    type="text"
                    name="shipping[state]"
                    v-model="address.shipping.state"
                    v-validate="'{{ core()->isStateRequired() ? 'required' : '' }}'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"
                    v-if="! haveStates('shipping')"/>

                <select
                    class="control" id="shipping[state]"
                    name="shipping[state]"
                    v-model="address.shipping.state"
                    v-validate=""
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"
                    v-if="haveStates('shipping')">
                    <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                    <option v-for='(state, index) in countryStates[address.shipping.country]' :value="state.code">
                        @{{ state.default_name }}
                    </option>
                </select>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[state]')"
                    v-if="errors.has('address-form.shipping[state]')">
                </span>
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[postcode]') ? 'has-error' : '']">
                <label for="shipping[postcode]" class="{{ core()->isPostCodeRequired() ? 'required' : '' }}">
                    {{ __('shop::app.checkout.onepage.postcode') }}
                </label>

                <input
                    class="control"
                    id="shipping[postcode]"
                    type="text"
                    name="shipping[postcode]"
                    v-model="address.shipping.postcode"
                    v-validate="'{{ core()->isPostCodeRequired() ? 'required' : '' }}'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;"/>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[postcode]')"
                    v-if="errors.has('address-form.shipping[postcode]')">
                </span>
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[phone]') ? 'has-error' : '']">
                <label for="shipping[phone]" class="required">
                    {{ __('shop::app.checkout.onepage.phone') }}
                </label>

                <input
                    class="control"
                    id="shipping[phone]"
                    type="text"
                    name="shipping[phone]"
                    v-model="address.shipping.phone"
                    v-validate="'required|numeric'"
                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"/>

                <span
                    class="control-error"
                    v-text="errors.first('address-form.shipping[phone]')"
                    v-if="errors.has('address-form.shipping[phone]')">
                </span>
            </div>

            @auth('customer')
                <div class="control-group">
                    <span class="checkbox">
                        <input
                            id="shipping[save_as_address]"
                            type="checkbox"
                            name="shipping[save_as_address]"
                            v-model="address.shipping.save_as_address"/>

                        <label class="checkbox-view" for="shipping[save_as_address]"></label>

                        {{ __('shop::app.checkout.onepage.save_as_address') }}
                    </span>
                </div>
            @endauth
        </div>
    @endif
</form>
