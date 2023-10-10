@php
    $channels = core()->getAllChannels();

    $currentChannel = core()->getRequestedChannel();

    $currentLocale = core()->getRequestedLocale();
@endphp

<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @if ($items = Arr::get($config->items, request()->route('slug') . '.children'))
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
        {{-- Save Inventory --}}
        <div class="flex gap-[16px] justify-between items-center mt-[14px] max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                {{ $title }}
            </p>

            {{-- Save Inventory --}}
            <div class="flex gap-x-[10px] items-center">
                <button 
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.configuration.index.save-btn')
                </button>
            </div>
        </div>

        <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                {{-- Channel Switcher --}}
                <x-admin::dropdown :class="$channels->count() <= 1 ? 'hidden' : ''">
                    {{-- Dropdown Toggler --}}
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-[4px] py-[6px] hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                        >
                            <span class="icon-store text-[24px] "></span>
                            
                            {{ $currentChannel->name }}

                            <input type="hidden" name="channel" value="{{ $currentChannel->code }}"/>

                            <span class="icon-sort-down text-[24px]"></span>
                        </button>
                    </x-slot:toggle>

                    {{-- Dropdown Content --}}
                    <x-slot:content class="!p-[0px]">
                        @foreach ($channels as $channel)
                            <a
                                href="?{{ Arr::query(['channel' => $channel->code, 'locale' => $currentLocale->code]) }}"
                                class="flex gap-[10px] px-5 py-2 text-[16px] cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white"
                            >
                                {{ $channel->name }}
                            </a>
                        @endforeach
                    </x-slot:content>
                </x-admin::dropdown>

                {{-- Channel Switcher --}}
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
                        @foreach ($currentChannel->locales as $locale)
                            <a
                                href="?{{ Arr::query(['channel' => $currentChannel->code, 'locale' => $locale->code]) }}"
                                class="flex gap-[10px] px-5 py-2 text-[16px] cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot:content>
                </x-admin::dropdown>
            </div>
        </div>

        @if ($groups)
            <div class="grid grid-cols-[1fr_2fr] gap-[10px] mt-[25px] max-xl:flex-wrap">
                @foreach ($groups as $key => $item)
                    <div>
                        <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                            @lang($item['name'])
                        </p>

                        <p class="text-gray-600 dark:text-gray-300 mt-[4px]">
                            @lang($item['info'] ?? '')
                        </p>
                    </div>

                    <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        @foreach ($item['fields'] as $field)
                            @include ('admin::configuration.field-type')
                        
                            @php ($hint = $field['title'] . '-hint')

                            @if ($hint !== __($hint))
                                <label 
                                    for="@lang($hint)"
                                    class="block leading-[20px] text-[12px] text-gray-600 dark:text-gray-300 font-medium"
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
</x-admin::layouts>
