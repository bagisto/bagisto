@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.edit-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.channels.update', $channel->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.settings.channels.edit-title') }}
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
                    <input name="_method" type="hidden" value="PUT">

                    {!! view_render_event('bagisto.admin.settings.channel.edit.before') !!}

                    <accordian :title="'{{ __('admin::app.settings.channels.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.settings.channels.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" data-vv-as="&quot;{{ __('admin::app.settings.channels.code') }}&quot;" value="{{ $channel->code }}" disabled="disabled"/>
                                <input type="hidden" name="code" value="{{ $channel->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.settings.channels.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('admin::app.settings.channels.name') }}&quot;" value="{{ old('name') ?: $channel->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="description">{{ __('admin::app.settings.channels.description') }}</label>
                                <textarea class="control" id="description" name="description">{{ old('description') ?: $channel->description }}</textarea>
                            </div>

                            <div class="control-group" :class="[errors.has('inventory_sources[]') ? 'has-error' : '']">
                                <label for="inventory_sources" class="required">{{ __('admin::app.settings.channels.inventory_sources') }}</label>
                                <?php $selectedOptionIds = old('inventory_sources') ?: $channel->inventory_sources->pluck('id')->toArray() ?>
                                <select v-validate="'required'" class="control" id="inventory_sources" name="inventory_sources[]" data-vv-as="&quot;{{ __('admin::app.settings.channels.inventory_sources') }}&quot;" multiple>
                                    @foreach (app('Webkul\Inventory\Repositories\InventorySourceRepository')->all() as $inventorySource)
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

                            <div class="control-group">
                                <label for="hostname">{{ __('admin::app.settings.channels.hostname') }}</label>
                                <input type="text" class="control" id="hostname" name="hostname" value="{{ $channel->hostname }}" placeholder="https://www.example.com"/>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.settings.channels.currencies-and-locales') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('locales[]') ? 'has-error' : '']">
                                <label for="locales" class="required">{{ __('admin::app.settings.channels.locales') }}</label>
                                <?php $selectedOptionIds = old('locales') ?: $channel->locales->pluck('id')->toArray() ?>
                                <select v-validate="'required'" class="control" id="locales" name="locales[]" data-vv-as="&quot;{{ __('admin::app.settings.channels.locales') }}&quot;" multiple>
                                    @foreach (core()->getAllLocales() as $locale)
                                        <option value="{{ $locale->id }}" {{ in_array($locale->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('locales[]')">@{{ errors.first('locales[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('default_locale_id') ? 'has-error' : '']">
                                <label for="default_locale_id" class="required">{{ __('admin::app.settings.channels.default-locale') }}</label>
                                <?php $selectedOption = old('default_locale_id') ?: $channel->default_locale_id ?>
                                <select v-validate="'required'" class="control" id="default_locale_id" name="default_locale_id" data-vv-as="&quot;{{ __('admin::app.settings.channels.default-locale') }}&quot;">
                                    @foreach (core()->getAllLocales() as $locale)
                                        <option value="{{ $locale->id }}" {{ $selectedOption == $locale->id ? 'selected' : '' }}>
                                            {{ $locale->name }}
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

                    <accordian :title="'{{ __('admin::app.settings.channels.design') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="theme">{{ __('admin::app.settings.channels.theme') }}</label>

                                <?php $selectedOption = old('theme') ?: $channel->theme ?>

                                <select class="control" id="theme" name="theme">
                                    @foreach (themes()->all() as $theme)
                                        <option value="{{ $theme->code }}" {{ $selectedOption == $theme->code ? 'selected' : '' }}>
                                            {{ $theme->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($selectedOption == "velocity")
                                <div class="control-group">
                                    <label for="subscription_bar">{{ __('velocity::app.admin.system.settings.channels.subscription_bar') }}</label>
                                    <textarea class="control" id="subscription_bar" name="subscription_bar_content">
                                    {{ old('subscription_bar_content') ?: $channel->subscription_bar_content }}</textarea>
                                </div>
                            @endif

                            <div class="control-group">
                                <label for="home_page_content">{{ __('admin::app.settings.channels.home_page_content') }}</label>
                                <textarea class="control" id="home_page_content" name="home_page_content">{{ old('home_page_content') ?: $channel->home_page_content }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="footer_content">{{ __('admin::app.settings.channels.footer_content') }}</label>
                                <textarea class="control" id="footer_content" name="footer_content">{{ old('footer_content') ?: $channel->footer_content }}</textarea>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.logo') }}

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="logo" :multiple="false" :images='"{{ $channel->logo_url }}"'></image-wrapper>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.settings.channels.favicon') }}

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="favicon" :multiple="false" :images='"{{ $channel->favicon_url }}"'></image-wrapper>
                            </div>

                        </div>
                    </accordian>

                    @php
                        $seo = json_decode($channel->home_seo);
                    @endphp

                    <accordian :title="'{{ __('admin::app.settings.channels.seo') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('seo_title') ? 'has-error' : '']">
                                <label for="seo_title" class="required">{{ __('admin::app.settings.channels.seo-title') }}</label>
                                <input v-validate="'required'" class="control" id="seo_title" name="seo_title" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-title') }}&quot;" value="{{ $seo->meta_title ?? old('seo_title') }}"/>
                                <span class="control-error" v-if="errors.has('seo_title')">@{{ errors.first('seo_title') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('seo_description') ? 'has-error' : '']">
                                <label for="seo_description" class="required">{{ __('admin::app.settings.channels.seo-description') }}</label>

                                <textarea v-validate="'required'" class="control" id="seo_description" name="seo_description" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-description') }}&quot;">{{ $seo->meta_description ?? old('seo_description') }}</textarea>

                                <span class="control-error" v-if="errors.has('seo_description')">@{{ errors.first('seo_description') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('seo_keywords') ? 'has-error' : '']">
                                <label for="seo_keywords" class="required">{{ __('admin::app.settings.channels.seo-keywords') }}</label>

                                <textarea v-validate="'required'" class="control" id="seo_keywords" name="seo_keywords" data-vv-as="&quot;{{ __('admin::app.settings.channels.seo-keywords') }}&quot;">{{ $seo->meta_keywords ?? old('seo_keywords') }}</textarea>

                                <span class="control-error" v-if="errors.has('seo_keywords')">@{{ errors.first('seo_keywords') }}</span>
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
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#home_page_content,textarea#subscription_bar,textarea#footer_content',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements : '*[*]'
            });
        });
    </script>
@endpush