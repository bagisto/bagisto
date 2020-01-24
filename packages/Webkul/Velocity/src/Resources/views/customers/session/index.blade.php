@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.login-form.page-title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">

        {!! view_render_event('bagisto.shop.customers.login.before') !!}

            <div class="container">
                <div class="col-lg-10 col-md-12 offset-lg-1">
                    <div class="heading">
                        <h2 class="fs24 fw6">
                            {{ __('velocity::app.customer.login-form.customer-login')}}
                        </h2>

                        <a href="{{ route('customer.register.index') }}" class="btn-new-customer">
                            <button type="button" class="theme-btn light">
                                {{ __('velocity::app.customer.login-form.sign-up')}}
                            </button>
                        </a>
                    </div>

                    <div class="body col-12">
                        <div class="form-header">
                            <h3 class="fw6">
                                {{ __('velocity::app.customer.login-form.registered-user')}}
                            </h3>

                            <p class="fs16">
                                {{ __('velocity::app.customer.login-form.form-login-text')}}
                            </p>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('customer.session.create') }}"
                            @submit.prevent="onSubmit">

                            {{ csrf_field() }}

                            {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                            <div class="form-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email" class="required label-style">
                                    {{ __('shop::app.customer.login-form.email') }}*
                                </label>

                                <input
                                    type="text"
                                    class="form-style"
                                    name="email"
                                    v-validate="'required|email'"
                                    value="{{ old('email') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;" />

                                <span class="control-error" v-if="errors.has('email')">
                                    @{{ errors.first('email') }}
                                </span>
                            </div>

                            <div class="form-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password" class="required label-style">
                                    {{ __('shop::app.customer.login-form.password') }}*
                                </label>

                                <input
                                    type="password"
                                    class="form-style"
                                    name="password"
                                    v-validate="'required'"
                                    value="{{ old('password') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.login-form.password') }}&quot;" />

                                <span class="control-error" v-if="errors.has('password')">
                                    @{{ errors.first('password') }}
                                </span>

                                <a href="{{ route('customer.forgot-password.create') }}" style="float: right;">
                                    {{ __('shop::app.customer.login-form.forgot_pass') }}
                                </a>

                                <div class="mt10">
                                    @if (Cookie::has('enable-resend'))
                                        @if (Cookie::get('enable-resend') == true)
                                            <a href="{{ route('customer.resend.verification-email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}

                            <input class="theme-btn" type="submit" value="{{ __('shop::app.customer.login-form.button_title') }}">

                        </form>
                    </div>
                </div>
            </div>

        {!! view_render_event('bagisto.shop.customers.login.after') !!}
    </div>
@endsection
