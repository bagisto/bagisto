@if ($products->count())
    <section class="featured-products">

        <div class="featured-heading">
            {{ __('shop::app.home.featured-products') }}<br/>
            
            <span class="featured-seperator" style="color:lightgrey;">_____</span>
        </div>

        <div class="featured-grid product-grid-4">

            @foreach ($products as $productFlat)

                @include ('shop::products.list.card', ['product' => $productFlat->product])

            @endforeach

        </div>

    </section>
@endif