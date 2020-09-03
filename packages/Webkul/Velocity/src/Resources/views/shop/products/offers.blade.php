{!! view_render_event('bagisto.shop.products.offers.before', ['product' => $product]) !!}

<div class="product-offers">
    {!! $product->getTypeInstance()->getOffersHtml() !!}
</div>

{!! view_render_event('bagisto.shop.products.offers.after', ['product' => $product]) !!}