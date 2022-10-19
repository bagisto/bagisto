@extends('admin::layouts.anonymous-master')

@section('page_title')
    {{ __('admin::app.users.sessions.title') }}
@stop

@section('content')

    <div class="panel">

        <div class="panel-content">

            <div class="form-container" style="text-align: left">

                <h1>{{ __('admin::app.users.sessions.title') }}</h1>

                <form method="POST" action="{{ route('admin.session.store') }}" @submit.prevent="onSubmit">
                    @csrf

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email">{{ __('admin::app.users.sessions.email') }}</label>
                        <input type="text" v-validate="'required|email'" class="control" id="email" name="email" data-vv-as="&quot;{{ __('admin::app.users.sessions.email') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password">{{ __('admin::app.users.sessions.password') }}</label>
                        <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.sessions.password') }}&quot;" value=""/>

                        <i refer="#password" class="rango-eye-visible toggle-password-icon" style="margin-left: -30px; cursor: pointer; vertical-align: sub;"></i>

                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                    </div>

                    <div class="control-group">
                        <a href="{{ route('admin.forget_password.create') }}">{{ __('admin::app.users.sessions.forget-password-link-title') }}</a>
                    </div>

                    <div class="button-group">
                        <button class="btn btn-xl btn-primary">{{ __('admin::app.users.sessions.submit-btn-title') }}</button>
                    </div>
                </form>

            </div>

        </div>

    </div>

@stop

@push('javascript')
    <script>
        $(document).ready(function(){
            $(".toggle-password-icon").click(function() {
                $(this).toggleClass("rango-eye-visible rango-eye-hide");

                var input = $($(this).attr("refer"));

                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
            $(":input[name=email]").focus();
        });
    </script>
@endpush