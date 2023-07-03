@php
    $searchQuery = request()->input();

    if (
        $searchQuery
        && ! empty($searchQuery)
    ) {
        $searchQuery = implode('&', array_map(
            function ($v, $k) {
                if (is_array($v)) {
                    if (is_array($v)) {
                        $key = array_keys($v)[0];

                        return $k. "[$key]=" . implode('&' . $k . '[]=', $v);
                    } else {
                        return $k. '[]=' . implode('&' . $k . '[]=', $v);
                    }
                } else {
                    return $k . '=' . $v;
                }
            },
            $searchQuery,
            array_keys($searchQuery)
        ));
    } else {
        $searchQuery = false;
    }
@endphp

<div
    class="flex justify-between items-center w-full border border-t-0 border-b-[1px] border-l-0 border-r-0 py-[11px] px-16"
>
    <x-shop::dropdown>
        <x-slot:toggle>
            <div class="flex">
                <span class="cursor-pointer">{{ core()->getCurrentCurrency()->symbol }}</span>

                <span class="ml-2 cursor-pointer">
                    {{core()->getCurrentCurrencyCode()}}
                </span>
            </div>
        </x-slot:toggle>

        <x-slot:content>
            <ul>
                @foreach (core()->getCurrentChannel()->currencies as $currency)
                    <li class="cursor-pointer">
                        <a href="?{{ ! empty($searchQuery) ? $searchQuery : '' }}&currency={{ $currency->code }}">
                            {{ $currency->code }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-slot:content>
    </x-shop::dropdown>

    <p class="text-xs font-medium">Get UPTO 40% OFF on your 1st order <a href="#" class="underline">SHOP NOW</a></p>

    <x-shop::dropdown>
        <x-slot:toggle>
            <div class="flex">
                @if(! empty(core()->getCurrentLocale()->image_url))
                    <img src="{{ core()->getCurrentLocale()->image_url }}" alt="" width="20" height="20" />
                @else
                    <img src="{{ asset('/themes/velocity/assets/images/flags/default-locale-image.png') }}" alt="" width="20" height="20" />
                @endif
                
                <span class="ml-2 cursor-pointer">
                    {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                </span>
            </div>
        </x-slot:toggle>
    
        <x-slot:content>
            <ul>
                @foreach (core()->getCurrentChannel()->locales()->orderBy('name')->get() as $locale)
                    <li class="cursor-pointer hover:bg-gray-80">
                        <a href="?{{ ! empty($searchQuery) ? $searchQuery : '' }}&locale={{ $locale->code }}">
                            {{ $locale->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-slot:content>
    </x-shop::dropdown>
</div>