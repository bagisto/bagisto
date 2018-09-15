<div class="cart-fav-seg">

    @include ('shop::products.add-to-cart', ['product' => $product])

    @include ('shop::products.buy-now')

    {{-- <span><img src="{{ bagisto_asset('images/wishlist.svg') }}" /></span> --}}

</div>