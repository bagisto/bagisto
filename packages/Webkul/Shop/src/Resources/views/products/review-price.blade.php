<div class="product-price mt-10">
    @inject ('priceHelper', 'Webkul\Product\Helpers\Price')

    @if ($product->type == 'configurable')
        <span class="pro-price">{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>
    @else
        @if ($priceHelper->haveSpecialPrice($product))
            <span class="pro-price">{{ core()->currency($priceHelper->getSpecialPrice($product)) }}</span>
        @else
            <span class="pro-price">{{ core()->currency($product->price) }}</span>
        @endif
    @endif
</div>