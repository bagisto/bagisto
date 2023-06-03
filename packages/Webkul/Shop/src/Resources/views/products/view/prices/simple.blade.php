
@if ($minPrice < $price)
    <div class="sticker sale">{{ trans('shop::app.products.sale') }}</div>
    <span class="regular-price">{{ core()->currency($roundOffPrice) }}</span>'
    <span class="special-price">{{ core()->currency($roundOffPrice) }}</span>';
@else
    <span>{{ core()->currency($roundOffPrice) }}</span>
@endif
