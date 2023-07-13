<x-admin::layouts>
    <div class="flex-1 h-full max-w-full px-[16px] pt-[11px] pb-[22px] pl-[275px] max-lg:px-[16px]">
        <form 
            method="POST" 
            action="{{ route('admin.locales.update', $locale->id) }}" 
            enctype="multipart/form-data"
        >
            @csrf

            @method('PUT')

            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('Update Locale')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <button 
                        type="submit" 
                        class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                    >
                        @lang('Save Locale')
                    </button>
                </div>
            </div>

            {!! view_render_event('bagisto.admin.settings.locale.create.before') !!}

            <div class="flex gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
                <div class="flex gap-x-[4px] items-center">
                    <div>
                        <div class="inline-flex gap-x-[8px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-store text-[24px]"></span>

                            @lang('Default Store')

                            <span class="icon-sort-down text-[24px]"></span>
                        </div>

                        <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow"></div>
                    </div>

                    <div>
                        <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-language text-[24px] "></span>

                            English

                            <span class="icon-sort-down text-[24px]"></span>
                        </div>

                        <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow"></div>
                    </div>
                </div>
            </div>

            <div class="flex gap-[10px] mt-[14px]">
                <div class=" flex flex-col gap-[8px] flex-1">
                    <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                        <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                            @lang('General')
                        </p>

                        <div class="mb-[10px]">
                            <label 
                                class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                for="code"
                            >
                                @lang('Code')
                            </label>

                            <input 
                                type="hidden"
                                name="code"
                                value="{{ $locale->code }}"
                            />

                            <input 
                                class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" 
                                type="text" 
                                name="code"
                                value="{{ old('code') ?? $locale->code}}"
                                id="code"
                                placeholder="@lang('Code')"
                                disabled="disabled"
                            >
                        </div>

                        <div class="mb-[10px]">
                            <label 
                                class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                for="name"
                            >
                                @lang('Name')
                            </label>

                            <input 
                                class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" 
                                type="text" 
                                name="name"
                                value="{{ old('name') ?? $locale->name }}"
                                id="name"
                                placeholder="@lang('name')"
                            >
                        </div>

                        <div class="mb-[10px]">
                            <label 
                                class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                for="direction"
                            >
                                @lang('Direction')
                            </label>

                            <select 
                                name="direction"
                                id="direction"
                                class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" 
                            >
                                <option value="ltr" {{ (old('direction') ?? $locale->direction) == 'ltr' ? 'selected' : '' }}>LTR</option>

                                <option value="rtl" {{ (old('direction') ?? $locale->direction) == 'rtl' ? 'selected' : '' }}>RTL</option>
                            </select>
                        </div>

                        <div class="mb-[10px]">
                            <label 
                                class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                for="logo_path"
                            >
                                @lang('Locale Logo')
                            </label>

                            @if (! empty($locale->logo_path))
                                <x-shop::media
                                    name="logo_path[image_1]"
                                    class="py-3"
                                    :is-multiple="false"
                                    accepted-types="image/*"
                                    :src="Storage::url($locale->logo_path)"
                                >
                                </x-shop::media>
                            @else
                                <x-shop::media
                                    name="logo_path[image_1]"
                                    class="py-3"
                                    :is-multiple="false"
                                    accepted-types="image/*"
                                >
                                </x-shop::media>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {!! view_render_event('bagisto.admin.settings.locale.create.after') !!}
        </form>
    </div>
</x-admin::layouts>