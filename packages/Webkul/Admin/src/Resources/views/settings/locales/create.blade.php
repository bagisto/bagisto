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
            
            <div class="flex gap-[10px] mt-[14px]">
                <div class=" flex flex-col gap-[8px] flex-1">
                    {!! view_render_event('bagisto.admin.settings.locale.create.before') !!}

                    <x-admin::accordion :is-active="true">
                        <x-slot:header>
                            <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                                @lang('General')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    Code
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="code"
                                    id="code"
                                    rules="required"
                                    label="Code"
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
                                    Name
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    id="name"
                                    rules="required"
                                    label="name"
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
                                    Direction
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="direction"
                                    id="direction"
                                    rules="required"
                                    label="direction"
                                >
                                    <option value="ltr" selected title="Text direction left to right">LTR</option>
                
                                    <option value="rtl" title="Text direction right to left">RTL</option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="direction"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('Locale Logo')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="image"
                                    name="logo_path[image_1]"
                                    id="direction"
                                    :label="trans('Logo Path')"
                                    accepted-types="image/*"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="logo_path[image_1]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </x-slot:content>
                    </x-admin::accordion>

                    {!! view_render_event('bagisto.admin.settings.locale.create.after') !!}
                </div>
            </div>
        </x-admin::form>
    </div>
</x-admin::layouts>