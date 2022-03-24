{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

<div class="col-12 availability">
    <label
        class="{{! $product->haveSufficientQuantity(1) ? '' : 'active' }} disable-box-shadow">
            @if ( $product->haveSufficientQuantity(1) === true )
                {{ __('shop::app.products.in-stock') }}
            @elseif ( $product->haveSufficientQuantity(1) > 0 )
                {{ __('shop::app.products.available-for-order') }}
            @else
                {{ __('shop::app.products.out-of-stock') }}
            @endif
    </label>
</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}