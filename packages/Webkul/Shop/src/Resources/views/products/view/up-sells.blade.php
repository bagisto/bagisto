@if ($product->up_sells()->count())
    <div class="attached-products-wrapper">

        <div class="title">
            {{ __('shop::app.products.up-sell-title') }}
            <span class="border-bottom"></span>
        </div>

        <div class="product-grid max-4-col">
                    
            @foreach ($product->up_sells()->paginate(4) as $up_sell_product)

                @include ('shop::products.list.card', ['product' => $up_sell_product])

            @endforeach

        </div>

    </div>
@endif