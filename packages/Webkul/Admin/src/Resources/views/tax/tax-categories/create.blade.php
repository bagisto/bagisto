@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-categories.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.tax-categories.create') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.settings.tax-categories.add-title') }}</h1>
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

                    <div class="control-group" :class="[errors.has('channel') ? 'has-error' : '']">
                        <label for="channel" class="required">{{ __('admin::app.configuration.tax-categories.select-channel') }}</label>

                        <select class="control" name="channel_id">
                            @foreach (core()->getAllChannels() as $channelModel)

                                <option value="{{ $channelModel->id }}">
                                    {{ $channelModel->name }}
                                </option>

                            @endforeach
                        </select>

                        <span class="control-error" v-if="errors.has('channel')">@{{ errors.first('channel') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                        <label for="code" class="required">{{ __('admin::app.configuration.tax-categories.code') }}</label>

                        <input v-validate="'required'" class="control" id="code" name="code" data-vv-as="&quot;{{ __('admin::app.configuration.tax-categories.code') }}&quot;" value="{{ old('code') }}"/>

                        <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('admin::app.configuration.tax-categories.name') }}</label>

                        <input v-validate="'required'" class="control" id="name" data-vv-as="&quot;{{ __('admin::app.configuration.tax-categories.name') }}&quot;" name="name" value="{{ old('name') }}"/>

                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                        <label for="description" class="required">{{ __('admin::app.configuration.tax-categories.description') }}</label>

                        <textarea v-validate="'required'" class="control" id="description" name="description" data-vv-as="&quot;{{ __('admin::app.configuration.tax-categories.description') }}&quot;" value="{{ old('description') }}"></textarea>

                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('taxrates[]') ? 'has-error' : '']">
                        <label for="taxrates" class="required">{{ __('admin::app.configuration.tax-categories.select-taxrates') }}</label>

                        <select multiple="multiple" v-validate="'required'" class="control" id="taxrates" name="taxrates[]" data-vv-as="&quot;{{ __('admin::app.configuration.tax-categories.select-taxrates') }}&quot;" value="{{ old('taxrates') }}">
                            @foreach ($taxRates as $taxRate)
                                <option value="{{ $taxRate['id'] }}">{{ $taxRate['identifier'] }}</option>
                            @endforeach
                        </select>

                        <span class="control-error" v-if="errors.first('taxrates[]')">@{{ errors.first('taxrates[]') }}</span>
                    </div>

                </div>
            </div>

        </form>
    </div>
@stop