@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.forgot-password.page_title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">
        <div class="container">
            <div class="col-lg-10 col-md-12 offset-lg-1">
                <div class="heading">
                    <h2 class="fs24 fw6">
                        {{ __('velocity::app.customer.forget-password.forgot-password')}}
                    </h2>

                    <a href="{{ route('shop.customer.session.index') }}" class="btn-new-customer">
                        <button type="button" class="theme-btn light">
                            {{  __('velocity::app.customer.signup-form.login') }}
                        </button>
                    </a>
                </div>

                <div class="body col-12">
                    <h3 class="fw6">
                        {{ __('velocity::app.customer.forget-password.recover-password')}}
                    </h3>

                    <p class="fs16">
                        {{ __('velocity::app.customer.forget-password.recover-password-text')}}
                    </p>

                    {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

                    <form
                        method="post"
                        action="{{ route('shop.customer.forgot_password.store') }}"
                        @submit.prevent="onSubmit">

                        {{ csrf_field() }}

                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                        <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="email" class="mandatory label-style">
                                {{ __('shop::app.customer.forgot-password.email') }}
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-style"
                                v-validate="'required|email'" />

                            <span class="control-error" v-if="errors.has('email')" v-text="errors.first('email')"></span>
                        </div>

                        <div class="control-group">

                            {!! Captcha::render() !!}

                        </div>

                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.after') !!}

                        <button class="theme-btn" type="submit">
                            {{ __('shop::app.customer.forgot-password.submit') }}
                        </button>
                    </form>

                    {!! view_render_event('bagisto.shop.customers.forget_password.after') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

{!! Captcha::renderJS() !!}

@endpush