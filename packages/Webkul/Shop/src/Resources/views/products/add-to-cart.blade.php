<button class="btn btn-lg btn-primary addtocart" {{ $product->type != 'configurable' && !$product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
    {{ __('shop::app.products.add-to-cart') }}
</button>