@if ($product->related_products()->count())
    <div class="attached-products-wrapper">

        <div class="title">
            {{ __('shop::app.products.related-product-title') }}
            <span class="border-bottom"></span>
        </div>

        <div class="product-grid-4">

            @foreach ($product->related_products()->paginate(4) as $related_product)

                @include ('shop::products.list.card', ['product' => $related_product])

            @endforeach

        </div>

    </div>
@endif