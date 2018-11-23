@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-categories.title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.tax-categories.update', $taxCategory->id) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.settings.tax-categories.edit.title') }}</h1>
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
                    <div class="control-group" :class="[errors.has('channel') ? 'has-error' : '']">
                        <label for="channel" class="required">{{ __('admin::app.settings.tax-categories.select-channel') }}</label>

                        <select class="control" name="channel_id">
                            @foreach(core()->getAllChannels() as $channelModel)

                                <option @if($taxCategory->channel_id == $channelModel->id) selected @endif value="{{ $channelModel->id }}">
                                    {{ $channelModel->name }}
                                </option>

                            @endforeach
                        </select>

                        <span class="control-error" v-if="errors.has('channel')">@{{ errors.first('channel') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                        <label for="code" class="required">{{ __('admin::app.settings.tax-categories.code') }}</label>

                        <input v-validate="'required'" class="control" id="code" name="code" value="{{ $taxCategory->code }}"/>

                        <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('admin::app.settings.tax-categories.name') }}</label>

                        <input v-validate="'required'" class="control" id="name" name="name" value="{{ $taxCategory->name }}"/>

                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                        <label for="description" class="required">{{ __('admin::app.settings.tax-categories.description') }}</label>

                        <textarea v-validate="'required'" class="control" id="description" name="description">{{ $taxCategory->description }}</textarea>

                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('taxrates') ? 'has-error' : '']">
                        <label for="taxrates" class="required">{{ __('admin::app.settings.tax-categories.select-taxrates') }}</label>

                        @inject('taxRates', 'Webkul\Tax\Repositories\TaxRateRepository')
                        <select multiple="multiple" class="control" id="taxrates" name="taxrates[]" v-validate="'required'">
                            @foreach($taxRates->all() as $taxRate)

                                <option value="{{ $taxRate->id }}" {{ is_numeric($taxCategory->pluck('id')->search($taxRate->id)) ? 'selected' : '' }}>

                                {{-- <option value="{{ $taxRate->id }}"> --}}
                                    {{ $taxRate->identifier }}
                                </option>

                            @endforeach
                        </select>

                        <span class="control-error" v-if="errors.has('taxrates')">@{{ errors.first('taxrates[]') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop