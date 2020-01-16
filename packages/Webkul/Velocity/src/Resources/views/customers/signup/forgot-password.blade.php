@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.forgot-password.page_title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">
        <div class="container">
            <div class="row col-10 offset-1">
                <div class="heading">
                    <h2 class="fs24 fw6">
                        {{ __('velocity::app.customer.forget-password.forgot-password')}}
                    </h2>

                    <a href="{{ route('customer.session.index') }}" class="btn-new-customer">
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
                        action="{{ route('customer.register.create') }}"
                        @submit.prevent="onSubmit">

                        {{ csrf_field() }}

                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                        <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="email" class="required label-style">
                                {{ __('shop::app.customer.forgot-password.email') }}
                            </label>

                            <input
                                type="email"
                                class="form-style"
                                name="email"
                                v-validate="'required|email'" />

                            <span class="control-error" v-if="errors.has('email')">
                                @{{ errors.first('email') }}
                            </span>
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
