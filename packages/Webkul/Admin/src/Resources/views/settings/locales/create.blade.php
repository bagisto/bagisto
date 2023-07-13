<x-admin::layouts>
    <div class="flex-1 h-full max-w-full px-[16px] pt-[11px] pb-[22px] pl-[275px] max-lg:px-[16px]">
        
        <x-shop::form 
            :action="route('admin.locales.store')"
            enctype="multipart/form-data"
        >
            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('Add Locale')
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
            
            <div class="flex gap-[10px] mt-[14px]">
                <div class=" flex flex-col gap-[8px] flex-1">
                    <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                        <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                            @lang('General')
                        </p>
            
                        <div class="mb-[10px]">
                            {{-- <label 
                                class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                for="code"
                            >
                                @lang('Code')
                            </label>
            
                            <input 
                                class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" 
                                type="text" 
                                name="code"
                                value="{{ old('name') }}"
                                id="code"
                                placeholder="@lang('Code')"
                            > --}}
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    Email
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="email"
                                    name="email"
                                    id="email"
                                    rules="required|email"
                                    label="Email"
                                    placeholder="email@example.com"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="email"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
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
                                value="{{ old('name') }}"
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
                                <option value="ltr" selected title="Text direction left to right">LTR</option>
            
                                <option value="rtl" title="Text direction right to left">RTL</option>
                            </select>
                        </div>
            
                        <div class="mb-[10px]">
                            <label 
                                class="block text-[12px]  text-gray-800 font-medium leading-[24px]" 
                                for="logo_path"
                            >
                                @lang('Locale Logo')
                            </label>
            
                            <x-shop::media
                                name="logo_path[image_1]"
                                class="py-3"
                                :is-multiple="false"
                                accepted-types="image/*"
                            >
                            </x-shop::media>
                        </div>
                    </div>
                </div>
            </div>
        
        </x-admin::form>
        {!! view_render_event('bagisto.admin.settings.locale.create.after') !!}
    </div>
</x-admin::layouts>