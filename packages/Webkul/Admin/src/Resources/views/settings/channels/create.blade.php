@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.channels.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.settings.channels.add-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.channels.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('admin::app.settings.channels.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.settings.channels.code') }}</label>
                                <input v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}" v-code/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.settings.channels.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                                <label for="description" class="required">{{ __('admin::app.settings.channels.description') }}</label>
                                <textarea class="control" id="description" name="description">{{ old('description') }}</textarea>
                                <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.settings.channels.currencies-and-locales') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('locales[]') ? 'has-error' : '']">
                                <label for="locales" class="required">{{ __('admin::app.settings.channels.locales') }}</label>
                                <select v-validate="'required'" class="control" id="locales" name="locales[]" multiple>
                                    @foreach(core()->getAllLocales() as $locale)
                                        <option value="{{ $locale->id }}" {{ old('locales') && in_array($locale->id, old('locales')) ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('locales[]')">@{{ errors.first('locales[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('default_locale_id') ? 'has-error' : '']">
                                <label for="default_locale_id" class="required">{{ __('admin::app.settings.channels.default-locale') }}</label>
                                <select v-validate="'required'" class="control" id="default_locale_id" name="default_locale_id">
                                    @foreach(core()->getAllLocales() as $locale)
                                        <option value="{{ $locale->id }}" {{ old('default_locale_id') == $locale->id ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('default_locale_id')">@{{ errors.first('default_locale_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('currencies[]') ? 'has-error' : '']">
                                <label for="currencies" class="required">{{ __('admin::app.settings.channels.currencies') }}</label>
                                <select v-validate="'required'" class="control" id="currencies" name="currencies[]" multiple>
                                    @foreach(core()->getAllCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currencies') && in_array($currency->id, old('currencies')) ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('currencies[]')">@{{ errors.first('currencies[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('base_currency_id') ? 'has-error' : '']">
                                <label for="base_currbase_currency_idency" class="required">{{ __('admin::app.settings.channels.base-currency') }}</label>
                                <select v-validate="'required'" class="control" id="base_currency_id" name="base_currency_id">
                                    @foreach(core()->getAllCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ old('base_currency_id') == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('base_currency_id')">@{{ errors.first('base_currency_id') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.settings.channels.design') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="theme">{{ __('admin::app.settings.channels.theme') }}</label>
                                <select class="control" id="theme" name="theme">
                                    @foreach(themes()->all() as $theme)
                                        <option value="{{ $theme->code }}" {{ old('theme') == $theme->code ? 'selected' : '' }}>
                                            {{ $theme->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.logo') }}

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="logo" :multiple="false"></image-wrapper>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.favicon') }}

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="logo" :multiple="false"></image-wrapper>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop