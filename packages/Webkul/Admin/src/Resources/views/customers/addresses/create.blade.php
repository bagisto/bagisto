@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.addresses.create-title') }}
@stop


@section('content-wrapper')

    <div class="content full-page">
        {!! view_render_event('admin.customer.addresses.create.before') !!}

        <form method="POST" action="{{ route('admin.customer.addresses.store', ['id' => $customer->id]) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.customers.addresses.create-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.addresses.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                    <accordian :title="'{{ __('admin::app.customers.addresses.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('company_name') ? 'has-error' : '']">
                                <label for="company_name">{{ __('shop::app.customer.account.address.create.company_name') }}</label>
                                <input type="text" class="control" name="company_name" value="{{ old('company_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.company_name') }}&quot;">
                                <span class="control-error" v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('vat_id') ? 'has-error' : '']">
                                <label for="vat_id">{{ __('shop::app.customer.account.address.create.vat_id') }}</label>
                                <input type="text" class="control" name="vat_id" value="{{ old('vat_id') }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.vat_id') }}&quot;">
                                <span class="control-error" v-if="errors.has('vat_id')">@{{ errors.first('vat_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('address1[]') ? 'has-error' : '']">
                                <label for="address_0" class="required">{{ __('shop::app.customer.account.address.edit.street-address') }}</label>
                                <input type="text" class="control" name="address1[]" id="address_0" v-validate="'required'" value="{{ old('address1') }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.street-address') }}&quot;">
                                <span class="control-error" v-if="errors.has('address1[]')">@{{ errors.first('address1[]') }}</span>
                            </div>

                            @if (core()->getConfigData('customer.settings.address.street_lines') && core()->getConfigData('customer.settings.address.street_lines') > 1)
                                <div class="control-group" style="margin-top: -25px;">
                                    @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                                        <input type="text" class="control" name="address1[{{ $i }}]" id="address_{{ $i }}">
                                    @endfor
                                </div>
                            @endif

                            <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                                <label for="city" class="required">{{ __('shop::app.customer.account.address.create.city') }}</label>
                                <input type="text" class="control" name="city" v-validate="'required|alpha_spaces'" value="{{ old('city') }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.city') }}&quot;">
                                <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                            </div>

                            @include ('admin::customers.country-state', ['countryCode' => old('country') ?? config('app.default_country'), 'stateCode' => old('state') ?? ''])

                            <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                                <label for="postcode" class="required">{{ __('shop::app.customer.account.address.create.postcode') }}</label>
                                <input type="text" class="control" name="postcode" v-validate="'required'" value="{{ old('postcode') }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.postcode') }}&quot;">
                                <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label for="phone" class="required">{{ __('shop::app.customer.account.address.create.phone') }}</label>
                                <input type="text" class="control" name="phone" v-validate="'required'" value="{{ old('phone') }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.phone') }}&quot;">
                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                            </div>

                            <div class="control-group">
                                <span class="checkbox">
                                    <input type="checkbox" class="control" id="default_address" name="default_address" {{ old('default_address') ? 'checked' : '' }} >

                                    <label class="checkbox-view" for="default_address"></label>
                                    {{ __('admin::app.customers.addresses.default-address') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </accordian>

                </div>
            </div>
        </form>

        {!! view_render_event('admin.customer.addresses.create.after') !!}
    </div>
@stop