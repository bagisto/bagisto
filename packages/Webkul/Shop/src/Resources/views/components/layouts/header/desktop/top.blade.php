<div
    class="flex justify-between items-center w-full border border-t-0 border-b-[1px] border-l-0 border-r-0 py-[11px] px-16"
>
    <select
        class="font-medium px-0 text-sm text-black bg-transparent border-0 border-gray-20 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer"
    >
        @foreach (core()->getCurrentChannel()->currencies as $currency)
            <option value="{{ $currency->code }}"
                {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}
            >
                {{ $currency->code }}
            </option>
        @endforeach
    </select>

    <p class="text-xs font-medium">Get UPTO 40% OFF on your 1st order <a href="" class="underline">SHOP NOW</a>
    </p>

    <select
        class="font-medium px-0 text-sm text-black bg-transparent border-0 border-gray-20 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer"
    >
        @foreach (core()->getCurrentChannel()->locales as $locale)
            <option value="{{ $locale->code }}"
                {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>
                {{ $locale->name }}
            </option>
        @endforeach
    </select>
</div>
