{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

@if ($product->type == 'simple')
    <div class="stock-status {{! $product->haveSufficientQuantity(1) ? '' : 'active' }}">
        {{ $product->haveSufficientQuantity(1) ? __('shop::app.products.in-stock') : __('shop::app.products.out-of-stock') }}
    </div>
@else
    <div class="stock-status in-stock" id="in-stock" style="display: none;">
        {{ __('shop::app.products.in-stock') }}
    </div>
@endif

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}