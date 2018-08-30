<div class="product-card">
    <div class="product-image">
        <a href="{{ route('shop.products.index', $product->url_key) }}" title="{{ $product->name }}">
            <img src="{{ bagisto_asset('images/gogs.png') }}" />
        </a>
    </div>

    <div class="product-information">
    
        <div class="product-name">
                
            {{ $product->id }}

            <a href="" title="{{ $product->name }}">
                <span>{{ $product->name }}</span>
            </a>
        </div>

        <div class="product-description">
            {{ $product->short_description }}
        </div>
        
        @include ('shop::products.price', ['product' => $product])

        @if ($product->reviews->count())

            @include ('shop::products.review', ['product' => $product])

        @endif

        @include ('shop::products.add-to', ['product' => $product])
    
    </div>

</div>