@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.groups.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.groups.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>
                        
                        {{ __('admin::app.customers.groups.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.groups.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()


                    <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                        <label for="code" class="required">{{ __('admin::app.customers.groups.code') }}</label>
                        <input v-validate="'required'" class="control" id="code" name="code" data-vv-as="&quot;{{ __('admin::app.customers.groups.code') }}&quot;" v-code/>
                        <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">
                            {{ __('admin::app.customers.groups.name') }}
                        </label>
                        <input type="text" class="control" name="name" v-validate="'required'" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.customers.groups.name') }}&quot;">
                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>

                </div>
            </div>

        </form>
    </div>
@stop