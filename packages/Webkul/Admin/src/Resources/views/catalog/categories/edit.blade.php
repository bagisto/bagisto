<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.categories.edit.title')
    </x-slot>

    @php
        $currentLocale = core()->getRequestedLocale();
    @endphp

    {!! view_render_event('bagisto.admin.catalog.categories.edit.before', ['category' => $category]) !!}

    <!-- Category Edit Form -->
    <x-admin::form
        :action="route('admin.catalog.categories.update', $category->id)"
        enctype="multipart/form-data"
        method="PUT"
    >

        {!! view_render_event('bagisto.admin.catalog.categories.edit.edit_form_controls.before', ['category' => $category]) !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.categories.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.catalog.categories.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
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
        <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
            <div class="flex items-center gap-x-1">
                <!-- Locale Switcher -->

                <x-admin::dropdown 
                    position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'left' : 'right' }}" 
                    :class="core()->getAllLocales()->count() <= 1 ? 'hidden' : ''"
                >
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                        >
                            <span class="icon-language text-2xl"></span>

                            {{ $currentLocale->name }}

                            <input
                                type="hidden"
                                name="locale"
                                value="{{ $currentLocale->code }}"
                            />

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-0">
                        @foreach (core()->getAllLocales() as $locale)
                            <a
                                href="?{{ Arr::query(['locale' => $locale->code]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-admin::dropdown>
            </div>
        </div>

        <!-- Full Pannel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.general.before', ['category' => $category]) !!}

                <!-- General -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.catalog.categories.edit.general')
                    </p>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.catalog.categories.edit.name')
                        </x-admin::form.control-group.label>

                        <v-field
                            type="text"
                            name="{{ $currentLocale->code }}[name]"
                            value="{{ old($currentLocale->code)['name'] ?? ($category->translate($currentLocale->code)['name'] ?? '') }}"
                            label="{{ trans('admin::app.catalog.categories.edit.name') }}"
                            rules="required"
                            v-slot="{ field }"
                        >
                            <input
                                type="text"
                                name="{{ $currentLocale->code }}[name]"
                                id="{{ $currentLocale->code }}[name]"
                                v-bind="field"
                                :class="[errors['{{ $currentLocale->code }}[name]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                placeholder="{{ trans('admin::app.catalog.categories.edit.name') }}"
                                v-slugify-target:{{$currentLocale->code.'[slug]'}}="setValues"
                            />
                        </v-field>

                        <x-admin::form.control-group.error control-name="{{ $currentLocale->code}}[name]" />
                    </x-admin::form.control-group>

                    @if ($categories->count())
                        <div>
                            <!-- Parent category -->
                            <label class="mb-2.5 block text-xs font-medium leading-6 text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.categories.edit.select-parent-category')
                            </label>

                            <!-- Radio select button -->
                            <div class="flex flex-col gap-3">
                                <x-admin::tree.view
                                    input-type="radio"
                                    name-field="parent_id"
                                    value-field="id"
                                    id-field="id"
                                    :items="json_encode($categories)"
                                    :value="json_encode($category->parent_id)"
                                    :fallback-locale="config('app.fallback_locale')"
                                />
                            </div>
                        </div>
                    @endif
                </div>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.general.after', ['category' => $category]) !!}

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.description_images.before', ['category' => $category]) !!}

                <!-- Description and images -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.catalog.categories.edit.description-and-images')
                    </p>

                    <!-- Description -->
                    <v-description v-slot="{ isDescriptionRequired }">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label ::class="{ 'required' : isDescriptionRequired}">
                                @lang('admin::app.catalog.categories.edit.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                id="description"
                                class="description"
                                name="{{ $currentLocale->code }}[description]"
                                ::rules="{ 'required' : isDescriptionRequired}"
                                :value="old($currentLocale->code)['description'] ?? ($category->translate($currentLocale->code)['description'] ?? '')"
                                :label="trans('admin::app.catalog.categories.edit.description')"
                                :tinymce="true"
                                :prompt="core()->getConfigData('general.magic_ai.content_generation.category_description_prompt')"
                            />

                            <x-admin::form.control-group.error control-name="{{ $currentLocale->code }}[description]" />
                        </x-admin::form.control-group>
                    </v-description>

                    <div class="flex pt-5">
                        <!-- Add Logo -->
                        <div class="flex w-2/5 flex-col gap-2">
                            <p class="font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.categories.edit.logo')
                            </p>

                            <p class="text-xs text-gray-500">
                                @lang('admin::app.catalog.categories.edit.logo-size')
                            </p>

                            <x-admin::media.images
                                name="logo_path"
                                :uploaded-images="$category->logo_path ? [['id' => 'logo_path', 'url' => $category->logo_url]] : []"
                            />
                        </div>

                        <!-- Add Banner -->
                        <div class="flex w-3/5 flex-col gap-2">
                            <p class="font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.categories.edit.banner')
                            </p>

                            <p class="text-xs text-gray-500">
                                @lang('admin::app.catalog.categories.edit.banner-size')
                            </p>

                            <x-admin::media.images
                                name="banner_path"
                                :uploaded-images="$category->banner_path ? [['id' => 'banner_path', 'url' => $category->banner_url]] : []"
                                width="220px"
                            />
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.description_images.after', ['category' => $category]) !!}

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.seo.before', ['category' => $category]) !!}

                <!-- SEO Details -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.catalog.categories.edit.seo-details')
                    </p>

                    <!-- SEO Title & Description Blade Component -->
                    <x-admin::seo/>

                    <div class="mt-8">
                        <!-- Meta Title -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.edit.meta-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="meta_title"
                                name="{{ $currentLocale->code }}[meta_title]"
                                :value="old($currentLocale->code)['meta_title'] ?? ($category->translate($currentLocale->code)['meta_title'] ?? '')"
                                :label="trans('admin::app.catalog.categories.edit.meta-title')"
                                :placeholder="trans('admin::app.catalog.categories.edit.meta-title')"
                            />

                        </x-admin::form.control-group>

                        <!-- Slug -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.catalog.categories.edit.slug')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="{{$currentLocale->code}}[slug]"
                                rules="required"
                                value="{{ old($currentLocale->code)['slug'] ?? ($category->translate($currentLocale->code)['slug'] ?? '') }}"
                                label="{{ trans('admin::app.catalog.categories.edit.slug') }}"
                                v-slot="{ field }"
                            >
                                <input
                                    type="text"
                                    id="{{$currentLocale->code}}[slug]"
                                    name="{{$currentLocale->code}}[slug]"
                                    :class="[errors['{{$currentLocale->code}}[slug]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    v-bind="field"
                                    placeholder="{{ trans('admin::app.catalog.categories.edit.slug') }}"
                                    v-slugify-target:{{$currentLocale->code.'[slug]'}}
                                />
                            </v-field>

                            <x-admin::form.control-group.error control-name="{{$currentLocale->code}}[slug]" />

                            <x-admin::form.control-group.error control-name="{{$currentLocale->code}}.slug" />
                        </x-admin::form.control-group>

                        <!-- Meta Keywords -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.edit.meta-keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="{{ $currentLocale->code }}[meta_keywords]"
                                :value="old($currentLocale->code)['meta_keywords'] ?? ($category->translate($currentLocale->code)['meta_keywords'] ?? '')"
                                :label="trans('admin::app.catalog.categories.edit.meta-keywords')"
                                :placeholder="trans('admin::app.catalog.categories.edit.meta-keywords')"
                            />
                        </x-admin::form.control-group>

                        <!-- Meta Description -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.edit.meta-description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                id="meta_description"
                                name="{{ $currentLocale->code }}[meta_description]"
                                :value="old($currentLocale->code)['meta_description'] ?? ($category->translate($currentLocale->code)['meta_description'] ?? '')"
                                :label="trans('admin::app.catalog.categories.edit.meta-description')"
                                :placeholder="trans('admin::app.catalog.categories.edit.meta-description')"
                            />
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.seo.after', ['category' => $category]) !!}
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">
                <!-- Settings -->

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.accordion.settings.before', ['category' => $category]) !!}

                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.categories.edit.settings')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <!-- Position -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.catalog.categories.edit.position')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="position"
                                rules="required|integer"
                                :value="old('position') ?: $category->position"
                                :label="trans('admin::app.catalog.categories.edit.position')"
                                :placeholder="trans('admin::app.catalog.categories.edit.enter-position')"
                            />

                            <x-admin::form.control-group.error control-name="position" />
                        </x-admin::form.control-group>

                        <!-- Display Mode  -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.categories.edit.display-mode')
                            </x-admin::form.control-group.label>

                            @php $selectedValue = old('display_mode') ?? $category->display_mode @endphp

                            <x-admin::form.control-group.control
                                type="select"
                                id="display_mode"
                                class="cursor-pointer"
                                name="display_mode"
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

                            <x-admin::form.control-group.error control-name="display_mode" />
                        </x-admin::form.control-group>

                        <!-- Visible in menu -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.edit.visible-in-menu')
                            </x-admin::form.control-group.label>

                            @php $selectedValue = old('status') ?: $category->status @endphp

                            <!-- Visible in menu Hidden field -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                class="cursor-pointer"
                                name="status"
                                :checked="(boolean) $selectedValue"
                            />

                            <x-admin::form.control-group.control
                                type="switch"
                                class="cursor-pointer"
                                name="status"
                                value="1"
                                :label="trans('admin::app.catalog.categories.edit.visible-in-menu')"
                                :checked="(boolean) $selectedValue"
                            />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.accordion.settings.after', ['category' => $category]) !!}

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.accordion.filterable_attributes.before', ['category' => $category]) !!}

                <!-- Filterable Attributes -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="required p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.catalog.categories.edit.filterable-attributes')
                        </p>
                    </x-slot>

                    @php $selectedAttributes = old('attributes') ?: $category->filterableAttributes->pluck('id')->toArray() @endphp

                    <x-slot:content>
                        @foreach ($attributes as $attribute)
                            <x-admin::form.control-group class="!mb-2 flex items-center gap-2.5 last:!mb-0">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    :id="$attribute->name ?? $attribute->admin_name"
                                    name="attributes[]"
                                    rules="required"
                                    :value="$attribute->id"
                                    :for="$attribute->name ?? $attribute->admin_name"
                                    :label="trans('admin::app.catalog.categories.edit.filterable-attributes')"
                                    :checked="in_array($attribute->id, $selectedAttributes)"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="{{ $attribute->name ?? $attribute->admin_name }}"
                                >
                                    {{ $attribute->name ?? $attribute->admin_name }}
                                </label>
                            </x-admin::form.control-group>
                        @endforeach

                        <x-admin::form.control-group.error control-name="attributes[]" />
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.catalog.categories.edit.card.accordion.filterable_attributes.after', ['category' => $category]) !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.edit.edit_form_controls.after', ['category' => $category]) !!}

    </x-admin::form>

    {!! view_render_event('bagisto.admin.catalog.categories.edit.after', ['category' => $category]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-description-template"
        >
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
