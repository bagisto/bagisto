@if ($crossSellProductsCount = $crossSellProducts->count())
    <card-list-header
        heading="{{ __('shop::app.products.cross-sell-title') }}"
        view-all="false"
        row-class="pt20"
    ></card-list-header>

    <div class="carousel-products vc-full-screen">
        <carousel-component
            slides-per-page="6"
            navigation-enabled="hide"
            pagination-enabled="hide"
            id="upsell-products-carousel"
            :slides-count="{{ $crossSellProductsCount }}">
            
            @foreach ($crossSellProducts as $index => $crossSellProduct)
                <slide slot="slide-{{ $index }}">
                    @include ('shop::products.list.card', [
                        'product' => $crossSellProduct,
                        'addToCartBtnClass' => 'small-padding',
                    ])
                </slide>
            @endforeach
        </carousel-component>
    </div>

    <div class="carousel-products vc-small-screen">
        <carousel-component
            :slides-count="{{ $crossSellProductsCount }}"
            slides-per-page="2"
            id="upsell-products-carousel"
            navigation-enabled="hide"
            pagination-enabled="hide">

            @foreach ($crossSellProducts as $index => $crossSellProduct)
                <slide slot="slide-{{ $index }}">
                    @include ('shop::products.list.card', [
                        'product' => $crossSellProduct,
                        'addToCartBtnClass' => 'small-padding',
                    ])
                </slide>
            @endforeach
        </carousel-component>
    </div>
@endif
