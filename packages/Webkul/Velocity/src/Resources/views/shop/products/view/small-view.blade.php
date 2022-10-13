@php
    $productBaseImage = product_image()->getProductBaseImage($product);
@endphp

<div class="col-lg-3 col-md-12">
    <a class="row" href="{{ route('shop.productOrCategory.index', $product->url_key) }}">
        <img src="{{ $productBaseImage['medium_image_url'] }}" class="col-12" alt="" />
    </a>

    <a class="row pt15 unset" href="{{ route('shop.productOrCategory.index', $product->url_key) }}">
        <h2 class="col-12 fw6 link-color">{{ $product->name }}</h2>
    </a>
</div>