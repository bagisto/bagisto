{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

<div class="stock-status {{! $product->haveSufficientQuantity(1) ? '' : 'active' }}">
    @if ( $product->haveSufficientQuantity(1) === true )
        {{ __('shop::app.products.in-stock') }}
    @elseif ( $product->haveSufficientQuantity(1) > 0 )
        {{ __('shop::app.products.available') }}
    @else
        {{ __('shop::app.products.out-of-stock') }}
    @endif
</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}