<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.catalog.categories.create.title')
    </x-slot:title>

    {{-- Input Form --}}
    <x-admin::form
        :action="route('admin.catalog.categories.store')"
        enctype="multipart/form-data"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.catalog.categories.create.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.catalog.categories.index') }}"
                    class="transparent-button hover:bg-gray-200"
                >
                    @lang('admin::app.catalog.categories.create.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.catalog.categories.create.save-btn')
                </button>
            </div>
        </div>

        {{-- Full Pannel --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left Section --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <!-- General -->
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
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.catalog.categories.create.company-name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name')"
                            class="w-full"
                            :label="trans('admin::app.catalog.categories.create.company-name')"
                            :placeholder="trans('admin::app.catalog.categories.create.company-name')"
                        >
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>

                    <div class="mb-[10px]">
                        {{-- Parent category --}}
                        <label class="block mb-[10px] text-[12px] text-gray-800 font-medium leading-[24px]">
                            @lang('admin::app.catalog.categories.create.parent-category')
                        </label>

                        {{-- Radio select button --}}
                        <div class="flex flex-col gap-[12px]">
                            <x-admin::tree.view
                                input-type="radio"
                                name-field="parent_id"
                                value-field="key"
                                id-field="key"
                                model-value="json_encode($categories)"
                                :items="json_encode($categories)"
                                :fallback-locale="config('app.fallback_locale')"
                            >
                            </x-admin::tree.view>
                        </div>
                    </div>
                </div>

                <!-- Description and images -->
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.categories.create.description-and-images')
                    </p>

                    <!-- Description -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.catalog.categories.create.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            id="description"
                            class="description"
                            :value="old('description')"
                            rules="required"
                            :label="trans('admin::app.catalog.categories.create.description')"
                            :tinymce="true"
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

                            <x-admin::media.images name="logo_path"></x-admin::media.images>
                        </div>

                        {{-- Add Banner --}}
                        <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.banner')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.create.banner-size')
                            </p>

                            <x-admin::media.images
                                name="banner_path"
                                width="220px"
                            >
                            </x-admin::media.images>
                        </div>
                    </div>
                </div>

                {{-- SEO Deatils --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.catalog.categories.create.seo-details')
                    </p>

                    {{-- SEO Title & Description Blade Componnet --}}
                    <x-admin::seo/>

                    <div class="mt-[30px]">
                        {{-- Meta Title --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="meta_title"
                                id="meta_title"
                                :value="old('meta_title')"
                                :label="trans('admin::app.catalog.categories.create.meta-title')"
                                :placeholder="trans('admin::app.catalog.categories.create.meta-title')"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Slug --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.catalog.categories.create.slug')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="slug"
                                :value="old('slug')"
                                id="slug"
                                rules="required"
                                :label="trans('admin::app.catalog.categories.create.slug')"
                                :placeholder="trans('admin::app.catalog.categories.create.slug')"
                                v-slugify
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="slug"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Meta Keywords --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="meta_keywords"
                                :value="old('meta_keywords')"
                                :label="trans('admin::app.catalog.categories.create.meta-keywords')"
                                :placeholder="trans('admin::app.catalog.categories.create.meta-keywords')"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Meta Description --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="meta_description"
                                id="meta_description"
                                :value="old('meta_description')"
                                :label="trans('admin::app.catalog.categories.create.meta-description')"
                                :placeholder="trans('admin::app.catalog.categories.create.meta-description')"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>

            {{-- Right Section --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
                {{-- Settings --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.catalog.categories.create.settings')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        {{-- Position --}}
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required !text-gray-800">
                                    @lang('admin::app.catalog.categories.create.position')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="position"
                                    :value="old('position')"
                                    rules="required"
                                    :label="trans('admin::app.catalog.categories.create.position')"
                                    :placeholder="trans('admin::app.catalog.categories.create.enter-position')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="position"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>

                        {{-- Display Mode  --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required !text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.display-mode')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="display_mode"
                                class="cursor-pointer"
                                rules="required"
                                :label="trans('admin::app.catalog.categories.create.display-mode')"
                            >
                                @foreach (['products-and-description', 'products-only', 'description-only'] as $item)
                                    <option value="{{ $item }}">
                                        @lang('admin::app.catalog.categories.create.' . $item)
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="display_mode"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Visible in menu --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.visible-in-menu')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="status"
                                class="cursor-pointer"
                                value="1"
                                :label="trans('admin::app.catalog.categories.create.visible-in-menu')"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                {{-- Filterable Attributes --}}
                <x-admin::accordion>
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
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    name="attributes[{{ $attribute->name ?? $attribute->admin_name }}]"
                                    id="{{ $attribute->name ?? $attribute->admin_name }}"
                                    class="cursor-pointer"
                                    value="{{ $attribute->id }}"
                                    for="{{ $attribute->name ?? $attribute->admin_name }}"
                                >
                                </x-admin::form.control-group.control>


                                <div class="text-[14px] text-gray-600 font-semibold cursor-pointer">
                                    {{ $attribute->name ?? $attribute->admin_name }}
                                </div>
                            </label>
                        @endforeach
                    </x-slot:content>
                </x-admin::accordion>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
