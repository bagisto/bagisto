<div class="product-card">

    @inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

    <?php $productBaseImage = $productImageHelper->getProductBaseImage($product); ?>

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

        @include ('shop::products.add-to', ['product' => $product])

    </div>

</div>