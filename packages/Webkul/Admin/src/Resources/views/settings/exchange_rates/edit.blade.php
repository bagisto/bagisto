@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.exchange_rates.edit-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.exchange_rates.update', $exchangeRate->id) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.settings.exchange_rates.edit-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.exchange_rates.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('admin::app.settings.exchange_rates.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('source_currency') ? 'has-error' : '']">
                                <label for="source_currency" class="required">{{ __('admin::app.settings.exchange_rates.source_currency') }}</label>
                                <select v-validate="'required'" class="control" name="source_currency">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ $exchangeRate->source_currency == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('source_currency')">@{{ errors.first('source_currency') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('target_currency') ? 'has-error' : '']">
                                <label for="target_currency" class="required">{{ __('admin::app.settings.exchange_rates.target_currency') }}</label>
                                <select v-validate="'required'" class="control" name="target_currency">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ $exchangeRate->target_currency == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select> 
                                <span class="control-error" v-if="errors.has('target_currency')">@{{ errors.first('target_currency') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('ratio') ? 'has-error' : '']">
                                <label for="ratio" class="required">{{ __('admin::app.settings.exchange_rates.ratio') }}</label>
                                <input v-validate="'required'" class="control" id="ratio" name="ratio" value="{{ old('ratio') ?: $exchangeRate->ratio }}"/>
                                <span class="control-error" v-if="errors.has('ratio')">@{{ errors.first('ratio') }}</span>
                            </div>
                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop