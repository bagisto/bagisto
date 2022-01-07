@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.channels.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.channels.index') }}'"></i>

                        {{ __('admin::app.settings.channels.add-title') }}
                    </h1>
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

                    {!! view_render_event('bagisto.admin.settings.channel.create.before') !!}

                    {{-- general --}}
                    <accordian :title="'{{ __('admin::app.settings.channels.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.settings.channels.code') }}</label>
                                <input v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}" data-vv-as="&quot;{{ __('admin::app.settings.channels.code') }}&quot;" v-code/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.settings.channels.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('admin::app.settings.channels.name') }}&quot;" value="{{ old('name') }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="description">{{ __('admin::app.settings.channels.description') }}</label>
                                <textarea class="control" id="description" name="description">{{ old('description') }}</textarea>
                            </div>

                            <div class="control-group" :class="[errors.has('inventory_sources[]') ? 'has-error' : '']">
                                <label for="inventory_sources" class="required">{{ __('admin::app.settings.channels.inventory_sources') }}</label>
                                <select v-validate="'required'" class="control" id="inventory_sources" name="inventory_sources[]" data-vv-as="&quot;{{ __('admin::app.settings.channels.inventory_sources') }}&quot;" multiple>
                                    @foreach (app('Webkul\Inventory\Repositories\InventorySourceRepository')->findWhere(['status' => 1]) as $inventorySource)
                                        <option value="{{ $inventorySource->id }}" {{ old('inventory_sources') && in_array($inventorySource->id, old('inventory_sources')) ? 'selected' : '' }}>
                                            {{ $inventorySource->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('inventory_sources[]')">@{{ errors.first('inventory_sources[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('root_category_id') ? 'has-error' : '']">
                                <label for="root_category_id" class="required">{{ __('admin::app.settings.channels.root-category') }}</label>
                                <select v-validate="'required'" class="control" id="root_category_id" name="root_category_id" data-vv-as="&quot;{{ __('admin::app.settings.channels.root-category') }}&quot;">
                                    @foreach (app('Webkul\Category\Repositories\CategoryRepository')->getRootCategories() as $category)
                                        <option value="{{ $category->id }}" {{ old('root_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('root_category_id')">@{{ errors.first('root_category_id') }}</span>
                            </div>

                            <div class="control-group"  :class="[errors.has('hostname') ? 'has-error' : '']">
                                <label for="hostname">{{ __('admin::app.settings.channels.hostname') }}</label>
                                <input class="control" v-validate="''" id="hostname" name="hostname" value="{{ old('hostname') }}" placeholder="{{ __('admin::app.settings.channels.hostname-placeholder') }}"/>

                                <span class="control-error" v-if="errors.has('hostname')">@{{ errors.first('hostname') }}</span>
                            </div>

                        </div>
                    </accordian>

                    {{-- currencies and locales --}}
                    <accordian :title="'{{ __('admin::app.settings.channels.currencies-and-locales') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('locales[]') ? 'has-error' : '']">
                                <label for="locales" class="required">{{ __('admin::app.settings.channels.locales') }}</label>
                                <select v-validate="'required'" class="control" id="locales" name="locales[]" data-vv-as="&quot;{{ __('admin::app.settings.channels.locales') }}&quot;" multiple>
                                    @foreach (core()->getAllLocales() as $locale)
                                        <option value="{{ $locale->id }}" {{ old('locales') && in_array($locale->id, old('locales')) ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('locales[]')">@{{ errors.first('locales[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('default_locale_id') ? 'has-error' : '']">
                                <label for="default_locale_id" class="required">{{ __('admin::app.settings.channels.default-locale') }}</label>
                                <select v-validate="'required'" class="control" id="default_locale_id" name="default_locale_id" data-vv-as="&quot;{{ __('admin::app.settings.channels.default-locale') }}&quot;">
                                    @foreach (core()->getAllLocales() as $locale)
                                        <option value="{{ $locale->id }}" {{ old('default_locale_id') == $locale->id ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('default_locale_id')">@{{ errors.first('default_locale_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('currencies[]') ? 'has-error' : '']">
                                <label for="currencies" class="required">{{ __('admin::app.settings.channels.currencies') }}</label>
                                <select v-validate="'required'" class="control" id="currencies" name="currencies[]" data-vv-as="&quot;{{ __('admin::app.settings.channels.currencies') }}&quot;" multiple>
                                    @foreach (core()->getAllCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currencies') && in_array($currency->id, old('currencies')) ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('currencies[]')">@{{ errors.first('currencies[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('base_currency_id') ? 'has-error' : '']">
                                <label for="base_currbase_currency_idency" class="required">{{ __('admin::app.settings.channels.base-currency') }}</label>
                                <select v-validate="'required'" class="control" id="base_currency_id" name="base_currency_id" data-vv-as="&quot;{{ __('admin::app.settings.channels.base-currency') }}&quot;">
                                    @foreach (core()->getAllCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ old('base_currency_id') == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('base_currency_id')">@{{ errors.first('base_currency_id') }}</span>
                            </div>

                        </div>
                    </accordian>

                    {{-- design --}}
                    <accordian :title="'{{ __('admin::app.settings.channels.design') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="theme">{{ __('admin::app.settings.channels.theme') }}</label>
                                <select class="control" id="theme" name="theme">
                                    @foreach (config('themes.themes') as $themeCode => $theme)
                                        <option value="{{ $themeCode }}" {{ old('theme') == $themeCode ? 'selected' : '' }}>
                                            {{ $theme['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="home_page_content">{{ __('admin::app.settings.channels.home_page_content') }}</label>
                                <textarea class="control" id="home_page_content" name="home_page_content">{{ old('home_page_content') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="footer_content">{{ __('admin::app.settings.channels.footer_content') }}</label>
                                <textarea class="control" id="footer_content" name="footer_content">{{ old('footer_content') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.logo') }}</label>

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="logo" :multiple="false"></image-wrapper>
                                
                                <span class="control-info mt-10">{{ __('admin::app.settings.channels.logo-size') }}</span>  
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.favicon') }}</label>

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="logo" :multiple="false"></image-wrapper>

                                <span class="control-info mt-10">{{ __('admin::app.settings.channels.favicon-size') }}</span>     
                            </div>

                        </div>
                    </accordian>

                    {{-- home page seo --}}
                    <accordian :title="'{{ __('admin::app.settings.channels.seo') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('seo_title') ? 'has-error' : '']">
                                <label for="seo_title" class="required">{{ __('admin::app.settings.channels.seo-title') }}</label>
                                <input v-validate="'required'" class="control" id="seo_title" name="seo_title" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-title') }}&quot;" value="{{ old('seo_title') }}"/>
                                <span class="control-error" v-if="errors.has('seo_title')">@{{ errors.first('seo_title') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('seo_description') ? 'has-error' : '']">
                                <label for="seo_description" class="required">{{ __('admin::app.settings.channels.seo-description') }}</label>

                                <textarea v-validate="'required'" class="control" id="seo_description" name="seo_description" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-description') }}&quot;" value="{{ old('seo_description') }}"></textarea>

                                <span class="control-error" v-if="errors.has('seo_description')">@{{ errors.first('seo_description') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('seo_keywords') ? 'has-error' : '']">
                                <label for="seo_keywords" class="required">{{ __('admin::app.settings.channels.seo-keywords') }}</label>

                                <textarea v-validate="'required'" class="control" id="seo_keywords" name="seo_keywords" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-keywords') }}&quot;" value="{{ old('seo_keywords') }}"></textarea>

                                <span class="control-error" v-if="errors.has('seo_keywords')">@{{ errors.first('seo_keywords') }}</span>
                            </div>
                        </div>
                    </accordian>

                    {{-- maintenance mode --}}
                    <accordian title="{{ __('admin::app.settings.channels.maintenance-mode') }}" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="maintenance-mode-status">{{ __('admin::app.status') }}</label>
                                <label class="switch">
                                    <input type="hidden" name="is_maintenance_on" value="0" />
                                    <input type="checkbox" id="maintenance-mode-status" name="is_maintenance_on" value="1">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="control-group">
                                <label for="maintenance-mode-text">{{ __('admin::app.settings.channels.maintenance-mode-text') }}</label>
                                <input class="control" id="maintenance-mode-text" name="maintenance_mode_text" value=""/>
                            </div>

                            <div class="control-group">
                                <label for="allowed-ips">{{ __('admin::app.settings.channels.allowed-ips') }}</label>
                                <input class="control" id="allowed-ips" name="allowed_ips" value=""/>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.settings.channel.create.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    @include('admin::layouts.tinymce')

    <script>
        $(document).ready(function () {
            tinyMCEHelper.initTinyMCE({
                selector: 'textarea#home_page_content,textarea#footer_content',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code | table',
                image_advtab: true,
                valid_elements : '*[*]',
            });
        });
    </script>
@endpush