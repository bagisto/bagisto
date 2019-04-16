{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

<div class="stock-status {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? '' : 'active' }}">
    {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? __('shop::app.products.out-of-stock') : __('shop::app.products.in-stock') }}
</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}