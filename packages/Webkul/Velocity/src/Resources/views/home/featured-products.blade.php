@php
    $count = 4;
    $config = core()->getConfigData('velocity.configuration.general.featured_product');

    if ($config > 4)
        $count = $config;

    $featuredProducts = app('Webkul\Velocity\Repositories\Product\ProductRepository')->getFeaturedProducts($count);

    $featuredProductsCount = $featuredProducts->count();
@endphp

@if ($featuredProductsCount)

    <div class="container-fluid popular-products">

        <card-list-header
            heading="{{ __('shop::app.home.featured-products') }}"
            scrollable="{{
                ($featuredProductsCount > (isset($cardCount) ? $cardCount : 6))
                ? 'fearured-products-carousel'
                : false
            }}"

        ></card-list-header>

        <div class="row flex-nowrap">

            <carousel-component
                :slides-count="{{ $featuredProductsCount }}"
                slides-per-page="6"
                id="fearured-products-carousel">

                @foreach ($featuredProducts as $index => $product)

                    <slide slot="slide-{{ $index }}">
                        @include ('shop::products.list.card', ['product' => $product])
                    </slide>

                @endforeach

            </carousel-component>
        </div>

    </div>
@endif
