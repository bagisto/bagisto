<button type="submit" data-href="{{ route('shop.product.buynow', $product->id)}}" class="btn btn-lg btn-primary buynow" {{ $product->type != 'configurable' && !$product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
    {{ __('shop::app.products.buy-now') }}
</button>