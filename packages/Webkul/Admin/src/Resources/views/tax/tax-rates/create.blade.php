@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-rates.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.tax-rates.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.tax-rates.index') }}'"></i>

                        {{ __('admin::app.settings.tax-rates.add-title') }}
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
                    @csrf()

                    <tax-rate-form></tax-rate-form>

                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')

    <script type="text/x-template" id="tax-rate-form-template">
        <div>
            <div class="control-group" :class="[errors.has('identifier') ? 'has-error' : '']">
                <label for="identifier" class="required">{{ __('admin::app.configuration.tax-rates.identifier') }}</label>
                <input v-validate="'required'" class="control" id="identifier" name="identifier" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.identifier') }}&quot;" value="{{ old('identifier') }}"/>
                <span class="control-error" v-if="errors.has('identifier')">@{{ errors.first('identifier') }}</span>
            </div>

            <country-state></country-state>

            <div class="control-group">
                <label for="zip_code">{{ __('admin::app.configuration.tax-rates.is_zip') }}</label>

                <label class="switch">
                    <input type="checkbox" id="is_zip" name="is_zip" v-model="is_zip">
                    <span class="slider round"></span>
                </label>
            </div>

            <div v-if="! is_zip" class="control-group" :class="[errors.has('zip_code') ? 'has-error' : '']" id="zip_code">
                <label for="zip_code">{{ __('admin::app.configuration.tax-rates.zip_code') }}</label>
                <input class="control" id="zip_code" name="zip_code" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.zip_code') }}&quot;" value="{{ old('zip_code') }}"/>
                <span class="control-error" v-if="errors.has('zip_code')">@{{ errors.first('zip_code') }}</span>
            </div>

            <span v-if="is_zip">
                <div class="control-group" :class="[errors.has('zip_from') ? 'has-error' : '']" id="zip_from">
                    <label for="zip_from" class="required">{{ __('admin::app.configuration.tax-rates.zip_from') }}</label>
                    <input v-validate="'required'" class="control" name="zip_from" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.zip_from') }}&quot;" value="{{ old('zip_from') }}"/>
                    <span class="control-error" v-if="errors.has('zip_from')">@{{ errors.first('zip_from') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('zip_to') ? 'has-error' : '']" id="zip_to">
                    <label for="zip_to" class="required">{{ __('admin::app.configuration.tax-rates.zip_to') }}</label>
                    <input v-validate="'required'" class="control" name="zip_to" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.zip_to') }}&quot;" value="{{ old('zip_to') }}"/>
                    <span class="control-error" v-if="errors.has('zip_to')">@{{ errors.first('zip_to') }}</span>
                </div>
            </span>

            <div class="control-group" :class="[errors.has('tax_rate') ? 'has-error' : '']">
                <label for="tax_rate" class="required">{{ __('admin::app.configuration.tax-rates.tax_rate') }}</label>
                <input v-validate="'required|decimal|min_value:0.0001'" class="control" id="tax_rate" name="tax_rate" data-vv-as="&quot;{{ __('admin::app.configuration.tax-rates.tax_rate') }}&quot;" value="{{ old('tax_rate') }}"/>
                <span class="control-error" v-if="errors.has('tax_rate')">@{{ errors.first('tax_rate') }}</span>
            </div>
        </div>
    </script>

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
        Vue.component('tax-rate-form', {

            template: '#tax-rate-form-template',

            inject: ['$validator'],

            data: function () {
                return {
                    is_zip: false
                }
            },
        });

        Vue.component('country-state', {

            template: '#country-state-template',

            inject: ['$validator'],

            data: function () {
                return {
                    country: "{{ old('country')  }}",

                    state: "{{ old('state')  }}",

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