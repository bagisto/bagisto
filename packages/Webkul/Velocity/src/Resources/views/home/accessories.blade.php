@php
    $newProducts = app('Webkul\Product\Repositories\ProductRepository')->getNewProducts(6)->items();

    $products = array_merge(array_merge($newProducts, $newProducts), $newProducts);

    $showRecentlyViewed = true;
@endphp

<div class="container-fluid accessories">
    <card-list-header
        heading="Accessories"
        view-all="{{
            (isset($cardCount) && (sizeof($products) > $cardCount))
            ? 'http://localhost/PHP/laravel/Bagisto/bagisto-clone/public/categories/category1'
            : false
        }}"
        scrollable="accessories-carousel"
    ></card-list-header>

    <div class="row flex-nowrap">
        <carousel-component
            :slides-count="{{ sizeof($products) }}"
            slides-per-page="6"
            id="accessories-carousel">

            @foreach ($products as $index => $product)

                <slide slot="slide-{{ $index }}">
                    @include ('shop::products.list.card', ['product' => $product])
                </slide>

            @endforeach

        </carousel-component>
    </div>
</div>