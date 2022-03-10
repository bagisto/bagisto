@extends('admin::layouts.anonymous-master')

@section('page_title')
    {{ __('admin::app.users.forget-password.title') }}
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
                <h1>{{ __('admin::app.users.forget-password.header-title') }}</h1>

                <form method="POST" action="{{ route('admin.forget-password.store') }}" @submit.prevent="onSubmit">
                    @csrf

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email" class="required">{{ __('admin::app.users.forget-password.email') }}</label>
                        <input type="text" v-validate="'required'" class="control" id="email" name="email" data-vv-as="&quot;{{ __('admin::app.users.forget-password.email') }}&quot;" value="{{ old('email') }}"/>
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="button-group">
                        <button class="btn btn-xl btn-primary">{{ __('admin::app.users.forget-password.submit-btn-title') }}</button>
                    </div>

                    <div class="control-group" style="margin-bottom: 0">
                        <a href="{{ route('admin.session.create') }}">
                            <i class="icon primary-back-icon" style="vertical-align: bottom"></i>
                            {{ __('admin::app.users.forget-password.back-link-title') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop