{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

<div class="col-12 availability">
    <button
        type="button"
        class="{{! $product->haveSufficientQuantity(1) ? '' : 'active' }} disable-box-shadow">
        {{ $product->haveSufficientQuantity(1) ? __('shop::app.products.in-stock') : __('shop::app.products.out-of-stock') }}
    </button>
</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}