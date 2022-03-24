@extends('admin::layouts.anonymous-master')

@section('page_title')
    {{ __('admin::app.users.reset-password.title') }}
@stop

@push('css')
    <style>
        .button-group {
            margin-bottom: 25px;
        }
    </style>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-content">
            <div class="form-container" style="text-align: left">
                <h1>{{ __('admin::app.users.reset-password.title') }}</h1>

                <form method="POST" action="{{ route('admin.reset-password.store') }}" @submit.prevent="onSubmit">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email">{{ __('admin::app.users.reset-password.email') }}</label>
                        <input type="text" v-validate="'required|email'" class="control" id="email" name="email" data-vv-as="&quot;{{ __('admin::app.users.reset-password.email') }}&quot;" value="{{ old('email') }}"/>
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password">{{ __('admin::app.users.reset-password.password') }}</label>
                        <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password" ref="password" data-vv-as="&quot;{{ __('admin::app.users.reset-password.password') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                        <label for="password_confirmation">{{ __('admin::app.users.reset-password.confirm-password') }}</label>
                        <input type="password" v-validate="'required|min:6|confirmed:password'" class="control" id="password_confirmation" name="password_confirmation" data-vv-as="&quot;{{ __('admin::app.users.reset-password.confirm-password') }}&quot;" data-vv-as="password"/>
                        <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-xl btn-primary">{{ __('admin::app.users.reset-password.submit-btn-title') }}</button>
                    </div>

                    <div class="control-group" style="margin-bottom: 0">
                        <a href="{{ route('admin.session.create') }}">
                            <i class="icon primary-back-icon" style="vertical-align: bottom"></i>
                            {{ __('admin::app.users.reset-password.back-link-title') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop