@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-rates.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.tax-rates.update', $taxRate->id) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.tax-rates.index') }}'"></i>

                        {{ __('admin::app.settings.tax-rates.edit.title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.tax-rates.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @method('PUT')

                    @csrf()

                    <div class="control-group" :class="[errors.has('identifier') ? 'has-error' : '']">
                        <label for="identifier" class="required">{{ __('admin::app.configuration.tax-rates.identifier') }}</label>
                        <input v-validate="'required'" class="control" id="identifier" name="identifier" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.identifier') }}&quot;" value="{{ old('identifier') ?: $taxRate->identifier }}" disabled="disabled"/>
                        <input type="hidden" name="identifier" value="{{ $taxRate->identifier }}"/>
                        <span class="control-error" v-if="errors.has('identifier')">@{{ errors.first('identifier') }}</span>
                    </div>

                    <country-state></country-state>

                    @if ($taxRate->is_zip)
                        <input type="hidden" id="is_zip" name="is_zip" value="{{ $taxRate->is_zip }}">

                        <div class="control-group" :class="[errors.has('zip_from') ? 'has-error' : '']">
                            <label for="zip_from" class="required">{{ __('admin::app.configuration.tax-rates.zip_from') }}</label>
                            <input v-validate="'required'" class="control" id="zip_from" name="zip_from" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.zip_from') }}&quot;" value="{{ old('zip_form') ?: $taxRate->zip_from }}" />
                            <span class="control-error" v-if="errors.has('zip_from')">@{{ errors.first('zip_from') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('zip_to') ? 'has-error' : '']">
                            <label for="zip_to" class="required">{{ __('admin::app.configuration.tax-rates.zip_to') }}</label>
                            <input v-validate="'required'" class="control" id="zip_to" name="zip_to" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.zip_to') }}&quot;" value="{{ old('zip_to') ?: $taxRate->zip_to }}" />
                            <span class="control-error" v-if="errors.has('zip_to')">@{{ errors.first('zip_to') }}</span>
                        </div>
                    @else
                        <div class="control-group" :class="[errors.has('zip_code') ? 'has-error' : '']">
                            <label for="zip_code">{{ __('admin::app.configuration.tax-rates.zip_code') }}</label>
                            <input class="control" id="zip_code" name="zip_code" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.zip_code') }}&quot;" value="{{ old('zip_code') ?: $taxRate->zip_code }}" />
                            <span class="control-error" v-if="errors.has('zip_code')">@{{ errors.first('zip_code') }}</span>
                        </div>
                    @endif

                    <div class="control-group" :class="[errors.has('tax_rate') ? 'has-error' : '']">
                        <label for="tax_rate" class="required">{{ __('admin::app.configuration.tax-rates.tax_rate') }}</label>
                        <input v-validate="'required|decimal|min_value:0.0001'" class="control" id="tax_rate" name="tax_rate" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.tax_rate') }}&quot;" value="{{ old('tax_rate') ?: $taxRate->tax_rate }}" />
                        <span class="control-error" v-if="errors.has('tax_rate')">@{{ errors.first('tax_rate') }}</span>
                    </div>

                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')

    <script type="text/x-template" id="country-state-template">
        <div>
            <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
                <label for="country" class="required">
                    {{ __('admin::app.customers.customers.country') }}
                </label>

                <select type="text" v-validate="'required'" class="control" id="country" name="country" v-model="country" data-vv-as="&quot;{{ __('admin::app.customers.customers.country') }}&quot;">
                    <option value=""></option>

                    @foreach (core()->countries() as $country)

                        <option value="{{ $country->code }}">{{ $country->name }}</option>

                    @endforeach
                </select>

                <span class="control-error" v-if="errors.has('country')">
                    @{{ errors.first('country') }}
                </span>
            </div>

            <div class="control-group" :class="[errors.has('state') ? 'has-error' : '']">
                <label for="state">
                    {{ __('admin::app.customers.customers.state') }}
                </label>

                <select class="control" id="state" name="state" v-model="state">

                    <option value="" :selected="! haveStates()">*</option>

                    <option v-for='(state, index) in countryStates[country]' :value="state.code">
                        @{{ state.default_name }}
                    </option>

                </select>

                <span class="control-error" v-if="errors.has('state')">
                    @{{ errors.first('state') }}
                </span>
            </div>
        </div>
    </script>

    <script>
        Vue.component('country-state', {

            template: '#country-state-template',

            inject: ['$validator'],

            data: function () {
                return {
                    country: "{{ old('country') ?? $taxRate->country  }}",

                    state: "{{ old('state') ?? $taxRate->state  }}",

                    countryStates: @json(core()->groupedStatesByCountries())
                }
            },

            methods: {
                haveStates: function () {
                    if (this.countryStates[this.country] && this.countryStates[this.country].length)
                        return true;

                    return false;
                },
            }
        });
    </script>
@endpush