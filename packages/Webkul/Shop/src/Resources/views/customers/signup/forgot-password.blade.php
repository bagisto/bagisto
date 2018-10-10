@extends('shop::layouts.master')

@section('content-wrapper')

<div class="content">

    <form method="post" action="{{ route('customer.forgot-password.store') }}">

        {{ csrf_field() }}

        <div class="login-form">

            <div class="login-text">{{ __('shop::app.customer.forgot-password-form.title') }}</div>

            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                <label for="email">{{ __('shop::app.customer.forgot-password-form.email') }}</label>
                <input type="email" class="control" name="email" v-validate="'required|email'">
                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
            </div>

            <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.forgot-password-form.button_title') }}">

        </div>
    </form>
</div>
@endsection