<form data-vv-scope="address-form">

    <div class="form-container">

        <div class="form-header">
            <h1>{{ __('shop::app.checkout.onepage.billing-address') }}</h1>

            @guest('customer')
                <a href="{{ route('customer.session.index') }}" class="btn btn-lg btn-primary">
                    {{ __('shop::app.checkout.onepage.sign-in') }}
                </a>
            @endguest
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[first_name]') ? 'has-error' : '']">
            <label for="billing[first_name]" class="required">
                {{ __('shop::app.checkout.onepage.first-name') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[first_name]" name="billing[first_name]" v-model="address.billing.first_name" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[first_name]')">
                @{{ errors.first('address-form.billing[first_name]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[last_name]') ? 'has-error' : '']">
            <label for="billing[last_name]" class="required">
                {{ __('shop::app.checkout.onepage.last-name') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[last_name]" name="billing[last_name]" v-model="address.billing.last_name" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[last_name]')">
                @{{ errors.first('address-form.billing[last_name]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[email]') ? 'has-error' : '']">
            <label for="billing[email]" class="required">
                {{ __('shop::app.checkout.onepage.email') }}
            </label>

            <input type="text" v-validate="'required|email'" class="control" id="billing[email]" name="billing[email]" v-model="address.billing.email" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[email]')">
                @{{ errors.first('address-form.billing[email]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[address1]') ? 'has-error' : '']">
            <label for="billing[address1]" class="required">
                {{ __('shop::app.checkout.onepage.address1') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[address1]" name="billing[address1]" v-model="address.billing.address1" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[address1]')">
                @{{ errors.first('address-form.billing[address1]') }}
            </span>
        </div>

        <div class="control-group">
            <label for="billing[address2]">
                {{ __('shop::app.checkout.onepage.address2') }}
            </label>

            <input type="text" class="control" id="billing[address2]" name="billing[address2]" v-model="address.billing.address2"/>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[city]') ? 'has-error' : '']">
            <label for="billing[city]" class="required">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[city]" name="billing[city]" v-model="address.billing.city" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[city]')">
                @{{ errors.first('address-form.billing[city]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[state]') ? 'has-error' : '']">
            <label for="billing[state]" class="required">
                {{ __('shop::app.checkout.onepage.state') }}
            </label>


            <input type="text" v-validate="'required'" class="control" id="billing[state]" name="billing[state]" v-model="address.billing.state" v-if="!haveStates('billing')" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"/>

            <select v-validate="'required'" class="control" id="billing[state]" name="billing[state]" v-model="address.billing.state" v-if="haveStates('billing')" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;">

                <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                <option v-for='(state, index) in countryStates[address.billing.country]' :value="state.code">
                    @{{ state.default_name }}
                </option>

            </select>

            <span class="control-error" v-if="errors.has('address-form.billing[state]')">
                @{{ errors.first('address-form.billing[state]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[postcode]') ? 'has-error' : '']">
            <label for="billing[postcode]" class="required">
                {{ __('shop::app.checkout.onepage.postcode') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[postcode]" name="billing[postcode]" v-model="address.billing.postcode" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[postcode]')">
                @{{ errors.first('address-form.billing[postcode]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[country]') ? 'has-error' : '']">
            <label for="billing[country]" class="required">
                {{ __('shop::app.checkout.onepage.country') }}
            </label>

            <select type="text" v-validate="'required'" class="control" id="billing[country]" name="billing[country]" v-model="address.billing.country" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">
                <option value=""></option>

                @foreach (core()->countries() as $country)

                    <option value="{{ $country->code }}">{{ $country->name }}</option>

                @endforeach
            </select>

            <span class="control-error" v-if="errors.has('address-form.billing[country]')">
                @{{ errors.first('address-form.billing[country]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[phone]') ? 'has-error' : '']">
            <label for="billing[phone]" class="required">
                {{ __('shop::app.checkout.onepage.phone') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[phone]" name="billing[phone]" v-model="address.billing.phone" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[phone]')">
                @{{ errors.first('address-form.billing[phone]') }}
            </span>
        </div>

        <div class="control-group">
            <span class="checkbox">
                <input type="checkbox" id="billing[use_for_shipping]" name="billing[use_for_shipping]" v-model="address.billing.use_for_shipping"/>
                <label class="checkbox-view" for="billing[use_for_shipping]"></label>
                {{ __('shop::app.checkout.onepage.use_for_shipping') }}
            </span>

        </div>
    </div>

    <div class="form-container" v-if="!address.billing.use_for_shipping">

        <div class="form-header">
            <h1>{{ __('shop::app.checkout.onepage.shipping-address') }}</h1>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[first_name]') ? 'has-error' : '']">
            <label for="shipping[first_name]" class="required">
                {{ __('shop::app.checkout.onepage.first-name') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[first_name]" name="shipping[first_name]" v-model="address.shipping.first_name" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[first_name]')">
                @{{ errors.first('address-form.shipping[first_name]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[last_name]') ? 'has-error' : '']">
            <label for="shipping[last_name]" class="required">
                {{ __('shop::app.checkout.onepage.last-name') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[last_name]" name="shipping[last_name]" v-model="address.shipping.last_name" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[last_name]')">
                @{{ errors.first('address-form.shipping[last_name]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[email]') ? 'has-error' : '']">
            <label for="shipping[email]" class="required">
                {{ __('shop::app.checkout.onepage.email') }}
            </label>

            <input type="text" v-validate="'required|email'" class="control" id="shipping[email]" name="shipping[email]" v-model="address.shipping.email" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[email]')">
                @{{ errors.first('address-form.shipping[email]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[address1]') ? 'has-error' : '']">
            <label for="shipping[address1]" class="required">
                {{ __('shop::app.checkout.onepage.address1') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[address1]" name="shipping[address1]" v-model="address.shipping.address1" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[address1]')">
                @{{ errors.first('address-form.shipping[address1]') }}
            </span>
        </div>

        <div class="control-group">
            <label for="shipping[address2]">
                {{ __('shop::app.checkout.onepage.address2') }}
            </label>

            <input type="text" class="control" id="shipping[address2]" name="shipping[address2]" v-model="address.shipping.address2"/>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[city]') ? 'has-error' : '']">
            <label for="shipping[city]" class="required">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[city]" name="shipping[city]" v-model="address.shipping.city" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[city]')">
                @{{ errors.first('address-form.shipping[city]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[state]') ? 'has-error' : '']">
            <label for="shipping[state]" class="required">
                {{ __('shop::app.checkout.onepage.state') }}
            </label>


            <input type="text" v-validate="'required'" class="control" id="shipping[state]" name="shipping[state]" v-model="address.shipping.state" v-if="!haveStates('shipping')" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"/>

            <select v-validate="'required'" class="control" id="shipping[state]" name="shipping[state]" v-model="address.shipping.state" v-if="haveStates('shipping')" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;">

                <option value="">{{ __('shop::app.checkout.onepage.select-state') }}</option>

                <option v-for='(state, index) in countryStates[address.shipping.country]' :value="state.code">
                    @{{ state.default_name }}
                </option>

            </select>

            <span class="control-error" v-if="errors.has('address-form.shipping[state]')">
                @{{ errors.first('address-form.shipping[state]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[postcode]') ? 'has-error' : '']">
            <label for="shipping[postcode]" class="required">
                {{ __('shop::app.checkout.onepage.postcode') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[postcode]" name="shipping[postcode]" v-model="address.shipping.postcode" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[postcode]')">
                @{{ errors.first('address-form.shipping[postcode]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[country]') ? 'has-error' : '']">
            <label for="shipping[country]" class="required">
                {{ __('shop::app.checkout.onepage.country') }}
            </label>

            <select type="text" v-validate="'required'" class="control" id="shipping[country]" name="shipping[country]" v-model="address.shipping.country" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.country') }}&quot;">
                <option value=""></option>

                @foreach (core()->countries() as $country)
                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </select>

            <span class="control-error" v-if="errors.has('address-form.shipping[country]')">
                @{{ errors.first('address-form.shipping[country]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[phone]') ? 'has-error' : '']">
            <label for="shipping[phone]" class="required">
                {{ __('shop::app.checkout.onepage.phone') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[phone]" name="shipping[phone]" v-model="address.shipping.phone" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[phone]')">
                @{{ errors.first('address-form.shipping[phone]') }}
            </span>
        </div>
    </div>

</form>