@extends('admin::layouts.content')

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.currencies.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.settings.currencies.add-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.currencies.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('admin::app.settings.currencies.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.settings.currencies.code') }}</label>
                                <input v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.settings.currencies.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('symbol') ? 'has-error' : '']">
                                <label for="symbol" class="required">{{ __('admin::app.settings.currencies.symbol') }}</label>
                                <input v-validate="'required'" class="control" id="symbol" name="symbol" value="{{ old('symbol') }}"/>
                                <span class="control-error" v-if="errors.has('symbol')">@{{ errors.first('symbol') }}</span>
                            </div>
                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop