@if ($prices['final']['price'] < $prices['regular']['price'])
    <p
        class="font-medium text-zinc-500 line-through max-sm:leading-4"
        aria-label="{{ $prices['regular']['formatted_price'] }}"
    >
        {{ $prices['regular']['formatted_price'] }}
    </p>

    <p class="font-semibold max-sm:leading-4">
        {{ $prices['final']['formatted_price'] }}
    </p>
@else
    <p class="font-semibold max-sm:leading-4">
        {{ $prices['regular']['formatted_price'] }}
    </p>
@endif