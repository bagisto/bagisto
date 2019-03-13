@foreach ($cart->items as $item)
    <?php
        $products[] = $item->product;
    ?>
@endforeach

@if ($products)

    @foreach ($products as $product)

        @if ($product->cross_sells()->count())
            <div class="attached-products-wrapper mt-50">

                <div class="title">
                    {{ __('shop::app.products.cross-sell-title') }}
                    <span class="border-bottom"></span>
                </div>

                <div class="product-grid-4">

                    @foreach ($product->cross_sells()->paginate(1) as $cross_sell_product)

                        @include ('shop::products.list.card', ['product' => $cross_sell_product])

                    @endforeach

                </div>

            </div>

        @endif

    @endforeach

@endif

