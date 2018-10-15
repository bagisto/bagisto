@extends('shop::layouts.master')
@section('page_title')
 {{ __('shop::app.customer.forgot-password.page_title') }}
@endsection
@section('content-wrapper')

<div class="auth-content">

    <form method="post" action="{{ route('customer.forgot-password.store') }}">

        {{ csrf_field() }}

        <div class="login-form">

            <div class="login-text">{{ __('shop::app.customer.forgot-password.title') }}</div>

            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                <label for="email">{{ __('shop::app.customer.forgot-password.email') }}</label>
                <input type="email" class="control" name="email" v-validate="'required|email'">
                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
            </div>

            <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.forgot-password.submit') }}">

        </div>
    </form>
</div>
@endsection