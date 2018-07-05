@extends('admin::layouts.anonymous-master')

@section('page_title')
    {{ __('Reset Password') }}
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

@section('content')

    <div class="panel">

        <div class="panel-content">

            <div class="form-container" style="text-align: left">

                <h1>{{ __('Reset Password') }}</h1>

                <form method="POST" action="{{ route('admin.reset-password.store') }}" @submit.prevent="onSubmit">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="text" v-validate="'required|email'" class="control" id="email" name="email" value="{{ old('email') }}"/>
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password">{{ __('Password') }}</label>
                        <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password"/>
                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                        <input type="password" v-validate="'required|min:6|confirmed:password'" class="control" id="password_confirmation" name="password_confirmation" data-vv-as="password"/>
                        <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" class="btn btn-xl btn-primary">{{ __('Reset Password') }}</button>
                    </div>

                    <div class="control-group" style="margin-bottom: 0">
                        <a href="{{ route('admin.session.create') }}">
                            <i class="icon primary-back-icon"></i>
                            {{ __('Back to Sign In') }}
                        </a>
                    </div>
                </form>

            </div>
        
        </div>

    </div>

@stop