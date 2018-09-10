<div class="cart-fav-seg">
    
    @include ('shop::products.add-to-cart', ['product' => $product])

    <span><img src="{{ bagisto_asset('images/wishlist.svg') }}" /></span>
    
</div>