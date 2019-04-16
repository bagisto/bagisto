@if (app('Webkul\Product\Repositories\ProductRepository')->getNewProducts()->count())
    <section class="featured-products">

        <div class="featured-heading">
            {{ __('shop::app.home.new-products') }}<br/>

            <span class="featured-seperator" style="color:lightgrey;">_____</span>
        </div>

        <div class="product-grid-4">

            @foreach (app('Webkul\Product\Repositories\ProductRepository')->getNewProducts() as $productFlat)

                @include ('shop::products.list.card', ['product' => $productFlat])

            @endforeach

        </div>

    </section>
@endif
