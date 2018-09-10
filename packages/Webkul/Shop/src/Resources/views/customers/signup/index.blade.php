@extends('shop::layouts.master')

@section('content-wrapper')

<div class="content">

    <div class="sign-up-text">
        {{ __('shop::app.customer.signup-text.account_exists') }} - <a href="{{ route('customer.session.index') }}">{{ __('shop::app.customer.signup-text.title') }}</a>
    </div>

    <form method="post" action="{{ route('customer.register.create') }}">

        {{ csrf_field() }}

        <div class="login-form">

            <div class="login-text">{{ __('shop::app.customer.signup-form.title') }}</div>

            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                <label for="first_name">{{ __('shop::app.customer.signup-form.firstname') }}</label>
                <input type="text" class="control" name="first_name" v-validate="'required'">
                <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                <label for="last_name">{{ __('shop::app.customer.signup-form.lastname') }}</label>
                <input type="text" class="control" name="last_name" v-validate="'required'">
                <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                <label for="email">{{ __('shop::app.customer.signup-form.email') }}</label>
                <input type="email" class="control" name="email" v-validate="'required|email'">
                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                <label for="password">{{ __('shop::app.customer.signup-form.password') }}</label>
                <input type="password" class="control" name="password" v-validate="'required|min:6'" ref="password">
                <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('confirm_password') ? 'has-error' : '']">
                <label for="confirm_password">{{ __('shop::app.customer.signup-form.confirm_pass') }}</label>
                <input type="password" class="control" name="password_confirmation"  v-validate="'required|min:6|confirmed:password'">
                <span class="control-error" v-if="errors.has('confirm_password')">@{{ errors.first('confirm_password') }}</span>
            </div>

            <div class="signup-confirm" :class="[errors.has('agreement') ? 'has-error' : '']">
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
