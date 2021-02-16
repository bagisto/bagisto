@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-categories.edit.title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.tax-categories.update', $taxCategory->id) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.tax-categories.index') }}'"></i>

                        {{ __('admin::app.settings.tax-categories.edit.title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.tax-categories.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    @method('PUT')
                    <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                        <label for="code" class="required">{{ __('admin::app.settings.tax-categories.code') }}</label>

                        <input v-validate="'required'" class="control" id="code" name="code" data-vv-as="&quot;{{ __('admin::app.configuration.tax-categories.code') }}&quot;" value="{{ old('code') ?: $taxCategory->code }}"/>

                        <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('admin::app.settings.tax-categories.name') }}</label>
                        <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('admin::app.configuration.tax-categories.name') }}&quot;" value="{{ old('name') ?: $taxCategory->name }}"/>

                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                        <label for="description" class="required">{{ __('admin::app.settings.tax-categories.description') }}</label>
                        <textarea v-validate="'required'" class="control" id="description" name="description" data-vv-as="&quot;{{ __('admin::app.configuration.tax-categories.description') }}&quot;">{{ $taxCategory->description }}</textarea>

                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                    </div>

                    <?php $selectedOptions = old('taxrates') ?: $taxCategory->tax_rates()->pluck('tax_rates.id')->toArray() ?>

                    <div class="control-group" :class="[errors.has('taxrates[]') ? 'has-error' : '']">
                        <label for="taxrates" class="required">{{ __('admin::app.settings.tax-categories.select-taxrates') }}</label>

                        @inject('taxRates', 'Webkul\Tax\Repositories\TaxRateRepository')
                        <select multiple="multiple" class="control" id="taxrates" name="taxrates[]" data-vv-as="&quot;{{ __('admin::app.settings.tax-categories.select-taxrates') }}&quot;" v-validate="'required'">
                            @foreach ($taxRates->all() as $taxRate)
                                <option value="{{ $taxRate->id }}"  {{ in_array($taxRate->id, $selectedOptions) ? 'selected' : '' }}>
                                    {{ $taxRate->identifier }}
                                </option>
                            @endforeach
                        </select>

                        <span class="control-error" v-if="errors.has('taxrates[]')">@{{ errors.first('taxrates[]') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop