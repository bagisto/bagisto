@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.edit-title') }}
@stop

@section('content')
    <div class="content">
        @php $locale = core()->getRequestedLocaleCode(); @endphp

        <form method="POST" action="{{ route('admin.channels.update', ['id' => $channel->id, 'locale' => $locale]) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.channels.index') }}'"></i>

                        {{ __('admin::app.settings.channels.edit-title') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ route('admin.channels.edit', $channel->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
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
                    <input name="_method" type="hidden" value="PUT">

                    {!! view_render_event('bagisto.admin.settings.channel.edit.before') !!}

                    {{-- general --}}
                    <accordian title="{{ __('admin::app.settings.channels.general') }}" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.settings.channels.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" data-vv-as="&quot;{{ __('admin::app.settings.channels.code') }}&quot;" value="{{ $channel->code }}" disabled="disabled"/>
                                <input type="hidden" name="code" value="{{ $channel->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[name]') ? 'has-error' : '']">
                                <label for="name" class="required">
                                    {{ __('admin::app.settings.channels.name') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <input v-validate="'required'" class="control" id="name" name="{{$locale}}[name]" data-vv-as="&quot;{{ __('admin::app.settings.channels.name') }}&quot;" value="{{ old($locale)['name'] ?? ($channel->translate($locale)['name'] ?? $channel->name) }}"/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[name]')">@{{ errors.first('{!!$locale!!}[page_title]') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="description">
                                    {{ __('admin::app.settings.channels.description') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <textarea class="control" id="description" name="{{$locale}}[description]">{{ old($locale)['description'] ?? ($channel->translate($locale)['description'] ?? $channel->description) }}</textarea>
                            </div>

                            <div class="control-group" :class="[errors.has('inventory_sources[]') ? 'has-error' : '']">
                                <label for="inventory_sources" class="required">{{ __('admin::app.settings.channels.inventory_sources') }}</label>
                                <?php $selectedOptionIds = old('inventory_sources') ?: $channel->inventory_sources->pluck('id')->toArray() ?>
                                <select v-validate="'required'" class="control" id="inventory_sources" name="inventory_sources[]" data-vv-as="&quot;{{ __('admin::app.settings.channels.inventory_sources') }}&quot;" multiple>
                                    @foreach (app('Webkul\Inventory\Repositories\InventorySourceRepository')->findWhere(['status' => 1]) as $inventorySource)
                                        <option value="{{ $inventorySource->id }}" {{ in_array($inventorySource->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $inventorySource->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('inventory_sources[]')">@{{ errors.first('inventory_sources[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('root_category_id') ? 'has-error' : '']">
                                <label for="root_category_id" class="required">{{ __('admin::app.settings.channels.root-category') }}</label>
                                <?php $selectedOption = old('root_category_id') ?: $channel->root_category_id ?>
                                <select v-validate="'required'" class="control" id="root_category_id" name="root_category_id" data-vv-as="&quot;{{ __('admin::app.settings.channels.root-category') }}&quot;">
                                    @foreach (app('Webkul\Category\Repositories\CategoryRepository')->getRootCategories() as $category)
                                        <option value="{{ $category->id }}" {{ $selectedOption == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('root_category_id')">@{{ errors.first('root_category_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('hostname') ? 'has-error' : '']">
                                <label for="hostname">{{ __('admin::app.settings.channels.hostname') }}</label>
                                <input type="text" v-validate="''" class="control" id="hostname" name="hostname" value="{{ $channel->hostname }}" placeholder="{{ __('admin::app.settings.channels.hostname-placeholder') }}"/>

                                <span class="control-error" v-if="errors.has('hostname')">@{{ errors.first('hostname') }}</span>
                            </div>

                        </div>
                    </accordian>

                    {{-- currencies and locales --}}
                    <accordian title="{{ __('admin::app.settings.channels.currencies-and-locales') }}" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('locales[]') ? 'has-error' : '']">
                                <label for="locales" class="required">{{ __('admin::app.settings.channels.locales') }}</label>
                                <?php $selectedOptionIds = old('locales') ?: $channel->locales->pluck('id')->toArray() ?>
                                <select v-validate="'required'" class="control" id="locales" name="locales[]" data-vv-as="&quot;{{ __('admin::app.settings.channels.locales') }}&quot;" multiple>
                                    @foreach (core()->getAllLocales() as $localeModel)
                                        <option value="{{ $localeModel->id }}" {{ in_array($localeModel->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $localeModel->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('locales[]')">@{{ errors.first('locales[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('default_locale_id') ? 'has-error' : '']">
                                <label for="default_locale_id" class="required">{{ __('admin::app.settings.channels.default-locale') }}</label>
                                <?php $selectedOption = old('default_locale_id') ?: $channel->default_locale_id ?>
                                <select v-validate="'required'" class="control" id="default_locale_id" name="default_locale_id" data-vv-as="&quot;{{ __('admin::app.settings.channels.default-locale') }}&quot;">
                                    @foreach (core()->getAllLocales() as $localeModel)
                                        <option value="{{ $localeModel->id }}" {{ $selectedOption == $localeModel->id ? 'selected' : '' }}>
                                            {{ $localeModel->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('default_locale_id')">@{{ errors.first('default_locale_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('currencies[]') ? 'has-error' : '']">
                                <label for="currencies" class="required">{{ __('admin::app.settings.channels.currencies') }}</label>
                                <?php $selectedOptionIds = old('currencies') ?: $channel->currencies->pluck('id')->toArray() ?>
                                <select v-validate="'required'" class="control" id="currencies" name="currencies[]" data-vv-as="&quot;{{ __('admin::app.settings.channels.currencies') }}&quot;" multiple>
                                    @foreach (core()->getAllCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ in_array($currency->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('currencies[]')">@{{ errors.first('currencies[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('base_currency_id') ? 'has-error' : '']">
                                <label for="base_currency_id" class="required">{{ __('admin::app.settings.channels.base-currency') }}</label>
                                <?php $selectedOption = old('base_currency_id') ?: $channel->base_currency_id ?>
                                <select v-validate="'required'" class="control" id="base_currency_id" name="base_currency_id" data-vv-as="&quot;{{ __('admin::app.settings.channels.base-currency') }}&quot;">
                                    @foreach (core()->getAllCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ $selectedOption == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('base_currency_id')">@{{ errors.first('base_currency_id') }}</span>
                            </div>

                        </div>
                    </accordian>

                    {{-- design --}}
                    <accordian title="{{ __('admin::app.settings.channels.design') }}" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="theme">{{ __('admin::app.settings.channels.theme') }}</label>

                                <?php $selectedOption = old('theme') ?: $channel->theme ?>

                                <select class="control" id="theme" name="theme">
                                    @foreach (config('themes.themes') as $themeCode => $theme)
                                        <option value="{{ $themeCode }}" {{ $selectedOption == $themeCode ? 'selected' : '' }}>
                                            {{ $theme['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="home_page_content">
                                    {{ __('admin::app.settings.channels.home_page_content') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <textarea class="control" id="home_page_content" name="{{$locale}}[home_page_content]">{{ old($locale)['home_page_content'] ?? ($channel->translate($locale)['home_page_content'] ?? $channel->home_page_content) }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="footer_content">
                                    {{ __('admin::app.settings.channels.footer_content') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <textarea class="control" id="footer_content" name="{{$locale}}[footer_content]">{{ old($locale)['footer_content'] ?? ($channel->translate($locale)['footer_content'] ?? $channel->footer_content) }}</textarea>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.logo') }}</label>

                                <image-wrapper button-label="{{ __('admin::app.catalog.products.add-image-btn-title') }}" input-name="logo" :multiple="false" :images='"{{ $channel->logo_url }}"'></image-wrapper>
                            
                                <span class="control-info mt-10">{{ __('admin::app.settings.channels.logo-size') }}</span>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.favicon') }}</label>

                                <image-wrapper button-label="{{ __('admin::app.catalog.products.add-image-btn-title') }}" input-name="favicon" :multiple="false" :images='"{{ $channel->favicon_url }}"'></image-wrapper>
                                
                                <span class="control-info mt-10">{{ __('admin::app.settings.channels.favicon-size') }}</span> 
                            </div>

                        </div>
                    </accordian>

                    @php
                        $home_seo = $channel->translate($locale)['home_seo'] ?? $channel->home_seo;
                        $seo = json_decode($home_seo);
                    @endphp

                    {{-- home page seo --}}
                    <accordian title="{{ __('admin::app.settings.channels.seo') }}" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('{{$locale}}[seo_title]') ? 'has-error' : '']">
                                <label for="seo_title" class="required">
                                    {{ __('admin::app.settings.channels.seo-title') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>

                                <input v-validate="'required'" class="control" id="seo_title" name="{{$locale}}[seo_title]" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-title') }}&quot;" value="{{ $seo->meta_title ?? (old($locale)['seo_title'] ?? '') }}"/>

                                <span class="control-error" v-if="errors.has('{{$locale}}[seo_title]')">@{{ errors.first('{!!$locale!!}[page_title]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[seo_description]') ? 'has-error' : '']">
                                <label for="seo_description" class="required">
                                    {{ __('admin::app.settings.channels.seo-description') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>

                                <textarea v-validate="'required'" class="control" id="seo_description" name="{{$locale}}[seo_description]" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-description') }}&quot;">{{ $seo->meta_description ?? (old($locale)['seo_description'] ?? '') }}</textarea>

                                <span class="control-error" v-if="errors.has('{{$locale}}[seo_description]')">@{{ errors.first('{!!$locale!!}[page_title]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[seo_keywords]') ? 'has-error' : '']">
                                <label for="seo_keywords" class="required">
                                    {{ __('admin::app.settings.channels.seo-keywords') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>

                                <textarea v-validate="'required'" class="control" id="seo_keywords" name="{{$locale}}[seo_keywords]" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-keywords') }}&quot;">{{ $seo->meta_keywords ?? (old($locale)['seo_keywords'] ?? '') }}</textarea>

                                <span class="control-error" v-if="errors.has('{{$locale}}[seo_keywords]')">@{{ errors.first('{!!$locale!!}[page_title]') }}</span>
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
                                    <input type="checkbox" id="maintenance-mode-status" name="is_maintenance_on" value="1" {{ $channel->is_maintenance_on ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="control-group">
                                <label for="maintenance-mode-text">
                                    {{ __('admin::app.settings.channels.maintenance-mode-text') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <input class="control" id="maintenance-mode-text" name="{{$locale}}[maintenance_mode_text]" value="{{ old('maintenance_mode_text') ?? ($channel->translate($locale)['maintenance_mode_text'] ?? $channel->maintenance_mode_text) }}"/>
                            </div>

                            <div class="control-group">
                                <label for="allowed-ips">{{ __('admin::app.settings.channels.allowed-ips') }}</label>
                                <input class="control" id="allowed-ips" name="allowed_ips" value="{{ old('allowed_ips') ?: $channel->allowed_ips }}"/>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.settings.channel.edit.after') !!}
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