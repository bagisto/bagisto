@if ($haveDiscount)
    <span class="inline-block price-label px-2 py-1 mr-2 text-xs font-semibold tracking-wide text-white bg-navyBlue rounded-full">
       @lang('shop::app.products.sale')
    </div>

    <span class="special-price">
        {{ $minPrice }}
    </span>
@else 
    <span class="inline-block price-label px-2 py-1 mr-2 text-xs font-semibold tracking-wide text-white bg-navyBlue rounded-full">
        @lang('shop::app.products.sale')
    </span>

    <span class="special-price">
        {{ $minPrice }}
    </span> 
@endif