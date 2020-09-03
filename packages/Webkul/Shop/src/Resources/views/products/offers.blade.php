{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

<div class="product-offers">
    {!! $product->getTypeInstance()->getOffersHtml() !!}
</div>

{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}