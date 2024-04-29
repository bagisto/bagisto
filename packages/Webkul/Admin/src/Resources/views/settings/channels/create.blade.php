<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.channels.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.channels.create.before') !!}

    <x-admin::form  action="{{ route('admin.settings.channels.store') }}" enctype="multipart/form-data">

        {!! view_render_event('admin.settings.channels.create.create_form_controls.before') !!}

        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.settings.channels.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.settings.channels.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.settings.channels.create.cancel')
                </a>

                <!-- Save Button -->
                <button 
                    type="submit" 
                    class="primary-button"
                >
                    @lang('admin::app.settings.channels.create.save-btn')
                </button>
            </div>
        </div>

        <!-- body content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left sub-component -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.settings.channels.create.card.general.before') !!}

                <!-- General Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.settings.channels.create.general')
                    </p>

                    <!-- Code -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.create.code')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="code"
                            name="code"
                            rules="required"
                            :value="old('code')"
                            :label="trans('admin::app.settings.channels.create.code')"
                            :placeholder="trans('admin::app.settings.channels.create.code')"
                        />

                        <x-admin::form.control-group.error control-name="code" />
                    </x-admin::form.control-group>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.create.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="name"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('admin::app.settings.channels.create.name')"
                            :placeholder="trans('admin::app.settings.channels.create.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Description -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.channels.create.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            id="description"
                            name="description"
                            :value="old('description')"
                            :label="trans('admin::app.settings.channels.create.description')"
                            :placeholder="trans('admin::app.settings.channels.create.description')"
                        />

                        <x-admin::form.control-group.error control-name="description" />
                    </x-admin::form.control-group>

                    <!-- Invertory Sources -->
                    <div class="mb-4">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.create.inventory-sources')
                        </x-admin::form.control-group.label>

                        @foreach (app('Webkul\Inventory\Repositories\InventorySourceRepository')->findWhere(['status' => 1]) as $inventorySource)
                            <x-admin::form.control-group class="!mb-2 flex items-center gap-2.5">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    :id="'inventory_sources_' . $inventorySource->id"
                                    name="inventory_sources[]"
                                    rules="required"
                                    :value="$inventorySource->id "
                                    :for="'inventory_sources_' . $inventorySource->id"
                                    :label="trans('admin::app.settings.channels.create.inventory-sources')"
                                />
                                    
                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="inventory_sources_{{ $inventorySource->id }}"
                                >
                                    {{ $inventorySource->name }}
                                </label>

                            </x-admin::form.control-group>
                        @endforeach 

                        <x-admin::form.control-group.error control-name="inventory_sources[]" />
                    </div>

                    <!-- Root Catgegory -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.create.root-category')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            id="root_category_id"
                            name="root_category_id"
                            rules="required"
                            :value="old('root_category_id')"
                            :label="trans('admin::app.settings.channels.create.root-category')"
                        >
                            <!-- Default Option -->
                            <option value="">
                                @lang('admin::app.settings.channels.create.select-root-category')
                            </option>

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
                            @lang('admin::app.settings.channels.create.hostname')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="hostname"
                            name="hostname"
                            :value="old('hostname')"
                            :label="trans('admin::app.settings.channels.create.hostname')"
                            :placeholder="trans('admin::app.settings.channels.create.hostname-placeholder')"
                        />

                        <x-admin::form.control-group.error control-name="hostname" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.general.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.create.card.design.before') !!}

                <!-- Logo and Design -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.settings.channels.create.design')
                    </p>

                    <!-- Theme Selector -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.channels.create.theme')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            id="theme"
                            name="theme"
                            :value="config('themes.admin-default')"
                            :label="trans('admin::app.settings.channels.create.theme')"
                        >
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
                        <div class="flex w-2/5 flex-col">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.channels.create.logo')
                                </x-admin::form.control-group.label>

                                <x-admin::media.images
                                    name="logo"
                                    width="110px"
                                    height="110px"
                                />
                            </x-admin::form.control-group>

                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.channels.create.logo-size')
                            </p>
                        </div>


                        <!-- Favicon -->
                        <div class="flex w-2/5 flex-col">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.channels.create.favicon')
                                </x-admin::form.control-group.label>

                                <x-admin::media.images
                                    name="favicon"
                                    width="110px"
                                    height="110px"
                                />
                            </x-admin::form.control-group>

                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.channels.create.favicon-size')
                            </p>
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.design.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.create.card.seo.before') !!}

                <!-- Home Page SEO -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.settings.channels.create.seo')
                    </p>

                    <!-- SEO Title & Description Blade Componnet -->
                    <x-admin::seo/>

                    <!-- SEO Title -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.create.seo-title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="meta_title"
                            name="seo_title" 
                            rules="required"
                            :value="old('seo_title')"
                            :label="trans('admin::app.settings.channels.create.seo-title')"
                            :placeholder="trans('admin::app.settings.channels.create.seo-title')"
                        />

                        <x-admin::form.control-group.error control-name="seo_title" />
                    </x-admin::form.control-group>

                    <!-- SEO Keywords -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.create.seo-keywords')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            id="seo_keywords"
                            name="seo_keywords"
                            rules="required"
                            :value="old('seo_keywords') "
                            :label="trans('admin::app.settings.channels.create.seo-keywords')"
                            :placeholder="trans('admin::app.settings.channels.create.seo-keywords')"
                        />

                        <x-admin::form.control-group.error control-name="seo_keywords" />
                    </x-admin::form.control-group>

                    <!-- SEO Description -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.channels.create.seo-description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            id="meta_description"
                            name="seo_description"
                            rules="required"
                            :value="old('seo_description')"
                            :label="trans('admin::app.settings.channels.create.seo-description')"
                            :placeholder="trans('admin::app.settings.channels.create.seo-description')"
                        />

                        <x-admin::form.control-group.error control-name="seo_description" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.seo.after') !!}

            </div>
            <!-- Right sub-component -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">

                {!! view_render_event('bagisto.admin.settings.channels.create.card.accordion.currencies_and_locales.before') !!}

                <!-- Currencies and Locales -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.channels.create.currencies-and-locales')
                        </p>
                    </x-slot>
            
                    <x-slot:content>
                        <!-- Locale Checkboxes  -->
                        <div class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.locales')
                            </x-admin::form.control-group.label>
                        
                            @foreach (core()->getAllLocales() as $locale)
                                <x-admin::form.control-group class="!mb-2 flex items-center gap-2.5">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        :id="'locales_' . $locale->id"
                                        name="locales[]"
                                        rules="required"
                                        :value="$locale->id"
                                        :for="'locales_' . $locale->id"
                                        :label="trans('admin::app.settings.channels.create.locales')"
                                    />

                                    <label
                                        class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                        for="locales_{{ $locale->id }}"
                                    >
                                        {{ $locale->name }} 
                                    </label>
                                </x-admin::form.control-group>
                            @endforeach

                            <x-admin::form.control-group.error control-name="locales[]" />
                        </div>

                        <!-- Default Locale Selector -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.default-locale')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                id="default_locale_id"
                                name="default_locale_id"
                                rules="required"
                                :value="old('default_locale_id')"
                                :label="trans('admin::app.settings.channels.create.default-locale')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('admin::app.settings.channels.create.select-default-locale')
                                </option>

                                @foreach (core()->getAllLocales() as $locale)
                                    <option value="{{ $locale->id }}" {{ old('default_locale_id') == $locale->id ? 'selected' : '' }}>
                                        {{ $locale->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="default_locale_id" />
                        </x-admin::form.control-group>

                        <!-- Currencies Checkboxes -->
                        <div class="mb-4">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.currencies')
                            </x-admin::form.control-group.label>
                        
                            @foreach (core()->getAllCurrencies() as $currency)
                                <x-admin::form.control-group class="!mb-2 flex items-center gap-2.5">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        :id="'currencies_' . $currency->id"
                                        name="currencies[]" 
                                        rules="required"
                                        :value="$currency->id"
                                        :for="'currencies_' . $currency->id"
                                        :label="trans('admin::app.settings.channels.create.currencies')"
                                    />

                                    <label
                                        class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
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
                                @lang('admin::app.settings.channels.create.default-currency')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                id="base_currency_id"
                                name="base_currency_id"
                                rules="required"
                                :value="old('base_currency_id')"
                                :label="trans('admin::app.settings.channels.create.default-currency')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('admin::app.settings.channels.create.select-default-currency')
                                </option>

                                @foreach (core()->getAllCurrencies() as $currency)
                                    <option value="{{ $currency->id }}" {{ old('base_currency_id') == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="base_currency_id" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.accordion.currencies_and_locales.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.create.card.accordion.settings.before') !!}

                <!-- settings -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.channels.create.settings')
                            </p>
                        </div>
                    </x-slot>
            
                    <x-slot:content>
                        <!-- Maintenance Mode Text  -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.create.maintenance-mode-text')
                            </x-admin::form.control-group.label>
                            
                            <x-admin::form.control-group.control
                                type="text"
                                id="maintenance-mode-text"
                                name="maintenance_mode_text"
                                :value="old('maintenance_mode_text')"
                                :label="trans('admin::app.settings.channels.create.maintenance-mode-text')"
                                :placeholder="trans('admin::app.settings.channels.create.maintenance-mode-text')"
                            />
                        
                            <x-admin::form.control-group.error control-name="maintenance_mode_text" />
                        </x-admin::form.control-group>

                        <!-- Allowed API's  -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="!text-gray-800 dark:!text-white">
                                @lang('admin::app.settings.channels.create.allowed-ips')
                            </x-admin::form.control-group.label>
                            
                            <x-admin::form.control-group.control
                                type="text"
                                id="allowed-ips"
                                name="allowed_ips"
                                :value="old('allowed_ips')"
                                :label="trans('admin::app.settings.channels.create.allowed-ips')"
                                :placeholder="trans('admin::app.settings.channels.create.allowed-ips')"
                            />
                            
                            <x-admin::form.control-group.error control-name="allowed_ips" />
                        </x-admin::form.control-group>

                        <!-- Maintenance Mode Switcher -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.create.status')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="switch"
                                id="maintenance-mode-status"
                                name="is_maintenance_on"
                                :value="1"
                                :checked="false"
                            />

                            <x-admin::form.control-group.error control-name="is_maintenance_on" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.accordion.settings.after') !!}

            </div>
        </div>

        {!! view_render_event('admin.settings.channels.create.create_form_controls.after') !!}

    </x-admin::form> 

    {!! view_render_event('bagisto.admin.settings.channels.create.after') !!}
</x-admin::layouts>
