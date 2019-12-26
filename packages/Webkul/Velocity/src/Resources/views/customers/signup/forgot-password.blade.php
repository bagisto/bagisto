@extends('shop::layouts.master')

@section('page_title')
 {{ __('shop::app.customer.forgot-password.page_title') }}
@stop

@section('content-wrapper')
<div class="auth-content">
    <div class="container">
        <div class="login-text">   
            <h2 style="font-weight:bold;" class="fs24">
                {{ __('velocity::app.customer.forget-password.forgot-password')}}
                <div style="" class="w3-card-login"><a href="{{ route('customer.session.index') }}" class="btn-new-customer-login">{{ __('velocity::app.customer.signup-form.login')}}</a></div>
            </h2>
        </div>
        <div class="mb60" style="height:260px;width:575px;border:1px solid #E5E5E5;margin: 0 auto;margin-bottom:44px;">
            <div style="margin: 45px;">

                <p class="fs16" style="font-weight:bold">
                    {{ __('velocity::app.customer.forget-password.recover-password')}}
                </p>
                <p class="fs16">
                    {{ __('velocity::app.customer.forget-password.recover-password-text')}}
                </p>

                {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

                <form method="post" action="{{ route('customer.forgot-password.store') }}" @submit.prevent="onSubmit">
            
                    {{ csrf_field() }}
            
                    <div class="login-form">
                        
                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}
            
                        <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="email" class="label-style">{{ __('shop::app.customer.forgot-password.email') }}</label>
                            <input type="email" class="form-control" name="email" v-validate="'required|email'">
                            <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                        </div>
                        <br>
            
                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}
            
                        <div class="button-group">
                            <button type="submit" class="btn btn-dark-green">
                                {{ __('shop::app.customer.forgot-password.submit') }}
                            </button>
                        </div>
                    </div>
                </form>
            
                {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}
            </div>
        </div>
    </div>
</div>
@endsection