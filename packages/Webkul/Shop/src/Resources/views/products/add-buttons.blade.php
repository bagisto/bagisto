@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@php
    $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;

    $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
@endphp

<div class="{{ $toolbarHelper->isModeActive('grid') ? 'cart-wish-wrap' : 'default-wrap' }}">
    <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
        <input type="hidden" name="quantity" value="1">
        <button class="btn btn-lg btn-primary addtocart" {{ $product->isSaleable() ? '' : 'disabled' }}>{{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}</button>
    </form>

    @if ($showWishlist)
        @include('shop::products.wishlist')
    @endif

    @if ($showCompare)
        @include('shop::products.compare', [
            'productId' => $product->id
        ])
    @endif
</div>