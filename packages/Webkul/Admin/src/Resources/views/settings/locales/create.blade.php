<x-admin::layouts>
    <div class="flex-1 h-full max-w-full px-[16px] pt-[11px] pb-[22px] pl-[275px] max-lg:px-[16px]">
        
        <x-shop::form 
            :action="route('admin.locales.store')"
            enctype="multipart/form-data"
        >
            <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
				<p class="text-[20px] text-gray-800 font-bold">
                    @lang('Add Locale')
                </p>

				<div class="flex gap-x-[10px] items-center">
					<button 
                        type="submit"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                    >
                        @lang('Save Locale')
                    </button>
				</div>
			</div>

            <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
				<div class="flex gap-x-[4px] items-center">
					<div class="">
						<div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
							<span class="icon-language text-[24px]"></span> 
                                English 
                            <span class="icon-sort-down text-[24px]"></span>
						</div>
						<div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow"></div>
					</div>
				</div>
			</div>
           
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
				<div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
					<div class="p-[16px] bg-white rounded-[4px] box-shadow">

                        {!! view_render_event('bagisto.admin.settings.locale.create.before') !!}

                        <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                            @lang('General')
                        </p>

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
    
                        {!! view_render_event('bagisto.admin.settings.locale.create.after') !!}
                    </div>
				</div>
			</div>
        </x-admin::form>
    </div>
</x-admin::layouts>