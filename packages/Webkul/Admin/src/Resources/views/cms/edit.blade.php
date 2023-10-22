@php
    $currentLocale = core()->getRequestedLocale();

    $selectedOptionIds = old('inventory_sources') ?? $page->channels->pluck('id')->toArray();
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.cms.edit.title')
    </x-slot:title>

    <x-admin::form
        :action="route('admin.cms.update', $page->id)"
        method="PUT"
        enctype="multipart/form-data"
    >

        {!! view_render_event('bagisto.admin.cms.pages.edit.create_form_controls.before') !!}

        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.cms.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.cms.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white "
                >
                    @lang('admin::app.cms.edit.back-btn')
                </a>

                {{-- Preview Button --}}
                @if ($page->translate($currentLocale->code))
                    <a
                        href="{{ route('shop.cms.page', $page->translate($currentLocale->code)['url_key']) }}"
                        class="secondary-button"
                        target="_blank"
                    >
                        @lang('admin::app.cms.edit.preview-btn')
                    </a>
                @endif

                {{--Save Button --}}
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.cms.edit.save-btn')
                </button>
            </div>
        </div>

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

          {{-- body content --}}
          <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left sub-component --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.content.before') !!}

                {{--Content --}}
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.cms.edit.description')
                    </p>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.cms.edit.content')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="{{ $currentLocale->code }}[html_content]"
                            :value="old($currentLocale->code)['html_content'] ?? ($page->translate($currentLocale->code)['html_content'] ?? '')"
                            id="content"
                            rules="required"
                            :label="trans('admin::app.cms.edit.content')"
                            :placeholder="trans('admin::app.cms.edit.content')"
                            :tinymce="true"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{ $currentLocale->code }}[html_content]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.content.after') !!}

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.seo.before') !!}

                {{-- SEO Input Fields --}}
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                        @lang('admin::app.cms.edit.seo')
                    </p>

                    {{-- SEO Title & Description Blade Componnet --}}
                    <x-admin::seo/>

                    <div class="mt-[30px]">
                        <x-admin::form.control-group class="mb-[30px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.cms.edit.meta-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="{{$currentLocale->code}}[meta_title]"
                                :value="old($currentLocale->code)['meta_title'] ?? ($page->translate($currentLocale->code)['meta_title'] ?? '') "
                                id="meta_title"
                                :label="trans('admin::app.cms.edit.meta-title')"
                                :placeholder="trans('admin::app.cms.edit.meta-title')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="{{$currentLocale->code}}[meta_title]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.cms.edit.url-key')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="{{$currentLocale->code}}[url_key]"
                                :value="old($currentLocale->code)['url_key'] ?? ($page->translate($currentLocale->code)['url_key'] ?? '')"
                                id="url_key"
                                rules="required"
                                :label="trans('admin::app.cms.edit.url-key')"
                                :placeholder="trans('admin::app.cms.edit.url-key')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="{{$currentLocale->code}}[url_key]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.cms.edit.meta-keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="{{$currentLocale->code}}[meta_keywords]"
                                :value="old($currentLocale->code)['meta_keywords'] ?? ($page->translate($currentLocale->code)['meta_keywords'] ?? '')"
                                id="meta_keywords"
                                class="text-gray-600 dark:text-gray-300"
                                :label="trans('admin::app.cms.edit.meta-keywords')"
                                :placeholder="trans('admin::app.cms.edit.meta-keywords')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="{{$currentLocale->code}}[meta_keywords]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.cms.edit.meta-description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="{{$currentLocale->code}}[meta_description]"
                                :value="old($currentLocale->code)['meta_description'] ?? ($page->translate($currentLocale->code)['meta_description'] ?? '')"
                                id="meta_description"
                                class="text-gray-600 dark:text-gray-300"
                                :label="trans('admin::app.cms.edit.meta-description')"
                                :placeholder="trans('admin::app.cms.edit.meta-description')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="{{$currentLocale->code}}[meta_description]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.seo.after') !!}

            </div>

            {{-- Right sub-component --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                {{-- General --}}

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.accordion.seo.before') !!}

                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                                @lang('admin::app.cms.create.general')
                            </p>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.cms.edit.page-title')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="{{ $currentLocale->code }}[page_title]"
                                    value="{{ old($currentLocale->code)['page_title'] ?? ($page->translate($currentLocale->code)['page_title'] ?? '') }}"
                                    id="{{ $currentLocale->code }}[page_title]"
                                    rules="required"
                                    :label="trans('admin::app.cms.edit.page-title')"
                                    :placeholder="trans('admin::app.cms.edit.page-title')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="{{ $currentLocale->code }}[page_title]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            {{-- Select Channels --}}
                            <p class="required block leading-[24px] text-gray-800 dark:text-white font-medium">
                                @lang('admin::app.cms.create.channels')
                            </p>

                            @foreach(core()->getAllChannels() as $channel)
                                <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="channels[]"
                                        :value="$channel->id"
                                        :id="'channels_' . $channel->id"
                                        :for="'channels_' . $channel->id"
                                        rules="required"
                                        :label="trans('admin::app.cms.create.channels')"
                                        :checked="in_array($channel->id, $selectedOptionIds)"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label
                                        :for="'channels_' . $channel->id"
                                        class="!text-[14px] !text-gray-600 dark:!text-gray-300 font-semibold cursor-pointer"
                                    >
                                        {{ core()->getChannelName($channel) }}
                                    </x-admin::form.control-group.label>
                                </x-admin::form.control-group>
                            @endforeach
                            
                            <x-admin::form.control-group.error
                                control-name="channels[]"
                            >
                            </x-admin::form.control-group.error>
                        </div>
                    </x-slot:content>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.cms.pages.edit.card.accordion.seo.after') !!}

            </div>
          </div>

        {!! view_render_event('bagisto.admin.cms.pages.edit.create_form_controls.after') !!}

    </x-admin::form>
</x-admin::layouts>
