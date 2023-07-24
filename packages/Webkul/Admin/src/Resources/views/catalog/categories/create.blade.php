<x-admin::layouts>
    {{-- Input Form --}}
    <x-admin::form 
        :action="route('admin.catalog.categories.store')"
        enctype="multipart/form-data"
    >
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

        {{-- Full Pannel --}}
        <div class="flex gap-[10px] mt-[14px]">
            {{-- Left Section --}}
            <div class=" flex flex-col gap-[8px] flex-1">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.categories.create.general')
                    </p>

                    {{-- Locales --}}
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="locale"
                        value="all"
                    >
                    </x-admin::form.control-group.control>

                    {{-- Name --}}
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
                    </div>

                    {{-- Parent category --}}
                    <div class="mb-[10px]">
                        <label
                            class="block mb-[10px] text-[12px] text-gray-800 font-medium leading-[24px]"
                            for="username"
                        >
                            @lang('admin::app.catalog.categories.create.select-parent-category')
                        </label>

                        {{-- Radio select button --}}
                        <div class="flex flex-col gap-[12px]">
                            @foreach ($categories as $category)
                                <label
                                    for="{{ $category->id }}"
                                    class="inline-flex items-center w-max px-[4px] text-gray-600 cursor-pointer select-none"
                                >
                                    <span class="icon-sort-right text-[24px]"></span>
                                    <input
                                        type="radio"
                                        name="parent_id"
                                        id="{{ $category->id }}"
                                        class="hidden peer"
                                        value="{{ old('parent_id')  ?? $category->id }}"
                                    >

                                    <span class="icon-radio-normal mr-[4px] text-[24px] rounded-[6px] cursor-pointer peer-checked:text-blue-600 peer-checked:icon-radio-selected peer-checked:text-navyBlue"></span>

                                    <div class="text-[14px] cursor-pointer">
                                        {{ $category->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Description and images -->
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.categories.create.description-and-images')
                    </p>

                    <x-admin::form.control-group class="mb-4">
                        <x-admin::form.control-group.control
                            type="tinymce"
                            name="description"
                            id="description"
                            class="description mt-3"
                            value="{{ old('description') }}"
                            rules="required"
                            label="{{ trans('admin::app.catalog.categories.create.description') }}"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="description"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <div class="flex gap-[50px]">
                        {{-- Add Logo --}}
                        <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.logo')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.create.logo-size')
                            </p>

                           
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="image"
                                    name="logo_path[image_1]"
                                    class="mb-0 !p-0 rounded-[12px] text-gray-700"
                                    :label="trans('admin::app.catalog.categories.create.add-logo')"
                                    :is-multiple="false"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="image[]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>

                        {{-- Add Banner --}}
                        <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.banner')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.create.banner-size')
                            </p>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="image"
                                    name="banner_path[]"
                                    class="mb-0 !p-0 rounded-[12px] text-gray-700"
                                    :width="200"
                                    :height="110"
                                    :label="trans('admin::app.catalog.categories.create.add-banner')"
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

                {{-- SEO Deatils --}}
                <div class="p-[16px] bg-white rounded box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.catalog.categories.create.seo-details')
                    </p>

                    <div class="flex flex-col gap-[3px]">
                        <p class="text-[#161B9D]">
                            @{{ metaTitle }}
                        </p>

                        {{-- Get Meta Title From v-model and convet in lower case also replace space with (-) --}}
                        <p class="text-[#135F29]">
                            {{ URL::to('/') }}/@{{ metaTitle ? metaTitle.toLowerCase().replace(/\s+/g, '-') : '' }}
                        </p>

                        {{-- Get Meta Description From v-model --}}
                        <p class="text-gray-600">
                            @{{ metaDescription }}
                        </p>
                    </div>

                    <div class="mt-[30px]">
                        {{-- Meta Title --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="meta_title"
                                value="{{ old('meta_title') }}"
                                rules="required"
                                label="{{ trans('admin::app.catalog.categories.create.meta-title') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.meta-title') }}"
                                v-model="metaTitle"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="meta_title"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Slug --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.slug')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="slug"
                                value="{{ old('slug') }}"
                                rules="required"
                                label="{{ trans('admin::app.catalog.categories.create.meta-title') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.meta-title') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="slug"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Meta Keywords --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="meta_keywords"
                                value="{{ old('meta_keywords') }}"
                                rules="required"
                                label="{{ trans('admin::app.catalog.categories.create.meta-keywords') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.meta-keywords') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="meta_keywords"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Meta Description --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="meta_description"
                                value="{{ old('meta_description') }}"
                                rules="required"
                                label="{{ trans('admin::app.catalog.categories.create.meta-description') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.meta-description') }}"
                                v-model="metaDescription"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="meta_description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>

            {{-- Right Section --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
                {{-- Settings --}}
                <x-accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.catalog.categories.create.settings')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content>
                        {{-- Visible in menu --}}
                        <label 
                            for="checkbox" 
                            class="flex gap-[10px] w-max items-center p-[6px] cursor-pointer select-none"
                        >
                            <input 
                                type="checkbox" 
                                class="hidden peer"
                                id="checkbox" 
                                name="status" 
                                value="1"
                                required
                            >

                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:text-blue-600 peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                            <div class="text-[14px] text-gray-600 font-semibold cursor-pointer">
                                @lang('admin::app.catalog.categories.create.visible-in-menu')
                            </div>
                        </label>

                        {{-- Position --}}
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-4">
                                <x-admin::form.control-group.label class="!text-gray-800">
                                    @lang('admin::app.catalog.categories.create.position')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="position"
                                    value="{{ old('position') }}"
                                    rules="required"
                                    label="{{ trans('admin::app.catalog.categories.create.position') }}"
                                    placeholder="{{ trans('admin::app.catalog.categories.create.enter-position') }}"
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
                                    class="cursor-pointer"
                                    rules="required"
                                    label="{{ trans('admin::app.catalog.categories.create.display-mode') }}"
                                >
                                    <option value="" readonly>
                                        @lang('admin::app.catalog.categories.create.select-display-mode')
                                    </option>

                                    <option value="products_and_description">
                                        @lang('admin::app.catalog.categories.create.products-and-description')
                                    </option>

                                    <option value="products_only">
                                        @lang('admin::app.catalog.categories.create.products-only')
                                    </option>

                                    <option value="description_only">
                                        @lang('admin::app.catalog.categories.create.description-only')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="display_mode"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                    </x-slot:content>
                </x-accordion>

                {{-- Filterable Attributes --}}
                <x-accordion>
                    <x-slot:header>
                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                            @lang('admin::app.catalog.categories.create.filterable-attributes')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content class="pointer-events-none">
                        @foreach ($attributes as $attribute)
                            <label 
                                class="flex gap-[10px] w-max items-center p-[6px] cursor-pointer select-none"
                                for="{{ $attribute->name ?? $attribute->admin_name }}"
                            >
                                <input 
                                    type="checkbox" 
                                    id="{{ $attribute->name ?? $attribute->admin_name }}"
                                    value="{{ $attribute->id }}"
                                    name="attributes[]" 
                                    class="hidden peer"
                                >

                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:text-blue-600 peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                                <div class="text-[14px] text-gray-600 font-semibold cursor-pointer">
                                    {{ $attribute->name ?? $attribute->admin_name }}
                                </div>
                            </label>
                        @endforeach
                    </x-slot:content>
                </x-accordion>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
