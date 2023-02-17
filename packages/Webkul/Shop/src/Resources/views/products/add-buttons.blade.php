@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

<div class="{{ $toolbarHelper->isModeActive('grid') ? 'cart-wish-wrap' : 'default-wrap' }}">
    <form action="{{ route('shop.cart.add', $product->id) }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" value="1">
        <button class="btn btn-lg btn-primary addtocart" {{ $product->isSaleable() ? '' : 'disabled' }}>{{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}</button>
    </form>

    @if ((bool) core()->getConfigData('general.content.shop.wishlist_option'))
        @include('shop::products.wishlist')
    @endif

    @if ((bool) core()->getConfigData('general.content.shop.compare_option'))
        @include('shop::products.compare', [
            'productId' => $product->id
        ])
    @endif
</div>