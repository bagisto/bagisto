<p class="price-label text-sm text-zinc-500 max-sm:text-xs max-sm:leading-4">
    @lang('shop::app.products.prices.configurable.as-low-as')
</p>

@if (isset($prices['final']) && $prices['final']['price'] < $prices['regular']['price'])
    <p class="regular-price text-lg font-semibold text-gray-500 line-through max-sm:text-sm max-sm:leading-4">
        {{ $prices['regular']['formatted_price'] }}
    </p>

    <p class="final-price font-semibold max-sm:leading-4">
        {{ $prices['final']['formatted_price'] }}
    </p>
@else
    <p class="regular-price text-lg font-semibold text-gray-500 line-through max-sm:text-sm max-sm:leading-4" style="display: none;"></p>

    <p class="final-price font-semibold max-sm:leading-4">
        {{ $prices['regular']['formatted_price'] }}
    </p>
@endif