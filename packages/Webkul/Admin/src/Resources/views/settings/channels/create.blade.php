<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.channels.create.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.channels.create.before') !!}

    <x-admin::form  action="{{ route('admin.settings.channels.store') }}" enctype="multipart/form-data">

        {!! view_render_event('admin.settings.channels.create.create_form_controls.before') !!}

        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.channels.create.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a
                    href="{{ route('admin.settings.channels.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.settings.channels.create.cancel')
                </a>

                {{-- Save Button --}}
                <button 
                    type="submit" 
                    class="primary-button"
                >
                    @lang('admin::app.settings.channels.create.save-btn')
                </button>
            </div>
        </div>

        {{-- body content --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left sub-component --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.settings.channels.create.card.general.before') !!}

                {{-- General Information --}}
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.settings.channels.create.general')
                    </p>
                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                :value="old('code')"
                                id="code"
                                rules="required"
                                :label="trans('admin::app.settings.channels.create.code')"
                                :placeholder="trans('admin::app.settings.channels.create.code')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="code"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                :value="old('name')"
                                id="name"
                                rules="required"
                                :label="trans('admin::app.settings.channels.create.name')"
                                :placeholder="trans('admin::app.settings.channels.create.name')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.create.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description')"
                                id="description"
                                :label="trans('admin::app.settings.channels.create.description')"
                                :placeholder="trans('admin::app.settings.channels.create.description')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <div class="mb-[10px]">
                            <p class="required block leading-[24px] text-[12px] text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.settings.channels.create.inventory-sources')
                            </p>

                            @foreach (app('Webkul\Inventory\Repositories\InventorySourceRepository')->findWhere(['status' => 1]) as $inventorySource)
                                <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="inventory_sources[]"
                                        :value="$inventorySource->id "
                                        :id="'inventory_sources_' . $inventorySource->id"
                                        :for="'inventory_sources_' . $inventorySource->id"
                                        rules="required"
                                        :label="trans('admin::app.settings.channels.create.inventory-sources')"
                                    >
                                    </x-admin::form.control-group.control>
                                        
                                    <x-admin::form.control-group.label 
                                        :for="'inventory_sources_' . $inventorySource->id"
                                        class="!text-[14px] !text-gray-600 dark:!text-gray-300 font-semibold cursor-pointer"
                                    >
                                        {{ $inventorySource->name }}
                                    </x-admin::form.control-group.label>

                                </x-admin::form.control-group>
                            @endforeach 

                            <x-admin::form.control-group.error
                                control-name="inventory_sources[]"
                            >
                            </x-admin::form.control-group.error>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.root-category')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="root_category_id"
                                :value="old('root_category_id')"
                                id="root_category_id"
                                rules="required"
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

                            <x-admin::form.control-group.error
                                control-name="root_category_id"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.create.hostname')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="hostname"
                                :value="old('hostname')"
                                id="hostname"
                                :label="trans('admin::app.settings.channels.create.hostname')"
                                :placeholder="trans('admin::app.settings.channels.create.hostname-placeholder')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="hostname"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.general.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.create.card.design.before') !!}

                {{-- Logo and Design --}}
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.settings.channels.create.design')
                    </p>

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.channels.create.theme')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="theme"
                                :value="old('theme')"
                                id="theme"
                                :label="trans('admin::app.settings.channels.create.theme')"
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
                                        @lang('admin::app.settings.channels.create.logo')
                                    </x-admin::form.control-group.label>

                                    <x-admin::media.images
                                        name="logo"
                                        width="220px"
                                    >
                                    </x-admin::media.images>
                                </x-admin::form.control-group>
                                <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.settings.channels.create.logo-size')
                                </p>
                            </div>

                            <div class="flex flex-col w-[40%]">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.channels.create.favicon')
                                    </x-admin::form.control-group.label>

                                    <x-admin::media.images
                                        name="favicon"
                                        width="220px"
                                    >
                                    </x-admin::media.images>
                                </x-admin::form.control-group>
                                <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.settings.channels.create.favicon-size')
                                </p>
                            </div>
                        </div>
                    </div>    
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.design.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.create.card.seo.before') !!}

                {{-- Home Page SEO --}} 
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.settings.channels.create.seo')
                    </p>

                    {{-- SEO Title & Description Blade Componnet --}}
                    <x-admin::seo/>

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.seo-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="seo_title" 
                                :value="old('seo_title')"
                                id="meta_title"
                                rules="required"
                                :label="trans('admin::app.settings.channels.create.seo-title')"
                                :placeholder="trans('admin::app.settings.channels.create.seo-title')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_title"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.seo-keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="seo_keywords"
                                :value="old('seo_keywords') "
                                id="seo_keywords"
                                rules="required"
                                :label="trans('admin::app.settings.channels.create.seo-keywords')"
                                :placeholder="trans('admin::app.settings.channels.create.seo-keywords')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_keywords"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.channels.create.seo-description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="seo_description"
                                :value="old('seo_description')"
                                id="meta_description"
                                rules="required"
                                :label="trans('admin::app.settings.channels.create.seo-description')"
                                :placeholder="trans('admin::app.settings.channels.create.seo-description')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.seo.after') !!}

            </div>
            {{-- Right sub-component --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">

                {!! view_render_event('bagisto.admin.settings.channels.create.card.accordion.currencies_and_locales.before') !!}

                {{-- Currencies and Locales --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                @lang('admin::app.settings.channels.create.currencies-and-locales')
                            </p>
                        </div>
                    </x-slot:header>
            
                    <x-slot:content>
                        {{-- Locale  --}}
                        <div class="mb-[10px]">
                            <div class="mb-[10px]">
                                <p class="required block leading-[24px] text-gray-800 dark:text-white font-medium">
                                    @lang('admin::app.settings.channels.create.locales')
                                </p>
                            
                                @foreach (core()->getAllLocales() as $locale)
                                    <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                        <x-admin::form.control-group.control
                                            type="checkbox"
                                            name="locales[]"
                                            :value="$locale->id"
                                            :id="'locales_' . $locale->id"
                                            :for="'locales_' . $locale->id"
                                            rules="required"
                                            :label="trans('admin::app.settings.channels.create.locales')"
                                        >
                                        </x-admin::form.control-group.control>
                                            
                                        <x-admin::form.control-group.label 
                                            :for="'locales_' . $locale->id"
                                            class="!text-[14px] !text-gray-600 dark:!text-gray-300 font-semibold cursor-pointer"
                                        >
                                            {{ $locale->name }} 
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
                                    @lang('admin::app.settings.channels.create.default-locale')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="default_locale_id"
                                    :value="old('default_locale_id')"
                                    id="default_locale_id"
                                    rules="required"
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

                                <x-admin::form.control-group.error
                                    control-name="default_locale_id"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <div class="mb-[10px]">
                                <p class="required block leading-[24px] text-gray-800 dark:text-white font-medium">
                                    @lang('admin::app.settings.channels.create.currencies')
                                </p>
                            
                                @foreach (core()->getAllCurrencies() as $currency)
                                    <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                        <x-admin::form.control-group.control
                                            type="checkbox"
                                            name="currencies[]" 
                                            :value="$currency->id"
                                            :id="'currencies_' . $currency->id"
                                            :for="'currencies_' . $currency->id"
                                            rules="required"
                                            :label="trans('admin::app.settings.channels.create.currencies')"
                                        >
                                        </x-admin::form.control-group.control>
                                            
                                        <x-admin::form.control-group.label 
                                            :for="'currencies_' . $currency->id"
                                            class="!text-[14px] !text-gray-600 dark:!text-gray-300 font-semibold cursor-pointer"
                                        >
                                            {{ $currency->name }}  
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
                                    @lang('admin::app.settings.channels.create.default-currency')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="base_currency_id"
                                    :value="old('base_currency_id')"
                                    id="base_currency_id"
                                    rules="required"
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

                                <x-admin::form.control-group.error
                                    control-name="base_currency_id"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                    </x-slot:content>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.channels.create.card.accordion.currencies_and_locales.after') !!}

                {!! view_render_event('bagisto.admin.settings.channels.create.card.accordion.settings.before') !!}

                {{-- settings --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                @lang('admin::app.settings.channels.create.settings')
                            </p>
                        </div>
                    </x-slot:header>
            
                    <x-slot:content>
                        {{-- Maintenance Mode  --}}
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.channels.create.maintenance-mode-text')
                                </x-admin::form.control-group.label>
                                
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="maintenance_mode_text"
                                    :value="old('maintenance_mode_text')"
                                    id="maintenance-mode-text"
                                    :label="trans('admin::app.settings.channels.create.maintenance-mode-text')"
                                    :placeholder="trans('admin::app.settings.channels.create.maintenance-mode-text')"
                                >
                                </x-admin::form.control-group.control>
                            
                                <x-admin::form.control-group.error
                                    control-name="maintenance_mode_text"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                    
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="!text-gray-800 dark:!text-white">
                                    @lang('admin::app.settings.channels.create.allowed-ips')
                                </x-admin::form.control-group.label>
                                
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="allowed_ips"
                                    :value="old('allowed_ips')"
                                    id="allowed-ips"
                                    :label="trans('admin::app.settings.channels.create.allowed-ips')"
                                    :placeholder="trans('admin::app.settings.channels.create.allowed-ips')"
                                >
                                </x-admin::form.control-group.control>
                                
                                <x-admin::form.control-group.error
                                    control-name="allowed_ips"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.channels.create.status')
                                </x-admin::form.control-group.label>
                                <x-admin::form.control-group.control
                                    type="switch"
                                    name="is_maintenance_on"
                                    :value="1"
                                    id="maintenance-mode-status"
                                    :checked="false"
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

                {!! view_render_event('bagisto.admin.settings.channels.create.card.accordion.settings.after') !!}

            </div>
        </div>

        {!! view_render_event('admin.settings.channels.create.create_form_controls.after') !!}

    </x-admin::form> 

    {!! view_render_event('bagisto.admin.settings.channels.create.after') !!}

</x-admin::layouts>
