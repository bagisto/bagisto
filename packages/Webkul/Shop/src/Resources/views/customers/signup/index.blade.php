@extends('shop::store.layouts.master')
@section('content-wrapper')
<div class="content">
    <div class="sign-up-text">
        {{ __('shop::app.customer.signup-text.account_exists') }} - <a href="{{ route('customer.session.index') }}">{{ __('shop::app.customer.signup-text.title') }}</a>
    </div>
    <form method="post" action="{{ route('customer.register.create') }}">
        {{ csrf_field() }}
        <div class="login-form">
            <div class="login-text">{{ __('shop::app.customer.signup-form.title') }}</div>
            <div class="control-group">
                <label for="first_name">{{ __('shop::app.customer.signup-form.firstname') }}</label>
                <input type="text" class="control" name="first_name">
            </div>
            <div class="control-group">
                <label for="last_name">{{ __('shop::app.customer.signup-form.lastname') }}</label>
                <input type="text" class="control" name="last_name">
            </div>
            <div class="control-group">
                <label for="email">{{ __('shop::app.customer.signup-form.email') }}</label>
                <input type="email" class="control" name="email">
            </div>
            <div class="control-group">
                <label for="password">{{ __('shop::app.customer.signup-form.password') }}</label>
                <input type="password" class="control" name="password">
            </div>
            <div class="control-group">
                <label for="confirm_password">{{ __('shop::app.customer.signup-form.confirm_pass') }}</label>
                <input type="password" class="control" name="confirm_password">
            </div>
            <div class="signup-confirm">
                <span class="checkbox">
                    <input type="checkbox" id="checkbox2" name="agreement" required>
                    <label class="checkbox-view" for="checkbox2"></label>
                    <span>{{ __('shop::app.customer.signup-form.agree') }} <a href="">{{ __('shop::app.customer.signup-form.terms') }}</a> & <a href="">{{ __('shop::app.customer.signup-form.conditions') }}</a> {{ __('shop::app.customer.signup-form.using') }}.</span>
                </span>

            </div>
            <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.signup-form.button_title') }}">
        </div>
    </form>

</div>
@endsection
