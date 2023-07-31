<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.sitemaps.edit.title')
    </x-slot:title>

    {{-- Input Form --}}
    <x-admin::form :action="route('admin.sitemaps.update', $sitemap->id)">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.sitemaps.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a href="{{ route('admin.sitemaps.index') }}">
                    <span class="text-gray-600 leading-[24px]">
                        @lang('admin::app.marketing.sitemaps.edit.cancel-btn')
                    </span>
                </a>

                {{-- Save Button --}}
                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.marketing.sitemaps.edit.save-btn')
                </button>
            </div>
        </div>

        {{-- Informations --}}
        <div class="flex gap-[10px] mt-[28px] mb-2">
            <div class="flex flex-col gap-[8px] flex-1">
                {{-- General Section --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.marketing.sitemaps.edit.general')
                    </p>

                    <div class="mb-[10px]">
                        <!-- File Name -->
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="!mt-0">
                                @lang('admin::app.marketing.sitemaps.edit.file-name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="file_name"
                                value="{{ old('file_name') ?: $sitemap->file_name }}"
                                rules="required"
                                label="{{ trans('admin::app.marketing.sitemaps.edit.file-name') }}"
                                placeholder="{{ trans('admin::app.marketing.sitemaps.edit.file-name') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="file_name"
                            >
                            </x-admin::form.control-group.error>

                            <p class="mt-[8px] ml-[4px] text-[12px] text-gray-600 font-medium">
                                @lang('admin::app.marketing.sitemaps.edit.file-name-info')
                            </p>

                        </x-admin::form.control-group>

                        <!---- File Path -->
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.sitemaps.edit.path')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="path"
                                value="{{ old('path') ?: $sitemap->path }}"
                                rules="required"
                                label="{{ trans('admin::app.marketing.sitemaps.edit.path') }}"
                                placeholder="{{ trans('admin::app.marketing.sitemaps.edit.path') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="path"
                            >
                            </x-admin::form.control-group.error>

                            <p class="mt-[8px] ml-[4px] text-[12px] text-gray-600 font-medium">
                                @lang('admin::app.marketing.sitemaps.edit.path-info')
                            </p>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>