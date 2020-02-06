<div class="col-12 form-field" id="password" v-if="is_customer_exist">
    <label for="password">{{ __('shop::app.checkout.onepage.password') }}</label>

    <input
        id="password"
        type="password"
        class="control"
        name="password"
        v-model="address.billing.password" />

    <div class="forgot-password-link">
        <a href="{{ route('customer.forgot-password.create') }}">{{ __('shop::app.customer.login-form.forgot_pass') }}</a>

        <div class="mt-10">
            @if (Cookie::has('enable-resend') && Cookie::get('enable-resend') == true)
                <a href="{{ route('customer.resend.verification-email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
            @endif
        </div>
    </div>

    <button type='button' id="" class="theme-btn" @click="loginCustomer">
        {{ __('shop::app.customer.login-form.button_title') }}
    </button>
</div>