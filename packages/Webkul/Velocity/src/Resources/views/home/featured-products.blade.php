@php
    $featuredProducts = app('Webkul\Product\Repositories\ProductRepository')->getFeaturedProducts(6);

    $featuredProductsCount = $featuredProducts->count();

    $showRecentlyViewed = true;
@endphp

@if ($featuredProductsCount)

    <div class="container-fluid popular-products no-padding">

        <card-list-header
            heading="{{ __('shop::app.home.featured-products') }}"

            view-all="{{
                $featuredProductsCount > (isset($cardCount) ? $cardCount : 6)
                ? 'http://localhost/PHP/laravel/Bagisto/bagisto-clone/public/categories/category1'
                : false
            }}"

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
