@extends('admin::layouts.anonymous-master')

@section('page_title')
    {{ __('Forget Password') }}
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

                <h1>{{ __('Recover Password') }}</h1>

                <form method="POST" action="{{ route('admin.forget-password.store') }}" @submit.prevent="onSubmit">
                    @csrf

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email">{{ __('Registered Email') }}</label>
                        <input type="text" v-validate="'required'" class="control" id="email" name="email" value="{{ old('email') }}"/>
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>
                    
                    <div class="button-group">
                        <button class="btn btn-xl btn-primary">{{ __('Email Password Reset Link') }}</button>
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