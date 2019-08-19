@if ($product->type == 'grouped')
    {!! view_render_event('bagisto.shop.products.view.grouped_products.before', ['product' => $product]) !!}

    <div class="grouped-product-container">
        @if ($product->grouped_products->count())
            <div class="grouped-product-list">
                <ul>
                    <li>
                        <span>{{ __('shop::app.products.name') }}</span>
                        <span>{{ __('shop::app.products.qty') }}</span>
                    </li>
                    @foreach ($product->grouped_products as $groupedProduct)
                        <li>
                            <span class="name">
                                {{ $groupedProduct->associated_product->name }}

                                @include ('shop::products.price', ['product' => $groupedProduct->associated_product])
                            </span>
                            <span class="qty">
                                <div class="control-group">
                                    <input name="qty[{{$groupedProduct->associated_product_id}}]" class="control" value="{{ $groupedProduct->qty }}"/>
                                </div>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.products.view.grouped_products.before', ['product' => $product]) !!}
@endif