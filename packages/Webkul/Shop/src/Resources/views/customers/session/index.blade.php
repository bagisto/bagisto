@extends('shop::store.layouts.master')
@section('content-wrapper')
    <div class="content">
        <div class="sign-up-text">
            {{ __('shop::app.customer.login-text.no_account') }} - <a href="{{ route('customer.register.index') }}">{{ __('shop::app.customer.login-form.title') }}</a>
        </div>
        <form method="POST" action="{{ route('customer.session.create') }}">
            {{ csrf_field() }}
            <div class="login-form">
                <div class="login-text">{{ __('shop::app.customer.login-text.title') }}</div>
                <div class="control-group">
                    <label for="email">{{ __('shop::app.customer.login-form.email') }}</label>
                    <input type="text" class="control" name="email">
                </div>
                <div class="control-group">
                    <label for="password">{{ __('shop::app.customer.login-form.password') }}</label>
                    <input type="password" class="control" name="password">
                </div>
                <div class="forgot-password-link">
                    <a href="">{{ __('shop::app.customer.login-form.forgot_pass') }}</a>
                </div>

                <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.login-form.button_title') }}">
            </div>
        </form>
    </div>

@endsection
