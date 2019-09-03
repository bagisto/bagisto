@extends('saas::companies.layouts.master')

@section('page_title')
    Add Stripe Details
@stop

@section('content-wrapper')
    <div class="content">
        <form method="POST" action="{{ route('admin.stripe.store-details') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        Add Stripe Details
                    </h1>
                </div>

                <div class="page-action">
                    @inject('stripeSuperAdmin', 'Webkul\StripeConnect\Repositories\StripeSuperAdminRepository')

                    @if ($stripeSuperAdmin->all()->count())
                        <a href="{{ route('admin.stripe.edit-details') }}" class="btn btn-lg btn-primary">Edit Details</a>
                    @else
                        <button type="submit" class="btn btn-lg btn-primary">
                            Save Stripe Details
                        </button>
                    @endif
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                        <label for="title" class="required">Title</label>
                        <input type="text" class="control" name="title" v-validate="'required'" value="{{ old('title') }}" data-vv-as="&quot;Title&quot;">
                        <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                        <label for="description" class="required">Description</label>
                        <textarea type="text" class="control" name="description" v-validate="'required'" value="{{ old('description') }}" data-vv-as="&quot;description&quot;"></textarea>
                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('enable_on_checkout') ? 'has-error' : '']">
                        <label for="enable_on_checkout" class="required">Is Active</label>
                        <select name="enable_on_checkout" class="control" v-validate="'required'" data-vv-as="&quot;Is Active&quot;">
                            <option value="0">Yes</option>
                            <option value="1">No</option>
                        </select>
                        <span class="control-error" v-if="errors.has('enable_on_checkout')">@{{ errors.first('enable_on_checkout') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('enable_testing') ? 'has-error' : '']">
                        <label for="enable_testing" class="required">Is Active</label>
                        <select name="enable_testing" class="control" v-validate="'required'" data-vv-as="&quot;Enable Testing&quot;">
                            <option value="0">Yes</option>
                            <option value="1">No</option>
                        </select>
                        <span class="control-error" v-if="errors.has('enable_testing')">@{{ errors.first('enable_testing') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('test_publishable_key') ? 'has-error' : '']">
                        <label for="test_publishable_key" class="required">Test Publishable Key</label>
                        <input type="text" class="control" name="test_publishable_key" v-validate="'required'" value="{{ old('test_publishable_key') }}" data-vv-as="&quot;Test Publishable Key&quot;">
                        <span class="control-error" v-if="errors.has('test_publishable_key')">@{{ errors.first('test_publishable_key') }}</span>
                    </div>
                    <div class="control-group" :class="[errors.has('test_secret_key') ? 'has-error' : '']">
                        <label for="test_secret_key" class="required">Test Secret Key</label>
                        <input type="text" class="control" name="test_secret_key" v-validate="'required'" value="{{ old('test_secret_key') }}" data-vv-as="&quot;Test secret Key&quot;">
                        <span class="control-error" v-if="errors.has('test_secret_key')">@{{ errors.first('test_secret_key') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('live_publishable_key') ? 'has-error' : '']">
                        <label for="live_publishable_key" class="required">Live Publishable Key</label>
                        <input type="text" class="control" name="live_publishable_key" v-validate="'required'" value="{{ old('live_publishable_key') }}" data-vv-as="&quot;live Publishable Key&quot;">
                        <span class="control-error" v-if="errors.has('live_publishable_key')">@{{ errors.first('live_publishable_key') }}</span>
                    </div>
                    <div class="control-group" :class="[errors.has('live_secret_key') ? 'has-error' : '']">
                        <label for="live_secret_key" class="required">Live Secret Key</label>
                        <input type="text" class="control" name="live_secret_key" v-validate="'required'" value="{{ old('live_secret_key') }}" data-vv-as="&quot;live secret Key&quot;">
                        <span class="control-error" v-if="errors.has('live_secret_key')">@{{ errors.first('live_secret_key') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop