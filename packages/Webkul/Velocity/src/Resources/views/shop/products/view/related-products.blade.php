<?php
    $relatedProducts = $product->related_products()->get();
?>

@if ($relatedProducts->count())
    <card-list-header
        heading="{{ __('shop::app.products.related-product-title') }}"
        view-all="false"
        row-class="pt20"
    ></card-list-header>

    <div class="carousel-products vc-full-screen">
        <carousel-component
            slides-per-page="6"
            navigation-enabled="hide"
            pagination-enabled="hide"
            id="related-products-carousel"
            :slides-count="{{ sizeof($relatedProducts) }}">

            @foreach ($relatedProducts as $index => $relatedProduct)
                <slide slot="slide-{{ $index }}">
                    @include ('shop::products.list.card', [
                        'product' => $relatedProduct,
                        'addToCartBtnClass' => 'small-padding',
                    ])
                </slide>
            @endforeach
        </carousel-component>
    </div>

    <div class="carousel-products vc-small-screen">
        <carousel-component
            :slides-count="{{ sizeof($relatedProducts) }}"
            slides-per-page="2"
            id="related-products-carousel"
            navigation-enabled="hide"
            pagination-enabled="hide">

            @foreach ($relatedProducts as $index => $relatedProduct)
                <slide slot="slide-{{ $index }}">
                    @include ('shop::products.list.card', [
                        'product' => $relatedProduct,
                        'addToCartBtnClass' => 'small-padding',
                    ])
                </slide>
            @endforeach
        </carousel-component>
    </div>
@endif