<div class="col-12 form-field" id="password" v-if="is_customer_exist">
    <label for="password">{{ __('shop::app.checkout.onepage.password') }}</label>

    <input
        id="password"
        type="password"
        class="control"
        name="password"
        v-model="address.billing.password" />

    <div class="forgot-password-link mt-4 mb-4">
        <a href="{{ route('shop.customer.forgot_password.create') }}">{{ __('shop::app.customer.login-form.forgot_pass') }}</a>

        <div class="mt-10">
            @if (
                Cookie::has('enable-resend')
                && Cookie::get('enable-resend') == true
            )
                <a href="{{ route('shop.customer.resend.verification_email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
            @endif
        </div>
    </div>

    <button type='button' id="" class="theme-btn" @click="loginCustomer">
        {{ __('shop::app.customer.login-form.button_title') }}
    </button>
</div>