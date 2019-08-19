<div class="cart-wish-wrap">
    <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
        @csrf
        <input type="hidden" name="product" value="{{ $product->product_id }}">
        <input type="hidden" name="quantity" value="1">
        <button class="btn btn-lg btn-primary addtocart" {{ $product->isSaleable() ? '' : 'disabled' }}>{{ __('shop::app.products.add-to-cart') }}</button>
    </form>

    @include('shop::products.wishlist')
</div>