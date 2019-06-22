{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}

<div class="add-to-buttons">
    @if ($product->type != 'configurable')
        @if ($product->totalQuantity() < 1 && $product->allow_preorder)
            <button type="submit" class="btn btn-lg btn-primary addtocart" style="width: 100%">
                {{ __('preorder::app.shop.products.preorder') }}
            </button>
        @else
            @include ('shop::products.add-to-cart', ['product' => $product])

            @include ('shop::products.buy-now')
        @endif
    @else
        @include ('shop::products.add-to-cart', ['product' => $product])

        @include ('shop::products.buy-now')
    @endif
</div>

{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}