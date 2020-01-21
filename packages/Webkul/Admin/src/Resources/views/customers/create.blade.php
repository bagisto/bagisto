@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.add-title') }}
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

                    <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                        <label for="last_name" class="required">{{ __('shop::app.customer.signup-form.lastname') }}</label>
                        <input type="text" class="control" name="last_name" v-validate="'required'" value="{{ old('last_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.lastname') }}&quot;">
                        <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email" class="required">{{ __('shop::app.customer.signup-form.email') }}</label>
                        <input type="email" class="control" name="email" v-validate="'required|email'" value="{{ old('email') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.email') }}&quot;">
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('gender') ? 'has-error' : '']">
                        <label for="gender" class="required">{{ __('admin::app.customers.customers.gender') }}</label>
                        <select name="gender" class="control" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customers.customers.gender') }}&quot;">
                            <option value="Male">{{ __('admin::app.customers.customers.male') }}</option>
                            <option value="Female">{{ __('admin::app.customers.customers.female') }}</option>
                        </select>
                        <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                        <label for="dob">{{ __('admin::app.customers.customers.date_of_birth') }}</label>
                        <input type="date" class="control" name="date_of_birth" v-validate="" value="{{ old('date_of_birth') }}"  data-vv-as="&quot;{{ __('admin::app.customers.customers.date_of_birth') }}&quot;">
                        <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="phone">{{ __('admin::app.customers.customers.phone') }}</label>
                        <input type="text" class="control" name="phone" value="{{ old('phone') }}" data-vv-as="&quot;{{ __('admin::app.customers.customers.phone') }}&quot;">
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="customerGroup" >{{ __('admin::app.customers.customers.customer_group') }}</label>
                        <select  class="control" name="customer_group_id">
                        @foreach ($customerGroup as $group)
                            <option value="{{ $group->id }}"> {{ $group->name}} </>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop