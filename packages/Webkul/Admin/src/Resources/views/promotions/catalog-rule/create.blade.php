@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-catalog-rule') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.customer.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.customers.customers.title') }}

                        {{ Config::get('carrier.social.facebook.url') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.customers.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                        <label for="first_name" class="required">{{ __('shop::app.customer.signup-form.firstname') }}</label>
                        <input type="text" class="control" name="first_name" v-validate="'required'" value="{{ old('first_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.firstname') }}&quot;">
                        <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop