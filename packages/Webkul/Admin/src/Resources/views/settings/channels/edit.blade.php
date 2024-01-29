@php
    $locale = core()->getRequestedLocaleCode();

    $seo = $channel->translate($locale)['home_seo'] ?? $channel->home_seo;
@endphp

<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.channels.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.channels.edit.before') !!}

    <!-- Channeld Edit Form -->
    <x-admin::form  
        :action="route('admin.settings.channels.update', ['id' => $channel->id, 'locale' => $locale])"
        enctype="multipart/form-data"
    >
        @method('PUT')

        {!! view_render_event('admin.settings.channels.edit.edit_form_controls.before') !!}

        <div class="flex justify-between items-center">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.channels.edit.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <a
                    href="{{ route('admin.settings.channels.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.settings.channels.edit.back-btn')
                </a>

                <button 
                    type="submit" 
                    class="primary-button"
                    aria-lebel="Submit"
                >
                    @lang('admin::app.settings.channels.edit.save-btn')
                </button>
            </div>
        </div>

        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <!-- Left Component -->
            <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.general.before') !!}

                <!-- General Information -->
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                        @lang('admin::app.settings.channels.edit.general')
                    </p>

                    <!-- Code -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.edit.code')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="code"
                            name="code"
                            rules="required"
                            :value="old('code') ?? $channel->code"
                            :label="trans('admin::app.settings.channels.edit.code')"
                            :placeholder="trans('admin::app.settings.channels.edit.code')"
                        />

                        <x-admin::form.control-group.error control-name="code" />
                    </x-admin::form.control-group>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            :id="$locale . '[name]'"
                            :name="$locale . '[name]'"
                            rules="required"
                            :value="old('name') ?? $channel->name"
                            :label="trans('admin::app.settings.channels.edit.name')"
                            :placeholder="trans('admin::app.settings.channels.edit.name')"
                        />

                        <x-admin::form.control-group.error :control-name="$locale . '[name]'" />
                    </x-admin::form.control-group>

                    <!-- Description -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.channels.edit.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            id="description"
                            name="description"
                            :value="old('description') ?? $channel->description"
                            :label="trans('admin::app.settings.channels.edit.description')"
                            :placeholder="trans('admin::app.settings.channels.edit.description')"
                        />

                        <x-admin::form.control-group.error control-name="description" />
                    </x-admin::form.control-group>

                    <!-- Inventory Sources -->
                    <div class="mb-4">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.edit.inventory-sources')
                        </x-admin::form.control-group.label>
                
                        @foreach (app('Webkul\Inventory\Repositories\InventorySourceRepository')->findWhere(['status' => 1]) as $inventorySource)
                            <x-admin::form.control-group class="flex items-center gap-2.5 !mb-2">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    :id="'inventory_sources_' . $inventorySource->id"
                                    name="inventory_sources[]"
                                    rules="required"
                                    :value="$inventorySource->id" 
                                    :for="'inventory_sources_' . $inventorySource->id"
                                    :label="trans('admin::app.settings.channels.edit.inventory-sources')"
                                    :checked="in_array($inventorySource->id, old('inventory_sources') ?? $channel->inventory_sources->pluck('id')->toArray())"
                                />

                                <label
                                    class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                    for="inventory_sources_{{ $inventorySource->id }}"
                                >
                                    {{ $inventorySource->name }}
                                </label>
                            </x-admin::form.control-group>
                        @endforeach

                        <x-admin::form.control-group.error control-name="inventory_sources[]" />
                    </div>

                    <!-- Root Category -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.channels.edit.root-category')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            id="root_category_id"
                            name="root_category_id"
                            rules="required"
                            :value="old('root_category_id') ?? $channel->root_category_id"
                            :label="trans('admin::app.settings.channels.edit.root-category')"
                        >
                            @foreach (app('Webkul\Category\Repositories\CategoryRepository')->getRootCategories() as $category)
                                <option value="{{ $category->id }}" {{ old('root_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="root_category_id" />
                    </x-admin::form.control-group>

                    <!-- Host Name -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.channels.edit.hostname')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="hostname"
                            name="hostname"
                            :value="old('hostname') ?? $channel->hostname"
                            :label="trans('admin::app.settings.channels.edit.hostname')"
                            :placeholder="trans('admin::app.settings.channels.edit.hostname-placeholder')"
                        />

                        <x-admin::form.control-group.error control-name="hostname" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.general.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.design.before') !!}

                <!-- Logo and Design -->
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                        @lang('admin::app.settings.channels.edit.design')
                    </p>

                    <!-- Theme Selector -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.channels.edit.theme')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            id="theme"
                            name="theme"
                            :value="old('theme') ?? $channel->theme"
                            :label="trans('admin::app.settings.channels.edit.theme')"
                        >
                            <!-- Default Option -->
                            <option value="">
                                @lang('admin::app.settings.channels.create.select-theme')
                            </option>

                            @foreach (config('themes.shop') as $themeCode => $theme)
                                <option value="{{ $themeCode }}" {{ old('theme') == $themeCode ? 'selected' : '' }}>
                                    {{ $theme['name'] }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="theme" />
                    </x-admin::form.control-group>

                    <div class="flex justify-between">
                        <!-- Logo -->
                        <div class="flex flex-col w-2/5">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.channels.edit.logo')
                                </x-admin::form.control-group.label>

                                <x-admin::media.images
                                    name="logo"
                                    width="110px"
                                    height="110px"
                                    :uploaded-images="$channel->logo ? [['id' => 'logo_path', 'url' => $channel->logo_url]] : []"
                                />
                            </x-admin::form.control-group>

                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.channels.edit.logo-size')
                            </p>
                        </div>

                        <!-- Favicon -->
                        <div class="flex flex-col w-2/5">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.channels.edit.favicon')
                                </x-admin::form.control-group.label>

                                @php
                                    $faviconImages = $channel->favicon ? [['id' => 'logo_path', 'url' => $channel->favicon_url]] : [];
                                @endphp

                                <x-admin::media.images
                                    name="favicon"
                                    width="110px"
                                    height="110px"
                                    :uploaded-images="$channel->favicon ? [['id' => 'logo_path', 'url' => $channel->favicon_url]] : []"
                                />
                            </x-admin::form.control-group>

                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.channels.edit.favicon-size')
                            </p>
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.design.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.seo.before') !!}

                <!-- Home Page SEO -->
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                        @lang('admin::app.settings.channels.edit.seo')
                    </p>

                    <!-- SEO Title & Description Blade Componnet -->
                    <x-admin::seo/>

                    <!-- Meta Title -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.edit.seo-title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            :name="$locale . '[seo_title]'"
                            :value="old($locale)['seo_title'] ?? $seo['meta_title']"
                            id="meta_title"
                            rules="required"
                            :label="trans('admin::app.settings.channels.edit.seo-title')"
                            :placeholder="trans('admin::app.settings.channels.edit.seo-title')"
                        />

                        <x-admin::form.control-group.error :control-name="$locale . '[seo_title]'" />
                    </x-admin::form.control-group>

                    <!-- Meta Keywords -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.channels.edit.seo-keywords')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            id="seo_keywords"
                            :name="$locale . '[seo_keywords]'"
                            :value="old($locale)['seo_keywords'] ?? $seo['meta_keywords']"
                            :label="trans('admin::app.settings.channels.edit.seo-keywords')"
                            :placeholder="trans('admin::app.settings.channels.edit.seo-keywords')"
                        />
                    </x-admin::form.control-group>

                    <!-- Meta Description -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.edit.seo-description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            id="meta_description"
                            :name="$locale . '[seo_description]'"
                            rules="required"
                            :value="old($locale)['seo_description'] ?? $seo['meta_description']"
                            :label="trans('admin::app.settings.channels.edit.seo-description')"
                            :placeholder="trans('admin::app.settings.channels.edit.seo-description')"
                        />

                        <x-admin::form.control-group.error :control-name="$locale . '[seo_description]'" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.seo.after') !!}

            </div>

            <!-- Right Compoenent -->
            <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.accordion.currencies_and_locales.before') !!}

                <!-- Currencies and Locale -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                @lang('admin::app.settings.channels.edit.currencies-and-locales')
                            </p>
                        </div>
                    </x-slot>
            
                    <x-slot:content>
                        <!-- Locales Checkboxes -->
                        <div class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.edit.locales') 
                            </x-admin::form.control-group.label>

                            @php $selectedLocalesId = old('locales') ?? $channel->locales->pluck('id')->toArray() @endphp
                            
                            @foreach (core()->getAllLocales() as $locale)
                                <x-admin::form.control-group class="flex items-center gap-2.5 !mb-2">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        :id="'locales_' . $locale->id" 
                                        name="locales[]"
                                        rules="required"
                                        :value="$locale->id"
                                        :for="'locales_' . $locale->id" 
                                        :label="trans('admin::app.settings.channels.edit.locales')"
                                        :checked="in_array($locale->id, $selectedLocalesId)"
                                    />

                                    <label
                                        class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                        for="locales_{{ $locale->id }}"
                                    >
                                        {{ $locale->name }} 
                                    </label>
                                </x-admin::form.control-group>
                            @endforeach

                            <x-admin::form.control-group.error control-name="locales[]" />
                        </div>

                        <!-- Default Locale Selector -->
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.edit.default-locale')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                id="default_locale_id"
                                name="default_locale_id"
                                rules="required"
                                :value="old('default_locale_id') ?? $channel->default_locale_id"
                                :label="trans('admin::app.settings.channels.edit.default-locale')"
                            >
                                @foreach (core()->getAllLocales() as $locale)
                                    <option value="{{ $locale->id }}">
                                        {{ $locale->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="default_locale_id" />
                        </x-admin::form.control-group>

                        <!-- Currencies Checkboxes -->
                        <div class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.edit.currencies')
                            </x-admin::form.control-group.label>
                        
                            @php $selectedCurrenciesId = old('currencies') ?: $channel->currencies->pluck('id')->toArray() @endphp

                            @foreach (core()->getAllCurrencies() as $currency)
                                <x-admin::form.control-group class="flex items-center gap-2.5 !mb-2">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        :id="'currencies_' . $currency->id"
                                        name="currencies[]"
                                        rules="required"
                                        :value="$currency->id" 
                                        :for="'currencies_' . $currency->id"
                                        :label="trans('admin::app.settings.channels.edit.currencies')"
                                        :checked="in_array($currency->id, $selectedCurrenciesId)"
                                    />

                                    <label
                                        class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                        for="currencies_{{ $currency->id }}"
                                    >
                                        {{ $currency->name }} 
                                    </label>
                                </x-admin::form.control-group>
                            @endforeach

                            <x-admin::form.control-group.error control-name="currencies[]" />
                        </div>

                        <!-- Default Currency Selector -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required"> 
                                @lang('admin::app.settings.channels.edit.default-currency')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                id="base_currency_id"
                                name="base_currency_id"
                                rules="required"
                                :value="old('base_currency_id') ?? $channel->base_currency_id"
                                :label="trans('admin::app.settings.channels.edit.default-currency')"
                            >
                                @foreach (core()->getAllCurrencies() as $currency)
                                    <option value="{{ $currency->id }}">
                                        {{ $currency->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="base_currency_id" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.accordion.currencies_and_locales.after') !!}
                
                {!! view_render_event('bagisto.admin.settings.channels.edit.card.accordion.settings.before') !!}
                
                <!-- Maintainance Mode -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                @lang('admin::app.settings.channels.edit.maintenance-mode')
                            </p>
                        </div>
                    </x-slot>
            
                    <x-slot:content>
                        <!-- Maintenance Mode Text -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.edit.maintenance-mode-text')
                            </x-admin::form.control-group.label>
                            
                            <x-admin::form.control-group.control
                                type="text"
                                id="maintenance-mode-text"
                                name="{{ $locale->code }}[maintenance_mode_text]"
                                :value="old('maintenance_mode_text') ?? ($channel->translate($locale)['maintenance_mode_text'] ?? $channel->maintenance_mode_text)"
                                :label="trans('admin::app.settings.channels.edit.maintenance-mode-text')"
                                :placeholder="trans('admin::app.settings.channels.edit.maintenance-mode-text')"
                            />
                        
                            <x-admin::form.control-group.error control-name="maintenance_mode_text" />
                        </x-admin::form.control-group>

                        <!-- Allowed API's -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="!text-gray-800 dark:!text-white">
                                @lang('admin::app.settings.channels.edit.allowed-ips')
                            </x-admin::form.control-group.label>
                            
                            <x-admin::form.control-group.control
                                type="text"
                                id="allowed-ips"
                                name="allowed_ips"
                                :value="old('allowed_ips') ?? $channel->allowed_ips"
                                :label="trans('admin::app.settings.channels.edit.allowed-ips')"
                                :placeholder="trans('admin::app.settings.channels.edit.allowed-ips')"
                            />
                            
                            <x-admin::form.control-group.error control-name="allowed_ips" />
                        </x-admin::form.control-group>

                        <!-- Maintenance Mode Switcher -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.edit.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="is_maintenance_on"
                                :value="1"
                                :label="trans('admin::app.settings.channels.edit.status')"
                                :checked="(boolean) $channel->is_maintenance_on"
                            />

                            <x-admin::form.control-group.error control-name="is_maintenance_on" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.accordion.settings.after') !!}

            </div>
        </div>

        {!! view_render_event('admin.settings.channels.edit.edit_form_controls.after') !!}

    </x-admin::form> 

    {!! view_render_event('bagisto.admin.settings.channels.edit.after') !!}

</x-admin::layouts>
