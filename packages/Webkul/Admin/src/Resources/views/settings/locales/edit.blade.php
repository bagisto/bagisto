<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.settings.locales.edit.title')
    </x-slot:title>

    <x-admin::form 
        :action="route('admin.settings.locales.update', $locale->id)"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.locales.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Cancel Button -->
                <a href="{{ route('admin.settings.locales.index') }}">
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.settings.locales.edit.back-btn')
                    </span>
                </a>

                <button 
                    type="submit"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.settings.locales.edit.save-btn')
                </button>
            </div>
        </div>

        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                    {!! view_render_event('bagisto.admin.settings.locale.edit.before', ['locale' => $locale]) !!}
                    
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.settings.locales.edit.general')
                    </p>

                    <x-admin::form.control-group.control
                        type="hidden"
                        name="code"
                        :value="old('code') ?? $locale->code"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.locales.edit.code')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="code"
                            :value="old('code') ?? $locale->code"
                            id="code"
                            rules="required"
                            :label="trans('admin::app.settings.locales.edit.code')"
                            :placeholder="trans('admin::app.settings.locales.edit.code')"
                            disabled="disabled"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="code"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.locales.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name') ?? $locale->name"
                            id="name"
                            rules="required"
                            :label="trans('admin::app.settings.locales.edit.name')"
                            :placeholder="trans('admin::app.settings.locales.edit.name')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="name"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
        
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.locales.edit.direction')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="direction"
                            :value="old('direction') ?? $locale->direction"
                            id="direction"
                            rules="required"
                            :label="trans('admin::app.settings.locales.edit.direction')"
                        >
                            <option 
                                value="ltr"
                                title="Text direction left to right"
                                {{ (old('direction') ?? $locale->direction) == 'ltr' ? 'selected' : '' }}
                            >
                                LTR
                            </option>
        
                            <option 
                                value="rtl"
                                title="Text direction right to left"
                                {{ (old('direction') ?: $locale->direction) == 'rtl' ? 'selected' : '' }}
                            >
                                RTL
                            </option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="direction"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
        
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.locales.edit.logo')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="image"
                            name="logo_path[image_1]"
                            id="direction"
                            :label="trans('admin::app.settings.locales.edit.logo-path')"
                            :src="$locale->logo_url"
                            accepted-types="image/*"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="logo_path[image_1]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    {!! view_render_event('bagisto.admin.settings.locale.edit.after', ['locale' => $locale]) !!}
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>