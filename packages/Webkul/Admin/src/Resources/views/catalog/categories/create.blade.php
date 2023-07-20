<x-admin::layouts>
    {{-- Input Form --}}
    <x-admin::form :action="route('admin.catalog.categories.store')">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.catalog.categories.create.add-new-category')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <a href="{{ route('admin.catalog.categories.index') }}">
                    <span class="text-gray-600 leading-[24px]">
                        @lang('admin::app.catalog.categories.create.cancel')
                    </span>
                </a>

                <button 
                    type="submit" 
                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                >
                    @lang('admin::app.catalog.categories.create.create-order')
                </button>
            </div>
        </div>

        {{-- Filter row --}}
        <div class="flex gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                <div class="">
                    <div class="inline-flex gap-x-[8px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                        <span class="icon-store text-[24px]"></span>

                        Default Store

                        <span class="icon-sort-down text-[24px]"></span>
                    </div>

                    <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow"></div>
                </div>

                {{-- Language changer --}}
                <div class="">
                    <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                        <span class="icon-language text-[24px] "></span>

                        English

                        <span class="icon-sort-down text-[24px]"></span>
                    </div>

                    <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow"></div>
                </div>
            </div>

        </div>

        {{-- body content --}}
        <div class="flex gap-[10px] mt-[14px]">
            <div class=" flex flex-col gap-[8px] flex-1">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.catalog.categories.create.general')
                    </p>

                    {{-- 
                        Group by locale :- name, slug, description etc (if meta details are available)     
                    --}}

                    <div class="mb-[10px]">
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.company-name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                rules="required"
                                label="Category Name"
                                placeholder="Category Name"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- 
                            Add Slug input field here 
                            The Old method we are using slugify
                        --}}
                    </div>

                    <div class="mb-[10px]">
                        <label 
                            class="block text-[12px]  text-gray-800 font-medium leading-[24px] mb-[10px]" 
                            for="username"
                        >
                            @lang('admin::app.catalog.categories.create.select-parent-category')
                        </label>

                        <div class="flex flex-col gap-[12px]">
                            
                            {{-- Radio select button --}}
                            <div class="inline-flex items-center px-[4px] text-gray-600 cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]">
                                <span class="icon-sort-right text-[24px]"></span>
                                <input 
                                    type="radio" 
                                    class="hidden peer"
                                    id="radio" 
                                    value="{{ old('parent_id') }} ?? 1" 
                                    name="parent_id"
                                >

                                <span class="icon-radio-normal mr-[4px] text-[24px] rounded-[6px] cursor-pointer peer-checked:text-blue-600 peer-checked:icon-radio-selected peer-checked:text-navyBlue"></span>

                                <label 
                                    for="radio" 
                                    class="text-[14px] cursor-pointer"
                                >
                                    @lang('Root')
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Description and images -->
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <x-admin::form.control-group class="mb-4">
                        <x-admin::form.control-group.label class="!font-semibold !text-[16px]">
                            @lang('admin::app.catalog.categories.create.description-and-images')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="description"
                            class="mt-3"
                            value="{{ old('description') }}"
                            rules="required"
                            label="Description"
                            placeholder="Description"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="description"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <div class="flex">
                        <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.category-logo')
                            </p>

                            <p class="text-gray-500">Image resolution should be like 20px X 20px</p>

                            <div class="w-[120px] h-[]">
                                <x-admin::form.control-group class="mt-[15px]">
                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="image[]"
                                        class="mb-0 !p-0 rounded-[12px] text-gray-700"
                                        :label="trans('Image')"
                                        :is-multiple="false"
                                        accepted-types="image/*"
                                        :src="isset($customer) ? $customer->image_url : ''"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="image[]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>

                            <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.image')
                            </p>

                            <p class="text-gray-500">Rectangular Image for Category Page</p>

                            <div class="w-[120px] h-[]">
                                <x-admin::form.control-group class="mt-[15px]">
                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="category_icon_image[]"
                                        class="mb-0 !p-0 rounded-[12px] text-gray-700"
                                        :label="trans('Image')"
                                        :is-multiple="false"
                                        accepted-types="image/*"
                                        :src="isset($customer) ? $customer->category_icon_image : ''"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="category_icon_image[]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right sub-component --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
            
                {{-- Basic Settings --}}
                <div class="bg-white rounded-[4px] box-shadow">
                    <div class="flex items-center justify-between p-[6px]">
                            <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                @lang('admin::app.catalog.categories.create.basic-settings')
                            </p>

                            <span class="icon-arrow-up p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100"></span>
                    </div>
                    
                    <div class="px-[16px] pb-[16px]">
                        {{-- Visible in menu --}}
                        <div class="flex gap-[10px] p-[6px] items-center cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]">
                            <input 
                                type="checkbox" 
                                class="hidden peer"
                                id="checkbox" 
                                name="status" 
                                value="1"
                                required
                            >

                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:text-blue-600 peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                            <label 
                                for="checkbox" 
                                class="text-[14px] text-gray-600 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.catalog.categories.create.visible-in-menu')
                            </label>
                        </div>

                        {{-- Position --}}
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-4">
                                <x-admin::form.control-group.label class="!text-gray-800">
                                    @lang('admin::app.catalog.categories.create.position')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="position"
                                    class="!w-[284px]"
                                    value="{{ old('position') }}"
                                    rules="required"
                                    label="Position"
                                    placeholder="Enter position" {{--@lang('admin::app.catalog.categories.create.enter-position') --}}
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="position"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>

                        {{-- Display Mode  --}}
                        <div class="">
                            <x-admin::form.control-group class="mb-4">
                                <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                    @lang('admin::app.catalog.categories.create.display-mode')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="display_mode"
                                    class="!w-[284px] cursor-pointer"
                                    rules="required"
                                    label="Display Mode"
                                >
                                    <option value="" readonly>@lang('Select Country')</option>

                                    <option value="products_and_description">
                                        @lang('admin::app.catalog.categories.products-and-description')
                                    </option>

                                    <option value="products_only">
                                        @lang('admin::app.catalog.categories.products-only')
                                    </option>

                                    <option value="description_only">
                                        @lang('admin::app.catalog.categories.description-only')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="display_mode"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                    </div>
                </div>

                {{-- Filterable Attributes --}}
                <div class="bg-white rounded-[4px] box-shadow">
                    <div class="flex items-center justify-between p-[6px]">
                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                            @lang('admin::app.catalog.categories.create.filterable-attributes')
                        </p>

                        <span class="icon-arrow-up rounded-[6px] text-[24px] p-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                    </div>

                    {{-- Need to grouping of all the selected value in attributes key --}}
                    <div class="px-[16px] pb-[16px]">
                        @foreach ($attributes as $attribute)
                            <div class="flex gap-[10px] p-[6px] items-center cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]">
                                <input 
                                    type="checkbox" 
                                    id="{{ $attribute->name ? $attribute->name : $attribute->admin_name }}" 
                                    value="{{ $attribute->id }}" 
                                    name="{{ $attribute->name ? $attribute->name : $attribute->admin_name }}" 
                                    class="hidden peer"
                                >

                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:text-blue-600 peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                                <label 
                                    for="{{ $attribute->name ? $attribute->name : $attribute->admin_name }}" 
                                    class="text-[14px] text-gray-600 font-semibold cursor-pointer"
                                >
                                    {{ $attribute->name ? $attribute->name : $attribute->admin_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    {{-- </form> --}}
    </x-admin::form>
</x-admin::layouts>
