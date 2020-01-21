{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

<button type="submit" class="btn btn-lg btn-primary addtocart" {{ ! $product->isSaleable() ? 'disabled' : '' }}>
    {{ __('shop::app.products.add-to-cart') }}
</button>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}