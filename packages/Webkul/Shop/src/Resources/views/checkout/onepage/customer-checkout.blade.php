<div class="control-group" id="password" :class="[errors.has('address-form.password') ? 'has-error' : '']">
    <label for="password" class="required">
        {{ __('shop::app.checkout.onepage.password') }}
    </label>

    <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password" v-model="address.billing.password" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.password') }}&quot;"/>

    <span class="control-error" v-if="errors.has('address-form.password')">
        @{{ errors.first('address-form.password') }}
    </span>
</div>

<div class="control-group" id="login-and-forgot-btn" style="display:inline-block;">
    <div class="forgot-password-link">
        <a href="{{ route('customer.forgot-password.create') }}">{{ __('shop::app.customer.login-form.forgot_pass') }}</a>
        <div class="mt-10">
            @if (Cookie::has('enable-resend'))
                @if (Cookie::get('enable-resend') == true)
                    <a href="{{ route('customer.resend.verification-email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
                @endif
            @endif
        </div>
    </div>
    <input type='button' id="" class="btn btn-primary btn-lg btn-login" value="{{ __('shop::app.customer.login-form.button_title') }}" />
</div>