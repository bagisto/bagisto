<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.catalog.categories.edit.title')
    </x-slot:title>

    @php
        $locale = core()->getRequestedLocaleCode();
    @endphp

    {{-- Input Form --}}
    <x-admin::form
        :action="route('admin.catalog.categories.update', $category->id)"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.catalog.categories.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Cancel Button -->
                <a href="{{ route('admin.catalog.categories.index') }}">
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.catalog.categories.edit.cancel-btn')
                    </span>
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.catalog.categories.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Filter Row -->
        <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                <div>
                    {{-- Locale switcher --}}
                    <v-locale-switcher>
                        <div class="shimmer w-[100px] h-[36px]"></div>
                    </v-locale-switcher>
                </div>
            </div>
        </div>

        {{-- Full Pannel --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left Section --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.before', ['category' => $category]) !!}

                <!-- General -->
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.categories.edit.general')
                    </p>

                    {{-- Name --}}
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.catalog.categories.edit.company-name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="{{$locale}}[name]"
                            value="{{ old($locale)['name'] ?? ($category->translate($locale)['name'] ?? '') }}"
                            class="w-full"
                            label="{{ trans('admin::app.catalog.categories.edit.company-name') }}"
                            placeholder="{{ trans('admin::app.catalog.categories.edit.company-name') }}"
                        >
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>

                    @if ($categories->count())
                        <div class="mb-[10px]">
                            {{-- Parent category --}}
                            <label class="block mb-[10px] text-[12px] text-gray-800 font-medium leading-[24px]">
                                @lang('admin::app.catalog.categories.edit.select-parent-category')
                            </label>

                            {{-- Radio select button --}}
                            <div class="flex flex-col gap-[12px]">
                                <x-admin::tree.view
                                    input-type="radio"
                                    name-field="parent_id"
                                    :value="json_encode($category->parent_id)"
                                    :value-field="json_encode($category->parent_id)"
                                    :model-value="json_encode($categories)"
                                    :items="json_encode($categories)"
                                    :fallback-locale="config('app.fallback_locale')"
                                >
                                </x-admin::tree.view>
                            </div>
                        </div>
                    @endif
                </div>

                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.after', ['category' => $category]) !!}

                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.before', ['category' => $category]) !!}

                <!-- Description and images -->
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.categories.edit.description-and-images')
                    </p>

                    <!-- Description -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.catalog.categories.edit.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            id="description"
                            class="description"
                            :value="old($locale)['description'] ?? ($category->translate($locale)['description'] ?? '')"
                            rules="required"
                            label="{{ trans('admin::app.catalog.categories.edit.description') }}"
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
                                @lang('admin::app.catalog.categories.edit.logo')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.edit.logo-size')
                            </p>

                            <x-admin::form.control-group>

                                @php $logo = $category->logo_path ? Storage::url($category->logo_path) : ''; @endphp

                                <x-admin::form.control-group.control
                                    type="image"
                                    name="logo_path[image_1]"
                                    class="mb-0 !p-0 rounded-[12px] text-gray-700"
                                    :label="trans('admin::app.catalog.categories.edit.add-logo')"
                                    :is-multiple="false"
                                    :src="$logo"
                                    accepted-types="image/*"
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
                                @lang('admin::app.catalog.categories.edit.banner')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.edit.banner-size')
                            </p>

                            <x-admin::form.control-group>

                                @php $banner = $category->banner_path ? Storage::url($category->banner_path) : ''; @endphp

                                <x-admin::form.control-group.control
                                    type="image"
                                    name="banner_path[]"
                                    class="mb-0 !p-0 rounded-[12px] text-gray-700"
                                    :width="200"
                                    :height="110"
                                    :label="trans('admin::app.catalog.categories.edit.add-banner')"
                                    :is-multiple="false"
                                    accepted-types="image/*"
                                    :src="$banner"
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

                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.after', ['category' => $category]) !!}

                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.before', ['category' => $category]) !!}

                {{-- SEO Deatils --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.catalog.categories.edit.seo-details')
                    </p>

                    <div class="flex flex-col gap-[3px]">
                        <p
                            class="text-[#161B9D]"
                            v-text="metaTitle ? metaTitle : '{{ $category->translate($locale)['meta_title'] ?? '' }}'"
                        >
                        </p>

                        {{-- Get Meta Title From v-model and convet in lower case also replace space with (-) --}}
                        <p
                            class="text-[#135F29]"
                            v-text="'{{ URL::to('/') }}/' + (metaTitle ? metaTitle.toLowerCase().replace(/\s+/g, '-') : '{{ $category ? str_replace(' ', '-', $category->translate($locale)['meta_title']) : '' }}')"
                        >
                        </p>

                        <p
                            class="text-gray-600"
                            v-text='metaDescription ?? "{{ $category?->translate($locale)['meta_description'] ?? '' }}"'
                        >
                        </p>
                    </div>

                    <div class="mt-[30px]">
                        {{-- Meta Title --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.edit.meta-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="{{$locale}}[meta_title]"
                                :value="old($locale)['meta_title'] ?? ($category->translate($locale)['meta_title'] ?? '')"
                                label="{{ trans('admin::app.catalog.categories.edit.meta-title') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.edit.meta-title') }}"
                                v-model="metaTitle"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Slug --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.slug')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="{{$locale}}[slug]"
                                :value="old($locale)['slug'] ?? ($category->translate($locale)['slug'] ?? '')"
                                rules="required"
                                label="{{ trans('admin::app.catalog.categories.create.slug') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.slug') }}"
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
                                @lang('admin::app.catalog.categories.edit.meta-keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="{{$locale}}[meta_keywords]"
                                :value="old($locale)['meta_keywords'] ?? ($category->translate($locale)['meta_keywords'] ?? '')"
                                label="{{ trans('admin::app.catalog.categories.edit.meta-keywords') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.edit.meta-keywords') }}"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Meta Description --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.edit.meta-description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="{{$locale}}[meta_description]"
                                :value="old($locale)['meta_description'] ?? ($category->translate($locale)['meta_description'] ?? '')"
                                label="{{ trans('admin::app.catalog.categories.edit.meta-description') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.edit.meta-description') }}"
                                v-model="metaDescription"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.after', ['category' => $category]) !!}
            </div>

            {{-- Right Section --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
                {{-- Settings --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.catalog.categories.edit.settings')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        {{-- Position --}}
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="!text-gray-800">
                                    @lang('admin::app.catalog.categories.edit.position')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="position"
                                    :value="old('position') ?: $category->position"
                                    rules="required"
                                    label="{{ trans('admin::app.catalog.categories.edit.position') }}"
                                    placeholder="{{ trans('admin::app.catalog.categories.edit.enter-position') }}"
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
                            <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.edit.display-mode')
                            </x-admin::form.control-group.label>

                            @php $selectedValue = old('status') ?: $category->display_mode @endphp

                            <x-admin::form.control-group.control
                                type="select"
                                name="display_mode"
                                class="cursor-pointer"
                                :value="$selectedValue"
                                rules="required"
                                label="{{ trans('admin::app.catalog.categories.edit.display-mode') }}"
                            >
                                @foreach (['products_and_description', 'products_only', 'description_only'] as $item)
                                    <option
                                        value="{{ $item }}"
                                        {{ $selectedValue == $item ? 'selected' : '' }}
                                    >
                                        @lang('admin::app.catalog.categories.edit.' . $item)
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
                                @lang('admin::app.catalog.categories.edit.visible-in-menu')
                            </x-admin::form.control-group.label>

                            @php $selectedValue = old('status') ?: $category->status @endphp

                            <x-admin::form.control-group.control
                                type="switch"
                                name="status"
                                class="cursor-pointer"
                                value="1"
                                label="{{ trans('admin::app.catalog.categories.edit.visible-in-menu') }}"
                                :checked="(boolean) $selectedValue"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                {{-- Filterable Attributes --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                            @lang('admin::app.catalog.categories.edit.filterable-attributes')
                        </p>
                    </x-slot:header>

                    @php $selectedaAtributes = old('attributes') ?: $category->filterableAttributes->pluck('id')->toArray() @endphp

                    <x-slot:content class="pointer-events-none">
                        @foreach ($attributes as $attribute)
                            <label
                                class="flex gap-[10px] w-max items-center p-[6px] cursor-pointer select-none"
                                for="{{ $attribute->name ?? $attribute->admin_name }}"
                            >
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="{{ $attribute->name ?? $attribute->admin_name }}"
                                    for="{{ $attribute->name ?? $attribute->admin_name }}"
                                    value="{{ $attribute->id }}"
                                    name="attributes[]"
                                    class="hidden peer"
                                    :checked="in_array($attribute->id, $selectedaAtributes)"
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

    @pushOnce('scripts')
        <script type="text/x-template" id="v-locale-switcher-template">
            <div>
                <!-- Locale dropdown -->
                <x-admin::dropdown>
                    <x-slot:toggle>
                        <!-- Current Locale-->
                        <div class="inline-flex gap-x-[4px] justify-between items-center w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-language text-[24px] "></span>
                                @{{ selectedLocale[0].name }}
                            <span class="icon-sort-down text-[24px]"></span>
                        </div>
                    </x-slot:toggle>

                    <!-- Locale content -->
                    <x-slot:content class="!p-[0px]">
                        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
                            <a
                                class="px-5 py-2 text-[16px] hover:bg-gray-100 cursor-pointer"
                                v-for="locale in locales"
                                :class="{'bg-gray-100': locale.code == '{{ $locale }}'}"
                                v-text="locale.name"
                                @click="change(locale)"
                            >
                            </a>
                        </div>
                    </x-slot:content>
                </x-admin::dropdown>
            </div>
        </script>

        <script type="module">
            app.component('v-locale-switcher', {
                template: '#v-locale-switcher-template',
                data() {
                    return {
                        locales: @json(core()->getAllLocales()),

                        selectedLocale: {},
                    }
                },

                created() {
                    this.init();
                },

                methods: {
                    init() {
                        this.selectedLocale = this.locales.filter((locale) => "{{ $locale }}" == locale.code);
                    },

                    change(locale) {
                        let url = new URL(window.location.href);

                        url.searchParams.set('locale', locale.code);

                        window.location.href = url.href;
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
