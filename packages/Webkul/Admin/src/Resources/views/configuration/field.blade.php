@php
    $locale = core()->checkRequestedLocaleCodeInRequestedChannel();
    
    $channel = core()->getRequestedChannelCode();
    
    $channelLocales = core()->getAllLocalesByRequestedChannel()['locales'];
@endphp

<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @if ($items = \Illuminate\Support\Arr::get($config->items, request()->route('slug') . '.children'))
            @foreach ($items as $key => $item)
                @if ( $key == request()->route('slug2'))
                    {{ $title = trans($item['name']) }}
                @endif
            @endforeach
        @endif
    </x-slot:title>

    {{-- Configuration form fields --}}
    <x-admin::form 
        action="" 
        enctype="multipart/form-data"
    >
        <a href="{{ route('admin.configuration.index') }}">
            <div class="flex items-center cursor-pointer">
                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-center w-full max-w-max rounded-[6px]">
                    <span class="icon-sort-left text-[24px]"></span>
                </div>
                
                <p class="text-gray-600">
                    @lang('admin::app.configuration.title') / {{ $title }}
                </p>
            </div>
        </a>

        {{-- Save Inventory --}}
        <div class="flex gap-[16px] justify-between items-center mt-[14px] max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                {{ $title }}
            </p>

            {{-- Save Inventory --}}
            <div class="flex gap-x-[10px] items-center">
                <button 
                    type="submit"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.configuration.save-btn')
                </button>
            </div>
        </div>

        <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                <div>
                    {{-- Channel switcher --}}
                    <v-channel-switcher></v-channel-switcher>
                </div>
                
                <div>
                    {{-- Locale switcher --}}
                    <v-locale-switcher></v-locale-switcher>
                </div>
            </div>
        </div>

        @if ($groups)
            <div class="grid grid-cols-[1fr_2fr] gap-[10px] mt-[25px] max-xl:flex-wrap">
                @foreach ($groups as $key => $item)
                    <div>
                        <p class="text-[16px] text-gray-600 font-semibold">
                            @lang($item['name'])
                        </p>

                        <p class="text-gray-600 mt-[4px]">
                            @lang($item['info'] ?? '')
                        </p>
                    </div>

                    <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                        @foreach ($item['fields'] as $field)
                            @include ('admin::configuration.field-type')
                        
                            @php ($hint = $field['title'] . '-hint')

                            @if ($hint !== __($hint))
                                <label 
                                    for="@lang($hint)"
                                    class="block leading-[20px] text-[12px] text-gray-600 font-medium"
                                >
                                    @lang($hint)
                                </label>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </x-admin::form>

    @pushOnce('scripts')
        {{-- Locale switcher template --}}
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
                            <input 
                                class="hidden"
                                name="locale"
                                :value="selectedLocale[0].code"
                            >

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

        {{-- Locale switcher component --}}
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

        {{-- Channel switcher template --}}
        <script 
            type="text/x-template"
            id="v-channel-switcher-template"
        >
            <div>
                {{-- channel dropdown --}}
                <x-admin::dropdown>
                    <x-slot:toggle>
                        {{-- Current channel--}}
                        <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-language text-[24px] "></span>
                                @{{ selectedChannel[0].name }}
                            <span class="icon-sort-down text-[24px]"></span>
                        </div>
                    </x-slot:toggle>
                
                    {{-- Channel content --}}
                    <x-slot:content class="!p-[0px]">
                        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
                            <input 
                                class="hidden"
                                name="channel"
                                :value="selectedChannel[0].code"
                            >

                            <a
                                class="px-5 py-2 text-[16px] hover:bg-gray-100 cursor-pointer"
                                v-for="channel in channels"
                                :class="{'bg-gray-100': channel.code == '{{ $channel }}'}"
                                v-text="channel.name"
                                @click="change(channel)"
                            >
                            </a>
                        </div>
                    </x-slot:content>
                </x-admin::dropdown>
            </div>
        </script>

        {{-- channel switcher component --}}
        <script type="module">
            app.component('v-channel-switcher', {
                template: '#v-channel-switcher-template',

                data() {
                    return {
                       channels: @json(core()->getAllChannels()),

                       selectedChannel: '',
                    }
                },

                created() {
                    this.init();
                },

                methods: {
                    init() {
                        this.selectedChannel = this.channels.filter((channel) => "{{ $channel }}" == channel.code);
                    },

                    change(channel) {
                        let url = new URL(window.location.href);

                        url.searchParams.set('channel', channel.code);

                        window.location.href = url.href;
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
