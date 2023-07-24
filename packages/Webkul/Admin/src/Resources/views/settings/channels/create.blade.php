<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('Add Channels')
    </x-slot:title>
    <x-admin::form  action="{{ route('admin.channels.store') }}" enctype="multipart/form-data">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('Add Channel')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <a href="{{ route('admin.channels.index') }}">
                    <span class="text-gray-600 leading-[24px]">
                        @lang('admin::app.catalog.categories.create.cancel')
                    </span>
                </a>

                <button 
                    type="submit" 
                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                >
                    @lang('Save Channel')
                </button>
            </div>
        </div>
        {{-- body content --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left sub-component --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    {{-- General Information --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('General')
                    </p>
                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                :value="old('code')"
                                id="code"
                                rules="required"
                                :label="trans('Code')"
                                :placeholder="trans('Code')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="code"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                :value="old('name')"
                                id="name"
                                rules="required"
                                :label="trans('Name')"
                                :placeholder="trans('Name')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description')"
                                id="description"
                                :label="trans('Description')"
                                :placeholder="trans('Description')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <div class="mb-[10px]">
                            <p class="block leading-[24px] text-[12px] text-gray-800 font-medium">@lang('Inventory Sources')</p>
                    
                            @foreach (app('Webkul\Inventory\Repositories\InventorySourceRepository')->findWhere(['status' => 1]) as $inventorySource)
                                <label 
                                    class="flex gap-[10px] w-max items-center p-[6px] cursor-pointer select-none"
                                    for="inventory_sources_{{ $inventorySource->id }}"
                                >
                                    <input 
                                        type="checkbox" 
                                        name="inventory_sources[]"
                                        id="inventory_sources_{{ $inventorySource->id }}"
                                        value="{{ $inventorySource->id }}"
                                        {{ in_array($inventorySource->id, old('inventory_sources', [])) ? 'checked' : '' }}
                                        class="hidden peer"
                                    >
                    
                                    <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                    
                                    <div class="text-[14px] text-gray-600 font-semibold cursor-pointer">
                                        {{ $inventorySource->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Root Category')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="root_category_id"
                                :value="old('name')"
                                id="root_category_id"
                                rules="required"
                                :label="trans('Root Category')"
                                :placeholder="trans('Root Category')"
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
                                @lang('Host Name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="hostname"
                                :value="old('hostname')"
                                id="hostname"
                                :label="trans('Host Name')"
                                :placeholder="trans('https://www.example.com (Don\'t add slash in the end.)')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="hostname"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {{-- Logo and Design --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('Design')
                    </p>

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Theme')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="theme"
                                id="theme"
                                :label="trans('Theme')"
                            >
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
                                        @lang('Logo')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.control
                                            type="image"
                                            name="logo[image_1]"
                                            :label="trans('Logo')"
                                            :is-multiple="false"
                                            accepted-types="image/*"
                                        >
                                        </x-admin::form.control-group.control>
    
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group.error
                                        control-name="logo[image_1]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                <p class="text-[12px] text-gray-600">Image resolution should be like 192px X 50px</p>
                            </div>

                            <div class="flex flex-col w-[40%]">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('Favicon')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="fevicon[image_1]"
                                        :label="trans('Favicon')"
                                        :is-multiple="false"
                                        accepted-types="image/*"
                                        :src="isset($customer) ? $customer->image_url : ''"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="fevicon[image_1]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                <p class="text-[12px] text-gray-600">Image resolution should be like 16px X 16px</p>
                            </div>
                        </div>
                    </div>    
                </div>

                {{-- Home Page SEO --}} 
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('Home page SEO ')
                    </p>

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Meta title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="seo_title" 
                                :value="old('seo_title')"
                                id="seo_title"
                                rules="required"
                                :label="trans('Meta title')"
                                :placeholder="trans('Meta title')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_title"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Meta description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="seo_description"
                                :value="old('seo_description')"
                                id="seo_description"
                                rules="required"
                                :label="trans('Meta description')"
                                :placeholder="trans('Meta description')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('Meta keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="seo_keywords"
                                :value="old('seo_keywords') "
                                id="seo_keywords"
                                :label="trans('Meta keywords')"
                                :placeholder="trans('Meta keywords')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="seo_keywords"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
            {{-- Right sub-component --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                {{-- component 1 --}}
                <div class="bg-white rounded-[4px] box-shadow">
                    <x-admin::accordion>
                        <x-slot:header>
                            <div class="flex items-center justify-between p-[6px]">
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('Currencies and Locales ')
                                </p>
                            </div>
                        </x-slot:header>
                
                        <x-slot:content>
                            {{-- Locale  --}}
                            <div class="mb-[10px]">
                                <div class="mb-[10px]">
                                    <p class="block leading-[24px] text-gray-800 font-medium">
                                        @lang('Locales')
                                    </p>
                                
                                    @foreach (core()->getAllLocales() as $locale)
                                        <label 
                                            class="flex gap-[10px] w-max items-center p-[6px] cursor-pointer select-none"
                                            for="locales_{{ $locale->id }}"
                                        >
                                            <input 
                                                type="checkbox" 
                                                name="locales[]"
                                                id="locales_{{ $locale->id }}" 
                                                value="{{ $locale->id }}"
                                                {{ in_array($locale->id, old('locales', [])) ? 'checked' : '' }}
                                                class="hidden peer"
                                            >
                                
                                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                                
                                            <p class="text-gray-600 font-semibold cursor-pointer">
                                                {{ $locale->name }} 
                                            </p>
                                        </label>
                                    @endforeach
                                </div>
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Default Locale')
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="default_locale_id"
                                        id="default_locale_id"
                                        rules="required"
                                        label="Default Locale"
                                    >
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
                                    <p class="block leading-[24px] text-gray-800 font-medium">
                                        @lang('Currencies')
                                    </p>
                                
                                    @foreach (core()->getAllCurrencies() as $currency)
                                        <label 
                                            class="flex gap-[10px] w-max items-center p-[6px] cursor-pointer select-none"
                                            for="{{ $currency->id }}"
                                        >
                                            <input 
                                                type="checkbox" 
                                                name="currencies[]" 
                                                id="{{ $currency->id }}" 
                                                value="{{ $currency->id }}"
                                                {{ in_array($currency->id, old('currencies', [])) ? 'checked' : '' }}
                                                class="hidden peer"
                                            >
                                
                                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                                
                                            <p class="text-gray-600 font-semibold cursor-pointer">
                                                {{ $currency->name }} 
                                            </p>
                                        </label>
                                    @endforeach
                                </div>
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Default Currency')
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="base_currency_id"
                                        id="base_currency_id"
                                        rules="required"
                                        label="Default Currency"
                                    >
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
                </div>

                {{-- component 2 --}}
                <div class="bg-white rounded-[4px] box-shadow">
                    <x-admin::accordion>
                        <x-slot:header>
                            <div class="flex items-center justify-between p-[6px]">
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('Maintenance Mode')
                                </p>
                            </div>
                        </x-slot:header>
                
                        <x-slot:content>
                            {{-- Maintenance Mode  --}}
                            <div class="mb-[10px]">
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('status')
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="is_maintenance_on"
                                        value="1"
                                        id="maintenance-mode-status"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <x-admin::form.control-group.error
                                        control-name="is_maintenance_on"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Message')
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="maintenance_mode_text"
                                        id="maintenance-mode-text"
                                        label="Message"
                                        placeholder="Message"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <x-admin::form.control-group.error
                                        control-name="maintenance_mode_text"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="!text-gray-800">
                                        @lang('Allowed IPs')
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="allowed_ips"
                                        id="allowed-ips"
                                        label="Allowed IPs"
                                        placeholder="Allowed IPs"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <x-admin::form.control-group.error
                                        control-name="allowed_ips[]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </x-slot:content>
                    </x-admin::accordion>
                </div>
            </div>
        </div>
    </x-admin::form> 
</x-admin::layouts>
