
@if ($minPrice < $price)
    <div class="sticker sale">{{ trans('shop::app.products.sale') }}</div>
    <span class="regular-price">{{ core()->currency($evaluatePrice) }}</span>'
    <span class="special-price">{{ core()->currency($evaluatePrice) }}</span>';
@else
    <span>{{ core()->currency($evaluatePrice) }}</span>
@endif