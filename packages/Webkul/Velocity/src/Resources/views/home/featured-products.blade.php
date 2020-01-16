@php
    $count = $velocityMetaData->featured_product_count;

    $featuredProducts = app('Webkul\Velocity\Repositories\Product\ProductRepository')->getFeaturedProducts($count);

    $featuredProductsCount = $featuredProducts->count();
@endphp

@if ($featuredProductsCount)

    <div class="container-fluid featured-products">

        <card-list-header heading="{{ __('shop::app.home.featured-products') }}">
        </card-list-header>

        <div class="row flex-nowrap">

            <carousel-component
                slides-per-page="6"
                navigation-enabled="hide"
                pagination-enabled="hide"
                id="fearured-products-carousel"
                :slides-count="{{ $featuredProductsCount }}">

                @foreach ($featuredProducts as $index => $product)

                    <slide slot="slide-{{ $index }}">
                        @include ('shop::products.list.card', ['product' => $product])
                    </slide>

                @endforeach

            </carousel-component>
        </div>

    </div>
@endif
