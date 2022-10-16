@php
    request()->query->remove('featured');

    request()->query->add([
        'new'   => 1,
        'order' => 'rand',
        'limit' => request()->get('count')
            ?? core()->getConfigData('catalog.products.homepage.no_of_new_product_homepage'),
    ]);

    $products = app(\Webkul\Product\Repositories\ProductRepository::class)->getAll();
@endphp

@if ($products->count())
    <section class="featured-products">

        <div class="featured-heading">
            {{ __('shop::app.home.new-products') }}<br/>

            <span class="featured-seperator" style="color: #d7dfe2;">_____</span>
        </div>

        <div class="product-grid-4">

            @foreach ($products as $productFlat)

                @include ('shop::products.list.card', ['product' => $productFlat])

            @endforeach

        </div>

    </section>
@endif
