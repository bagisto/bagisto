@extends('shop::layouts.master')

@section('page_title')
 {{ __('shop::app.customer.forgot-password.page_title') }}
@stop

@section('css')
    <style>
        .button-group {
            margin-bottom: 25px;
        }
        .primary-back-icon {
            vertical-align: middle;
        }
    </style>
@stop

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

            <div class="button-group">
                <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.forgot-password.submit') }}">
            </div>

            <div class="control-group" style="margin-bottom: 0px;">
                <a href="{{ route('customer.session.index') }}">
                    <i class="icon primary-back-icon"></i>
                    Back to Sign In
                </a>
            </div>

        </div>
    </form>
</div>
@endsection