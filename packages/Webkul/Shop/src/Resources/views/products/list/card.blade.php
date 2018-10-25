<div class="product-card">

    @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

    <?php $productBaseImage = $productImageHelper->getProductBaseImage($product); ?>

    @if($product->new)
        <div class="sticker new">
            {{ __('shop::app.products.new') }}
        </div>
    @endif

    <div class="product-image">
        <a href="{{ route('shop.products.index', $product->url_key) }}" title="{{ $product->name }}">
            <img src="{{ $productBaseImage['medium_image_url'] }}" />
        </a>
    </div>

    <div class="product-information">

        <div class="product-name">
            <a href="{{ url()->to('/').'/products/'.$product->url_key }}" title="{{ $product->name }}">
                <span>
                    {{ $product->name }}
                </span>
            </a>
        </div>

        <div class="product-description">
            {{ $product->short_description }}
        </div>

        @include ('shop::products.price', ['product' => $product])

        @include ('shop::products.review', ['product' => $product])

        @if(Route::currentRouteName() == "shop.products.index")
            @include ('shop::products.add-to', ['product' => $product])
        @else
            @if($product->type == "configurable")
                <div class="cart-wish-wrap">
                    <a href="{{ route('cart.add.configurable', $product->url_key) }}" class="btn btn-lg btn-primary addtocart">{{ __('shop::app.products.add-to-cart') }}</a>
                @include('shop::products.wishlist')
                </div>
            @else
                <div class="cart-wish-wrap">
                    <form action="{{route('cart.add', $product->id)}}" method="POST">
                        @csrf
                        <input type="hidden" name="product" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" value="false" name="is_configurable">
                        <button class="btn btn-lg btn-primary addtocart">{{ __('shop::app.products.add-to-cart') }}</button>
                    </form>
                    @include('shop::products.wishlist')
                </div>
            @endif
        @endif
    </div>

</div>