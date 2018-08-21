<div class="product-card">
    <div class="product-image">
        <img src="{{ bagisto_asset('images/gogs.png') }}" />
    </div>
    
    <div class="product-name">
        <span>{{ $product->name }}</span>
    </div>
    
    @include ('shop::products.price', ['product' => $product])

    @if ($product->reviews->count())

        @include ('shop::products.review', ['product' => $product])

    @endif

    @include ('shop::products.add-to', ['product' => $product])

</div>