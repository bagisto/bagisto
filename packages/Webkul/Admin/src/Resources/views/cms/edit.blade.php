@php
    $currentLocale = core()->getRequestedLocale();

    $selectedOptionIds = old('inventory_sources') ?? $page->channels->pluck('id')->toArray();
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.cms.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.cms.update', $page->id)"
        method="PUT"
        enctype="multipart/form-data"
    >

        {!! view_render_event('bagisto.admin.cms.pages.edit.create_form_controls.before', ['page' => $page]) !!}

        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('admin::app.cms.edit.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.cms.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white "
                >
                    @lang('admin::app.cms.edit.back-btn')
                </a>

                <!-- Preview Button -->
                @if ($page->translate($currentLocale->code))
                    <a
                        href="{{ route('shop.cms.page', $page->translate($currentLocale->code)['url_key']) }}"
                        class="secondary-button"
                        target="_blank"
                    >
                        @lang('admin::app.cms.edit.preview-btn')
                    </a>
                @endif

                <!--Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.cms.edit.save-btn')
                </button>
            </div>
        </div>

        <div class="flex  gap-4 justify-between items-center mt-7 max-md:flex-wrap">
            <div class="flex gap-x-1 items-center">
                <!-- Locale Switcher -->
                <x-admin::dropdown :class="core()->getAllLocales()->count() <= 1 ? 'hidden' : ''">
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                        >
                            <span class="icon-language text-2xl"></span>

                            {{ $currentLocale->name }}
                            
                            <input type="hidden" name="locale" value="{{ $currentLocale->code }}"/>

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-0">
                        @foreach (core()->getAllLocales() as $locale)
                            <a
                                href="?{{ Arr::query(['locale' => $locale->code]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base  cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-admin::dropdown>
            </div>
        </div>

          <!-- body content -->
          <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <!-- Left sub-component -->
            <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.content.before', ['page' => $page]) !!}

                <!--Content -->
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.cms.edit.description')
                    </p>

                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.cms.edit.content')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            id="content"
                            name="{{ $currentLocale->code }}[html_content]"
                            rules="required"
                            :value="old($currentLocale->code)['html_content'] ?? ($page->translate($currentLocale->code)['html_content'] ?? '')"
                            :label="trans('admin::app.cms.edit.content')"
                            :placeholder="trans('admin::app.cms.edit.content')"
                            :tinymce="true"
                            :prompt="core()->getConfigData('general.magic_ai.content_generation.cms_page_content_prompt')"
                        />

                        <x-admin::form.control-group.error control-name="{{ $currentLocale->code }}[html_content]" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.content.after', ['page' => $page]) !!}

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.seo.before', ['page' => $page]) !!}

                <!-- SEO Input Fields -->
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <p class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                        @lang('admin::app.cms.edit.seo')
                    </p>

                    <!-- SEO Title & Description Blade Componnet -->
                    <x-admin::seo slug="page"/>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta-title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="meta_title"
                            name="{{$currentLocale->code}}[meta_title]"
                            :value="old($currentLocale->code)['meta_title'] ?? ($page->translate($currentLocale->code)['meta_title'] ?? '') "
                            :label="trans('admin::app.cms.edit.meta-title')"
                            :placeholder="trans('admin::app.cms.edit.meta-title')"
                        />

                        <x-admin::form.control-group.error control-name="{{$currentLocale->code}}[meta_title]" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.cms.edit.url-key')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="url_key"
                            name="{{$currentLocale->code}}[url_key]"
                            rules="required"
                            :value="old($currentLocale->code)['url_key'] ?? ($page->translate($currentLocale->code)['url_key'] ?? '')"
                            :label="trans('admin::app.cms.edit.url-key')"
                            :placeholder="trans('admin::app.cms.edit.url-key')"
                        />

                        <x-admin::form.control-group.error control-name="{{$currentLocale->code}}[url_key]" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta-keywords')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            class="text-gray-600 dark:text-gray-300"
                            id="meta_keywords"
                            name="{{$currentLocale->code}}[meta_keywords]"
                            :value="old($currentLocale->code)['meta_keywords'] ?? ($page->translate($currentLocale->code)['meta_keywords'] ?? '')"
                            :label="trans('admin::app.cms.edit.meta-keywords')"
                            :placeholder="trans('admin::app.cms.edit.meta-keywords')"
                        />

                        <x-admin::form.control-group.error control-name="{{$currentLocale->code}}[meta_keywords]" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta-description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            class="text-gray-600 dark:text-gray-300"
                            id="meta_description"
                            name="{{$currentLocale->code}}[meta_description]"
                            :value="old($currentLocale->code)['meta_description'] ?? ($page->translate($currentLocale->code)['meta_description'] ?? '')"
                            :label="trans('admin::app.cms.edit.meta-description')"
                            :placeholder="trans('admin::app.cms.edit.meta-description')"
                        />

                        <x-admin::form.control-group.error control-name="{{$currentLocale->code}}[meta_description]" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.seo.after', ['page' => $page]) !!}

            </div>

            <!-- Right sub-component -->
            <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
                <!-- General -->

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.accordion.seo.before', ['page' => $page]) !!}

                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                @lang('admin::app.cms.create.general')
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content>
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.cms.edit.page-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="{{ $currentLocale->code }}[page_title]"
                                name="{{ $currentLocale->code }}[page_title]"
                                rules="required"
                                value="{{ old($currentLocale->code)['page_title'] ?? ($page->translate($currentLocale->code)['page_title'] ?? '') }}"
                                :label="trans('admin::app.cms.edit.page-title')"
                                :placeholder="trans('admin::app.cms.edit.page-title')"
                            />

                            <x-admin::form.control-group.error control-name="{{ $currentLocale->code }}[page_title]" />
                        </x-admin::form.control-group>

                        <!-- Select Channels -->
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.cms.create.channels')
                        </x-admin::form.control-group.label>

                        @foreach(core()->getAllChannels() as $channel)
                            <x-admin::form.control-group class="flex gap-2.5 !mb-2 last:!mb-0 select-none">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    :id="'channels_' . $channel->id"
                                    name="channels[]"
                                    rules="required"
                                    :value="$channel->id"
                                    :for="'channels_' . $channel->id"
                                    :label="trans('admin::app.cms.create.channels')"
                                    :checked="in_array($channel->id, $selectedOptionIds)"
                                />

                                <label
                                    class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                    for="channels_{{ $channel->id }}" 
                                >
                                    {{ core()->getChannelName($channel) }}
                                </label>
                            </x-admin::form.control-group>
                        @endforeach
                        
                        <x-admin::form.control-group.error control-name="channels[]" />
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.accordion.seo.after', ['page' => $page]) !!}

            </div>
          </div>

        {!! view_render_event('bagisto.admin.cms.pages.edit.create_form_controls.after', ['page' => $page]) !!}

    </x-admin::form>
</x-admin::layouts>
