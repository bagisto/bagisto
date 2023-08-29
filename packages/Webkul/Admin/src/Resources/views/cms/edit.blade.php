@inject('channels', 'Webkul\Core\Repositories\ChannelRepository')

@php
    $locale = core()->getRequestedLocaleCode();

    $selectedOptionIds = old('inventory_sources') ?? $page->channels->pluck('id')->toArray();
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.cms.edit.title')
    </x-slot:title>

    <x-admin::form
        :action="route('admin.cms.update', $page->id)"
        enctype="multipart/form-data"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.cms.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.cms.index') }}"
                    class="transparent-button"
                >
                    @lang('admin::app.cms.edit.back-btn')
                </a>

                {{-- Preview Button --}}
                @if ($page->translate($locale))
                    <a
                        href="{{ route('shop.cms.page', $page->translate($locale)['url_key']) }}"
                        class="primary-button"
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
                <div>
                    {{-- Locale switcher --}}
                    <v-locale-switcher></v-locale-switcher>
                </div>
            </div>
        </div>

          {{-- body content --}}
          <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left sub-component --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                {{--Content --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.cms.edit.content')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="{{ $locale }}[html_content]"
                            :value="old('html_content')"
                            id="content"
                            rules="required"
                            :label="trans('admin::app.cms.edit.content')"
                            :placeholder="trans('admin::app.cms.edit.content')"
                            :tinymce="true"
                        >
                            {{ old($locale)['html_content'] ?? ($page->translate($locale)['html_content'] ?? '') }}
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{ $locale }}[html_content]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>

                {{-- SEO Input Fields --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <x-admin::form.control-group class="mb-[30px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta-title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="{{$locale}}[meta_title]"
                            :value="old($locale)['meta_title'] ?? ($page->translate($locale)['meta_title'] ?? '') "
                            id="meta_title"
                            :label="trans('admin::app.cms.edit.meta-title')"
                            :placeholder="trans('admin::app.cms.edit.meta-title')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{$locale}}[meta_title]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.cms.edit.url-key')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="{{$locale}}[url_key]"
                            :value="old($locale)['url_key'] ?? ($page->translate($locale)['url_key'] ?? '')"
                            id="url_key"
                            rules="required"
                            :label="trans('admin::app.cms.edit.url-key')"
                            :placeholder="trans('admin::app.cms.edit.url-key')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{$locale}}[url_key]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta-keywords')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="{{$locale}}[meta_keywords]"
                            :value="old($locale)['meta_keywords'] ?? ($page->translate($locale)['meta_keywords'] ?? '')"
                            id="meta_keywords"
                            class="text-gray-600"
                            :label="trans('admin::app.cms.edit.meta-keywords')"
                            :placeholder="trans('admin::app.cms.edit.meta-keywords')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{$locale}}[meta_keywords]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta-description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="{{$locale}}[meta_description]"
                            :value="old($locale)['meta_description'] ?? ($page->translate($locale)['meta_description'] ?? '')"
                            id="meta_description"
                            class="text-gray-600"
                            :label="trans('admin::app.cms.edit.meta-description')"
                            :placeholder="trans('admin::app.cms.edit.meta-description')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{$locale}}[meta_description]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>
            </div>
            {{-- Right sub-component --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                {{-- General --}}
                <div class="bg-white rounded-[4px] box-shadow">
                    <x-admin::accordion>
                        <x-slot:header>
                            <div class="flex items-center justify-between p-[6px]">
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.cms.create.general')
                                </p>
                            </div>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="mb-[10px]">
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.cms.edit.page-title')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="{{ $locale }}[page_title]"
                                        value="{{ old($locale)['page_title'] ?? ($page->translate($locale)['page_title'] ?? '') }}"
                                        id="{{ $locale }}[page_title]"
                                        rules="required"
                                        :label="trans('admin::app.cms.edit.page-title')"
                                        :placeholder="trans('admin::app.cms.edit.page-title')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="{{ $locale }}[page_title]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                {{-- Select Channels --}}
                                <p class="required block leading-[24px] text-gray-800 font-medium">
                                    @lang('admin::app.cms.create.channels')
                                </p>

                                @foreach($channels->all() as $channel)
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
                                            class="!text-[14px] !text-gray-600 font-semibold cursor-pointer"
                                        >
                                            {{ core()->getChannelName($channel) }}
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.error
                                            control-name="channels[]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                @endforeach
                            </div>
                        </x-slot:content>
                    </x-admin::accordion>
                </div>
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
                        <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
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
