<div class="cart-wish-wrap">
    <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
        <input type="hidden" name="quantity" value="1">
        <button class="btn btn-lg btn-primary addtocart" {{ $product->isSaleable() ? '' : 'disabled' }}>
            @if($product->type == 'booking')
                {{ __('shop::app.products.book-now') }}
            @else
                {{ __('shop::app.products.add-to-cart') }}
            @endif
        </button>
    </form>

    @include('shop::products.wishlist')

    @include('shop::products.compare', [
        'productId' => $product->id
    ])
</div>