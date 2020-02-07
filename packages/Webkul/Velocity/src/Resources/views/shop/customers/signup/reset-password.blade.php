@extends('shop::layouts.master')

@section('page_title')
 {{ __('shop::app.customer.reset-password.title') }}
@endsection

@section('content-wrapper')

<div class="auth-content">
    {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}
        <div class="auth-content form-container">
            <div class="container">
                <div class="col-lg-10 col-md-12 offset-lg-1">
                    <div class="heading">
                        <h2 class="fs24 fw6">
                            {{ __('shop::app.customer.reset-password.title')}}
                        </h2>
                    </div>

                    <div class="body col-12">

                        {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

                        <form
                            method="POST"
                            @submit.prevent="onSubmit"
                            action="{{ route('customer.reset-password.store') }}">

                            {{ csrf_field() }}

                            {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                            <div :class="`form-group ${errors.has('email') ? 'has-error' : ''}`">
                                <label for="email" class="required label-style mandatory">
                                    {{ __('shop::app.customer.reset-password.email') }}
                                </label>

                                <input
                                    id="email"
                                    type="text"
                                    name="email"
                                    class="form-style"
                                    value="{{ old('email') }}"
                                    v-validate="'required|email'" />

                                <span class="control-error" v-if="errors.has('email')">
                                    @{{ errors.first('email') }}
                                </span>
                            </div>

                            <div :class="`form-group ${errors.has('password') ? 'has-error' : ''}`">
                                <label for="password" class="required label-style mandatory">
                                    {{ __('shop::app.customer.reset-password.password') }}
                                </label>

                                <input
                                    ref="password"
                                    class="form-style"
                                    name="password"
                                    type="password"
                                    v-validate="'required|min:6'" />

                                <span class="control-error" v-if="errors.has('password')">
                                    @{{ errors.first('password') }}
                                </span>
                            </div>

                            <div :class="`form-group ${errors.has('confirm_password') ? 'has-error' : ''}`">
                                <label for="confirm_password" class="required label-style mandatory">
                                    {{ __('shop::app.customer.reset-password.confirm-password') }}
                                </label>

                                <input
                                    type="password"
                                    class="form-style"
                                    name="confirm_password"
                                    v-validate="'required|min:6|confirmed:password'" />

                                <span class="control-error" v-if="errors.has('confirm_password')">
                                    @{{ errors.first('confirm_password') }}
                                </span>
                            </div>

                            {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.after') !!}

                            <button class="theme-btn" type="submit">
                                {{ __('shop::app.customer.reset-password.submit-btn-title') }}
                            </button>
                        </form>


                        {!! view_render_event('bagisto.shop.customers.forget_password.after') !!}
                    </div>
                </div>
            </div>
        </div>
    {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}
</div>
@endsection