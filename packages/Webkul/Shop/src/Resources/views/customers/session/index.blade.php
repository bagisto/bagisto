@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.login-form.page-title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content">
        <div class="sign-up-text">
            {{ __('shop::app.customer.login-text.no_account') }} - <a href="{{ route('shop.customer.register.index') }}">{{ __('shop::app.customer.login-text.title') }}</a>
        </div>

        {!! view_render_event('bagisto.shop.customers.login.before') !!}

        <form method="POST" action="{{ route('shop.customer.session.create') }}" @submit.prevent="onSubmit">

            {{ csrf_field() }}

            <div class="login-form">
                <div class="login-text">{{ __('shop::app.customer.login-form.title') }}</div>

                {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('shop::app.customer.login-form.email') }}</label>
                    <input type="text" class="control" name="email" v-validate="'required|email'" value="{{ old('email') }}" data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;">
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="required">{{ __('shop::app.customer.login-form.password') }}  </label>
                    <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.sessions.password') }}&quot;" value=""/>
                   <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <input type="checkbox"  id="shoPassword" >{{ __('shop::app.customer.login-form.show-password') }}  
                    </div>

                    <div class="col-md-6">
                        <div class="forgot-password-link">
                            <a href="{{ route('shop.customer.forgot_password.create') }}">{{ __('shop::app.customer.login-form.forgot_pass') }}</a>

                            <div class="mt-10">
                                @if (Cookie::has('enable-resend'))
                                    @if (Cookie::get('enable-resend') == true)
                                        <a href="{{ route('shop.customer.resend.verification_email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="control-group">

                    {!! Captcha::render() !!}
                    
                </div>

                {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}

                <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.login-form.button_title') }}">
            </div>

        </form>

        {!! view_render_event('bagisto.shop.customers.login.after') !!}
    </div>
@stop

@push('scripts')

{!! Captcha::renderJS() !!}
<script>
    $(document).ready(function(){
        $("#shoPassword").click(function() {              
            var input = $('#password').attr("type");
            if (input == "password") {
                $('#password').attr("type", "text");
            } else {
                $('#password').attr("type", "password");
            }
        });
        $(":input[name=email]").focus();
    });
</script>

@endpush