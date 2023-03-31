{!! view_render_event('bagisto.shop.products.buy_now.before', ['product' => $product]) !!}

<button type="submit" class="theme-btn text-center buynow" {{ ! $product->isSaleable(1) ? 'disabled' : '' }}>
    {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.buy-now') }}
</button>

{!! view_render_event('bagisto.shop.products.buy_now.after', ['product' => $product]) !!}