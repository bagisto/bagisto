@php
    $locale = core()->getRequestedLocaleCode();

    $seo = $channel->translate($locale)['home_seo'] ?? $channel->home_seo;
@endphp

<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.channels.edit.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.channels.edit.before') !!}

    {{-- Channeld Edit Form --}}
    <x-admin::form  
        :action="route('admin.settings.channels.update', ['id' => $channel->id, 'locale' => $locale])"
        enctype="multipart/form-data"
    >
        @method('PUT')

        {!! view_render_event('admin.settings.channels.edit.edit_form_controls.before') !!}

        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.channels.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <a
                    href="{{ route('admin.settings.channels.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.settings.channels.edit.back-btn')
                </a>

                <button 
                    type="submit" 
                    class="primary-button"
                >
                    @lang('admin::app.settings.channels.edit.save-btn')
                </button>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.general.before') !!}

                {{-- General Information --}}
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.settings.channels.edit.general')
                    </p>
                    
                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.edit.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                :value="old('code') ?? $channel->code"
                                id="code"
                                rules="required"
                                :label="trans('admin::app.settings.channels.edit.code')"
                                :placeholder="trans('admin::app.settings.channels.edit.code')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="code"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.edit.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                :name="$locale . '[name]'"
                                :value="old('name') ?? $channel->name"
                                :id="$locale . '[name]'"
                                rules="required"
                                :label="trans('admin::app.settings.channels.edit.name')"
                                :placeholder="trans('admin::app.settings.channels.edit.name')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                :control-name="$locale . '[name]'"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.edit.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description') ?? $channel->description"
                                id="description"
                                :label="trans('admin::app.settings.channels.edit.description')"
                                :placeholder="trans('admin::app.settings.channels.edit.description')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <div class="mb-[10px]">
                            <p class="required block leading-[24px] text-[12px] text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.settings.channels.edit.inventory-sources')
                            </p>
                    
                            @foreach (app('Webkul\Inventory\Repositories\InventorySourceRepository')->findWhere(['status' => 1]) as $inventorySource)
                                <x-admin::form.control-group class="flex gap-[10px] mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="inventory_sources[]"
                                        :value="$inventorySource->id" 
                                        :id="'inventory_sources_' . $inventorySource->id"
                                        :for="'inventory_sources_' . $inventorySource->id"
                                        rules="required"
                                        :label="trans('admin::app.settings.channels.edit.inventory-sources')"
                                        :checked="in_array($inventorySource->id, old('inventory_sources') ?? $channel->inventory_sources->pluck('id')->toArray())"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        :for="'inventory_sources_' . $inventorySource->id"
                                        class="!text-[14px] !text-gray-600 font-semibold cursor-pointer"
                                    >
                                        <span class="text-gray-600 dark:text-gray-300 font-semibold cursor-pointer">
                                            {{ $inventorySource->name }}
                                        </span>
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>

                            @endforeach

                            <x-admin::form.control-group.error
                                control-name="inventory_sources[]"
                            >
                            </x-admin::form.control-group.error>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.edit.root-category')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="root_category_id"
                                :value="old('root_category_id') ?? $channel->root_category_id"
                                id="root_category_id"
                                rules="required"
                                :label="trans('admin::app.settings.channels.edit.root-category')"
                            >
                                @foreach (app('Webkul\Category\Repositories\CategoryRepository')->getRootCategories() as $category)
                                    <option value="{{ $category->id }}" {{ old('root_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="root_category_id"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.edit.hostname')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="hostname"
                                :value="old('hostname') ?? $channel->hostname"
                                id="hostname"
                                :label="trans('admin::app.settings.channels.edit.hostname')"
                                :placeholder="trans('admin::app.settings.channels.edit.hostname-placeholder')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="hostname"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.general.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.design.before') !!}

                {{-- Logo and Design --}}
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.settings.channels.edit.design')
                    </p>

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.edit.theme')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="theme"
                                :value="old('theme') ?? $channel->theme"
                                id="theme"
                                :label="trans('admin::app.settings.channels.edit.theme')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('admin::app.settings.channels.create.select-theme')
                                </option>

                                @foreach (config('themes.themes') as $themeCode => $theme)
                                    <option value="{{ $themeCode }}" {{ old('theme') == $themeCode ? 'selected' : '' }}>
                                        {{ $theme['name'] }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="theme"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <div class="flex justify-between">
                            <div class="flex flex-col w-[40%]">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.channels.edit.logo')
                                    </x-admin::form.control-group.label>

                                    <x-admin::media.images
                                        name="logo"
                                        :uploaded-images="$channel->logo ? [['id' => 'logo_path', 'url' => $channel->logo_url]] : []"
                                    >
                                    </x-admin::media.images>
                                </x-admin::form.control-group>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.settings.channels.edit.logo-size')
                                </p>
                            </div>

                            <div class="flex flex-col w-[40%]">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.channels.edit.favicon')
                                    </x-admin::form.control-group.label>

                                    @php
                                        $faviconImages = $channel->favicon ? [['id' => 'logo_path', 'url' => $channel->favicon_url]] : [];
                                    @endphp

                                    <x-admin::media.images
                                        name="favicon"
                                        :uploaded-images="$channel->favicon ? [['id' => 'logo_path', 'url' => $channel->favicon_url]] : []"
                                    >
                                    </x-admin::media.images>
                                </x-admin::form.control-group>

                                <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.settings.channels.edit.favicon-size')
                                </p>
                            </div>
                        </div>
                    </div>    
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.design.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.seo.before') !!}

                {{-- Home Page SEO --}} 
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.settings.channels.edit.seo')
                    </p>

                    {{-- SEO Title & Description Blade Componnet --}}
                    <x-admin::seo/>

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
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
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_title"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.edit.seo-keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                :name="$locale . '[seo_keywords]'"
                                :value="old($locale)['seo_keywords'] ?? $seo['meta_keywords']"
                                id="seo_keywords"
                                :label="trans('admin::app.settings.channels.edit.seo-keywords')"
                                :placeholder="trans('admin::app.settings.channels.edit.seo-keywords')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_keywords"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.edit.seo-description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                :name="$locale . '[seo_description]'"
                                :value="old($locale)['seo_description'] ?? $seo['meta_description']"
                                id="meta_description"
                                rules="required"
                                :label="trans('admin::app.settings.channels.edit.seo-description')"
                                :placeholder="trans('admin::app.settings.channels.edit.seo-description')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.seo.after') !!}

            </div>

            {{-- Currencies and Locale --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.accordion.currencies_and_locales.before') !!}

                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                @lang('admin::app.settings.channels.edit.currencies-and-locales')
                            </p>
                        </div>
                    </x-slot:header>
            
                    <x-slot:content>
                        <div class="mb-[10px]">
                            <p class="required block leading-[24px] text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.settings.channels.edit.locales') 
                            </p>

                            @php $selectedLocalesId = old('locales') ?? $channel->locales->pluck('id')->toArray() @endphp
                            
                            @foreach (core()->getAllLocales() as $locale)
                                <x-admin::form.control-group class="flex gap-[10px] mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="locales[]"
                                        :value="$locale->id"
                                        :id="'locales_' . $locale->id" 
                                        :for="'locales_' . $locale->id" 
                                        :checked="in_array($locale->id, $selectedLocalesId)"
                                        rules="required"
                                        :label="trans('admin::app.settings.channels.edit.locales')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        :for="'locales_' . $locale->id"
                                        class="!text-[14px] !text-gray-600 font-semibold cursor-pointer"
                                    >
                                        <span class="text-gray-600 dark:text-gray-300 font-semibold cursor-pointer">
                                            {{ $locale->name }} 
                                        </span>
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>
                            @endforeach

                            <x-admin::form.control-group.error
                                control-name="locales[]" 
                            >
                            </x-admin::form.control-group.error>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.edit.default-locale')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="default_locale_id"
                                :value="old('default_locale_id') ?? $channel->default_locale_id"
                                id="default_locale_id"
                                rules="required"
                                :label="trans('admin::app.settings.channels.edit.default-locale')"
                            >
                                @foreach (core()->getAllLocales() as $locale)
                                    <option value="{{ $locale->id }}">
                                        {{ $locale->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="default_locale_id"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <div class="mb-[10px]">
                            <p class="required block leading-[24px] text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.settings.channels.edit.currencies')
                            </p>
                        
                            @php $selectedCurrenciesId = old('currencies') ?: $channel->currencies->pluck('id')->toArray() @endphp

                            @foreach (core()->getAllCurrencies() as $currency)
                                <x-admin::form.control-group class="flex gap-[10px] mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="currencies[]"
                                        :value="$currency->id" 
                                        :id="'currencies_' . $currency->id"
                                        :for="'currencies_' . $currency->id"
                                        rules="required"
                                        :label="trans('admin::app.settings.channels.edit.currencies')"
                                        :checked="in_array($currency->id, $selectedCurrenciesId)"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        :for="'currencies_' . $currency->id"
                                        class="!text-[14px] !text-gray-600 font-semibold cursor-pointer"
                                    >
                                        <span class="text-gray-600 dark:text-gray-300 font-semibold cursor-pointer">
                                            {{ $currency->name }} 
                                        </span>
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>
                            @endforeach

                            <x-admin::form.control-group.error
                                control-name="currencies[]"
                            >
                            </x-admin::form.control-group.error>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required"> 
                                @lang('admin::app.settings.channels.edit.default-currency')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="base_currency_id"
                                :value="old('base_currency_id') ?? $channel->base_currency_id"
                                id="base_currency_id"
                                rules="required"
                                :label="trans('admin::app.settings.channels.edit.default-currency')"
                            >
                                @foreach (core()->getAllCurrencies() as $currency)
                                    <option value="{{ $currency->id }}">
                                        {{ $currency->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="base_currency_id"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.accordion.currencies_and_locales.after') !!}

                
                {!! view_render_event('bagisto.admin.settings.channels.edit.card.accordion.settings.before') !!}
                
                {{-- Settings --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                @lang('admin::app.settings.channels.edit.settings')
                            </p>
                        </div>
                    </x-slot:header>
            
                    <x-slot:content>
                        {{-- Maintenance Mode  --}}
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.channels.edit.maintenance-mode-text')
                                </x-admin::form.control-group.label>
                                
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="maintenance_mode_text"
                                    :value="old('maintenance_mode_text') ?? ($channel->translate($locale)['maintenance_mode_text'] ?? $channel->maintenance_mode_text)"
                                    id="maintenance-mode-text"
                                    :label="trans('admin::app.settings.channels.edit.maintenance-mode-text')"
                                    :placeholder="trans('admin::app.settings.channels.edit.maintenance-mode-text')"
                                >
                                </x-admin::form.control-group.control>
                            
                                <x-admin::form.control-group.error
                                    control-name="maintenance_mode_text"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                    
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="!text-gray-800 dark:!text-white">
                                    @lang('admin::app.settings.channels.edit.allowed-ips')
                                </x-admin::form.control-group.label>
                                
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="allowed_ips"
                                    :value="old('allowed_ips') ?? $channel->allowed_ips"
                                    id="allowed-ips"
                                    :label="trans('admin::app.settings.channels.edit.allowed-ips')"
                                    :placeholder="trans('admin::app.settings.channels.edit.allowed-ips')"
                                >
                                </x-admin::form.control-group.control>
                                
                                <x-admin::form.control-group.error
                                    control-name="allowed_ips"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.channels.edit.status')
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="switch"
                                    name="is_maintenance_on"
                                    :value="1"
                                    :label="trans('admin::app.settings.channels.edit.status')"
                                    :checked="(boolean) $channel->is_maintenance_on"
                                >
                                </x-admin::form.control-group.control>
    
                                <x-admin::form.control-group.error
                                    control-name="is_maintenance_on"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                    </x-slot:content>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.channels.edit.card.accordion.settings.after') !!}

            </div>
        </div>

        {!! view_render_event('admin.settings.channels.edit.edit_form_controls.after') !!}

    </x-admin::form> 

    {!! view_render_event('bagisto.admin.settings.channels.edit.after') !!}

</x-admin::layouts>
