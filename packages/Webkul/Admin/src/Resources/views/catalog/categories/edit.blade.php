<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.catalog.categories.edit.title')
    </x-slot:title>

    @php
        $currentLocale = core()->getRequestedLocale();
    @endphp

    {!! view_render_event('bagisto.admin.catalog.categories.edit.before') !!}

    {{-- Category Edit Form --}}
    <x-admin::form
        :action="route('admin.catalog.categories.update', $category->id)"
        enctype="multipart/form-data"
        method="PUT"
    >

        {!! view_render_event('bagisto.admin.catalog.categories.edit.edit_form_controls.before', ['category' => $category]) !!}

        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.catalog.categories.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.catalog.categories.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white "
                >
                    @lang('admin::app.catalog.categories.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.catalog.categories.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Filter Row -->
        <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                {{-- Locale Switcher --}}
                <x-admin::dropdown>
                    {{-- Dropdown Toggler --}}
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-[4px] py-[6px] hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                        >
                            <span class="icon-language text-[24px] "></span>

                            {{ $currentLocale->name }}
                            
                            <input type="hidden" name="locale" value="{{ $currentLocale->code }}"/>

                            <span class="icon-sort-down text-[24px]"></span>
                        </button>
                    </x-slot:toggle>

                    {{-- Dropdown Content --}}
                    <x-slot:content class="!p-[0px]">
                        @foreach (core()->getAllLocales() as $locale)
                            <a
                                href="?{{ Arr::query(['locale' => $locale->code]) }}"
                                class="flex gap-[10px] px-5 py-2 text-[16px] cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot:content>
                </x-admin::dropdown>
            </div>
        </div>

        {{-- Full Pannel --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left Section --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.general.before', ['category' => $category]) !!}

                <!-- General -->
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.catalog.categories.edit.general')
                    </p>

                    {{-- Name --}}
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.catalog.categories.edit.name')
                        </x-admin::form.control-group.label>

                        <v-field
                            type="text"
                            name="{{$currentLocale->code}}[name]"
                            value="{{ old($currentLocale->code)['name'] ?? ($category->translate($currentLocale->code)['name'] ?? '') }}"
                            label="{{ trans('admin::app.catalog.categories.edit.name') }}"
                            rules="required"
                            v-slot="{ field }"
                        >
                            <input
                                type="text"
                                name="{{$currentLocale->code}}[name]"
                                id="{{$currentLocale->code}}[name]"
                                v-bind="field"
                                :class="[errors['{{ $currentLocale->code }}[name]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                placeholder="{{ trans('admin::app.catalog.categories.edit.name') }}"
                                v-slugify-target:{{$currentLocale->code . '[slug]'}}="setValues"
                            >
                        </v-field>

                        <x-admin::form.control-group.error
                            control-name="{{ $currentLocale->code}}[name]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    @if ($categories->count())
                        <div class="mb-[10px]">
                            {{-- Parent category --}}
                            <label class="block mb-[10px] text-[12px] text-gray-800 dark:text-white font-medium leading-[24px]">
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

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.general.after', ['category' => $category]) !!}

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.description_images.before', ['category' => $category]) !!}

                <!-- Description and images -->
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.catalog.categories.edit.description-and-images')
                    </p>

                    <!-- Description -->
                    <v-description v-slot="{ isDescriptionRequired }">
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label ::class="{ 'required' : isDescriptionRequired}">
                                @lang('admin::app.catalog.categories.edit.description')
                            </x-admin::form.control-group.label>
        
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                id="description"
                                class="description"
                                :value="old($currentLocale->code)['description'] ?? ($category->translate($currentLocale->code)['description'] ?? '')"
                                ::rules="{ 'required' : isDescriptionRequired}"
                                :label="trans('admin::app.catalog.categories.edit.description')"
                                :tinymce="true"
                            >
                            </x-admin::form.control-group.control>
        
                            <x-admin::form.control-group.error
                                control-name="description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </v-description>

                    <div class="flex gap-[50px]">
                        {{-- Add Logo --}}
                        <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.catalog.categories.edit.logo')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.edit.logo-size')
                            </p>

                            <x-admin::media.images
                                name="logo_path"
                                :uploaded-images="$category->logo_path ? [['id' => 'logo_path', 'url' => $category->logo_url]] : []"
                            >
                            </x-admin::media.images>
                        </div>

                        {{-- Add Banner --}}
                        <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.catalog.categories.edit.banner')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.edit.banner-size')
                            </p>

                            <x-admin::media.images
                                name="banner_path"
                                :uploaded-images="$category->banner_path ? [['id' => 'banner_path', 'url' => $category->banner_url]] : []"
                                width="220px"
                            >
                            </x-admin::media.images>
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.description_images.after', ['category' => $category]) !!}

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.seo.before', ['category' => $category]) !!}

                {{-- SEO Deatils --}}
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.catalog.categories.edit.seo-details')
                    </p>
                    
                    {{-- SEO Title & Description Blade Componnet --}}
                    <x-admin::seo/>

                    <div class="mt-[30px]">
                        {{-- Meta Title --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.edit.meta-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="{{$currentLocale->code}}[meta_title]"
                                id="meta_title"
                                :value="old($currentLocale->code)['meta_title'] ?? ($category->translate($currentLocale->code)['meta_title'] ?? '')"
                                :label="trans('admin::app.catalog.categories.edit.meta-title')"
                                :placeholder="trans('admin::app.catalog.categories.edit.meta-title')"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Slug --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.catalog.categories.edit.slug')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="{{$currentLocale->code}}[slug]"
                                value="{{ old($currentLocale->code)['slug'] ?? ($category->translate($currentLocale->code)['slug'] ?? '') }}"
                                label="{{ trans('admin::app.catalog.categories.edit.slug') }}"
                                rules="required"
                                v-slot="{ field }"
                            >
                                <input
                                    type="text"
                                    name="{{$currentLocale->code}}[slug]"
                                    id="{{$currentLocale->code}}[slug]"
                                    v-bind="field"
                                    :class="[errors['{{$currentLocale->code}}[slug]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    placeholder="{{ trans('admin::app.catalog.categories.edit.slug') }}"
                                    v-slugify-target:slug
                                >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="{{$currentLocale->code}}[slug]"
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
                                name="{{$currentLocale->code}}[meta_keywords]"
                                :value="old($currentLocale->code)['meta_keywords'] ?? ($category->translate($currentLocale->code)['meta_keywords'] ?? '')"
                                :label="trans('admin::app.catalog.categories.edit.meta-keywords')"
                                :placeholder="trans('admin::app.catalog.categories.edit.meta-keywords')"
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
                                name="{{$currentLocale->code}}[meta_description]"
                                id="meta_description"
                                :value="old($currentLocale->code)['meta_description'] ?? ($category->translate($currentLocale->code)['meta_description'] ?? '')"
                                :label="trans('admin::app.catalog.categories.edit.meta-description')"
                                :placeholder="trans('admin::app.catalog.categories.edit.meta-description')"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.seo.after', ['category' => $category]) !!}
            </div>

            {{-- Right Section --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
                {{-- Settings --}}

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.accordion.settings.before', ['category' => $category]) !!}

                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                            @lang('admin::app.catalog.categories.edit.settings')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        {{-- Position --}}
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.catalog.categories.edit.position')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="position"
                                    :value="old('position') ?: $category->position"
                                    rules="required"
                                    :label="trans('admin::app.catalog.categories.edit.position')"
                                    :placeholder="trans('admin::app.catalog.categories.edit.enter-position')"
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
                            <x-admin::form.control-group.label class="text-gray-800 dark:text-white font-medium required">
                                @lang('admin::app.catalog.categories.edit.display-mode')
                            </x-admin::form.control-group.label>

                            @php $selectedValue = old('display_mode') ?? $category->display_mode @endphp
                            
                            <x-admin::form.control-group.control
                                type="select"
                                name="display_mode"
                                id="display_mode"
                                class="cursor-pointer"
                                rules="required"
                                :value="$selectedValue"
                                :label="trans('admin::app.catalog.categories.edit.display-mode')"
                            >
                                <option value="products_and_description" {{ $selectedValue == 'products_and_description' ? 'selected' : '' }}>
                                    @lang('admin::app.catalog.categories.edit.products-and-description') 
                                </option>

                                <option value="products_only" {{ $selectedValue == 'products_only' ? 'selected' : '' }}>
                                    @lang('admin::app.catalog.categories.edit.products-only')
                                </option>

                                <option value="description_only" {{ $selectedValue == 'description_only' ? 'selected' : '' }}>
                                    @lang('admin::app.catalog.categories.edit.description-only')
                                </option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="display_mode"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Visible in menu --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.catalog.categories.edit.visible-in-menu')
                            </x-admin::form.control-group.label>

                            @php $selectedValue = old('status') ?: $category->status @endphp

                            <x-admin::form.control-group.control
                                type="switch"
                                name="status"
                                class="cursor-pointer"
                                value="1"
                                :label="trans('admin::app.catalog.categories.edit.visible-in-menu')"
                                :checked="(boolean) $selectedValue"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.accordion.settings.after', ['category' => $category]) !!}

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.accordion.filterable_attributes.before', ['category' => $category]) !!}

                {{-- Filterable Attributes --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="required text-gray-600 dark:text-gray-300 text-[16px] p-[10px] font-semibold">
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
                                    rules="required"
                                    class="hidden peer"
                                    :checked="in_array($attribute->id, $selectedaAtributes)"
                                    :label="trans('admin::app.catalog.categories.edit.filterable-attributes')"
                                >
                                </x-admin::form.control-group.control>

                                <div class="text-[14px] text-gray-600 dark:text-gray-300 font-semibold cursor-pointer">
                                    {{ $attribute->name ?? $attribute->admin_name }}
                                </div>
                            </label>
                        @endforeach

                        <x-admin::form.control-group.error
                            control-name="attributes[]"
                        >
                        </x-admin::form.control-group.error>
                    </x-slot:content>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.accordion.filterable_attributes.after', ['category' => $category]) !!}

            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.edit.edit_form_controls.after', ['category' => $category]) !!}

    </x-admin::form>

    {!! view_render_event('bagisto.admin.catalog.categories.edit.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-description-template">
            <div>
               <slot :is-description-required="isDescriptionRequired"></slot>
            </div>
        </script>

        <script type="module">
            app.component('v-description', {
                template: '#v-description-template',

                data() {
                    return {
                        isDescriptionRequired: true,

                        displayMode: "{{ $category->display_mode }}",
                    };
                },

                mounted() {
                    this.isDescriptionRequired = this.displayMode !== 'products_only';

                    this.$nextTick(() => {
                        document.querySelector('#display_mode').addEventListener('change', (e) => {
                            this.isDescriptionRequired = e.target.value !== 'products_only';
                        });
                    });
                },
            });
        </script>
    @endPushOnce

</x-admin::layouts>
