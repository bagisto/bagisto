{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

<div class="col-12 availability">
    @php
        $inStock = $product->haveSufficientQuantity(1);
    @endphp

    <label class="{{ $inStock ? 'active' : '' }} disable-box-shadow">
        @if ($inStock)
            {{ __('shop::app.products.in-stock') }}
        @else
            {{ __('shop::app.products.out-of-stock') }}
        @endif
    </label>
</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}