@inject('channels', 'Webkul\Core\Repositories\ChannelRepository')

@php
    $locale = core()->getRequestedLocaleCode();

    $selectedOptionIds = old('inventory_sources') ?? $page->channels->pluck('id')->toArray();
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.cms.edit.edit')
    </x-slot:title>

    <x-admin::form 
        :action="route('admin.cms.update', $page->id)"
        enctype="multipart/form-data"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.cms.edit.edit')
            </p>

            <div class="flex gap-x-[10px] items-center">
                @if ($page->translate($locale))
                    <a 
                        href="{{ route('shop.cms.page', $page->translate($locale)['url_key']) }}"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        target="_blank"
                    >
                        @lang('admin::app.cms.edit.preview')
                    </a>
                @endif

                <button 
                    type="submit"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.cms.edit.save')
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

        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                    {!! view_render_event('bagisto.admin.cms.pages.edit_form_accordian.general.before') !!}

                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.cms.edit.general')
                    </p>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.page')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="{{ $locale }}[page_title]"
                            value="{{ old($locale)['page_title'] ?? ($page->translate($locale)['page_title'] ?? '') }}"
                            id="{{ $locale }}[page_title]"
                            rules="required"
                            :label="trans('admin::app.cms.edit.page')"
                            :placeholder="trans('admin::app.cms.edit.page')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{ $locale }}[page_title]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.channels')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="channels[]"
                            id="channels[]"
                            :value="implode($selectedOptionIds)"
                            rules="required"
                            :label="trans('admin::app.cms.edit.channels')"
                            :placeholder="trans('admin::app.cms.edit.channels')"
                            multiple
                        >
                            @foreach($channels->all() as $channel)
                                <option value="{{ $channel->id }}" {{ in_array($channel->id, $selectedOptionIds) ? 'selected' : '' }}>
                                    {{ core()->getChannelName($channel) }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="channels[]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>  
                    
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.content')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="tinymce"
                            name="{{ $locale }}[html_content]"
                            :value="old('html_content')"
                            id="content"
                            rules="required"
                            :label="trans('admin::app.cms.edit.content')"
                            :placeholder="trans('admin::app.cms.edit.content')"
                        >
                            {{ old($locale)['html_content'] ?? ($page->translate($locale)['html_content'] ?? '') }}
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{ $locale }}[html_content]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    {!! view_render_event('bagisto.admin.cms.pages.edit_form_accordian.general.after') !!}
                </div>
            </div>
        </div>

        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                    {!! view_render_event('bagisto.admin.cms.pages.edit_form_accordian.seo.before') !!}

                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.cms.edit.seo')
                    </p>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta_title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="{{$locale}}[meta_title]"
                            :value="old($locale)['meta_title'] ?? ($page->translate($locale)['meta_title'] ?? '') "
                            id="meta_title"
                            rules="required"
                            :label="trans('admin::app.cms.edit.meta_title')"
                            :placeholder="trans('admin::app.cms.edit.meta_title')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{$locale}}[meta_title]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.url_key')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="{{$locale}}[url_key]"
                            :value="old($locale)['url_key'] ?? ($page->translate($locale)['url_key'] ?? '')"
                            id="url_key"
                            rules="required"
                            :label="trans('admin::app.cms.edit.url_key')"
                            :placeholder="trans('admin::app.cms.edit.url_key')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{$locale}}[url_key]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                    
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta_keywords')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="{{$locale}}[meta_keywords]"
                            :value="old($locale)['meta_keywords'] ?? ($page->translate($locale)['meta_keywords'] ?? '')"
                            id="meta_keywords"
                            class="text-gray-600"
                            rules="required"
                            :label="trans('admin::app.cms.edit.meta_keywords')"
                            :placeholder="trans('admin::app.cms.edit.meta_keywords')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{$locale}}[meta_keywords]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.edit.meta_description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="{{$locale}}[meta_description]"
                            :value="old($locale)['meta_description'] ?? ($page->translate($locale)['meta_description'] ?? '')"
                            id="meta_description"
                            class="text-gray-600"
                            rules="required"
                            :label="trans('admin::app.cms.edit.meta_description')"
                            :placeholder="trans('admin::app.cms.edit.meta_description')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="{{$locale}}[meta_description]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    {!! view_render_event('bagisto.admin.cms.pages.edit_form_accordian.seo.after') !!}
                </div>
            </div>
        </div>
    </x-admin::form>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-locale-switcher-template">
            <div>
                {{-- Locale dropdown --}}
                <x-admin::dropdown>
                    <x-slot:toggle>
                        {{-- Current Locale--}}
                        <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-language text-[24px] "></span>
                                @{{ selectedLocale[0].name }}
                            <span class="icon-sort-down text-[24px]"></span>
                        </div>
                    </x-slot:toggle>
                
                    {{-- Locale content --}}
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
