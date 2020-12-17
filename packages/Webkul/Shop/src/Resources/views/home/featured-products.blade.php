@if (count(app('Webkul\Product\Repositories\ProductRepository')->getFeaturedProducts()))
    <section class="featured-products">

        <div class="featured-heading">
            {{ __('shop::app.home.featured-products') }}<br/>

            <span class="featured-seperator" style="color: #d7dfe2;">_____</span>
        </div>

        <div class="featured-grid product-grid-4">

            @foreach (app('Webkul\Product\Repositories\ProductRepository')->getFeaturedProducts() as $productFlat)

                @include ('shop::products.list.card', ['product' => $productFlat])

            @endforeach

        </div>

    </section>
@endif