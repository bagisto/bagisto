{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

<div class="stock-status {{! $product->haveSufficientQuantity(1) ? '' : 'active' }}">
    {{ $product->haveSufficientQuantity(1) ? __('shop::app.products.in-stock') : __('shop::app.products.out-of-stock') }}
</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}