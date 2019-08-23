@inject ('priceHelper', 'Webkul\Product\Helpers\Price')

@inject('productFlat', 'Webkul\Product\Repositories\ProductFlatRepository')

<script>
    dataLayer.push({
        'pageCategory': 'shop.checkout.cart.index',

        @php
            $cart = \Cart::getCart();
        @endphp

        @if ($cart)
            @foreach($cart->items as $item)
                @php
                    $product = $item->product;
                @endphp

                @if ($product->type == 'configurable')
                    'ecommerce': {
                        'currencyCode': '{{ core()->getCurrentCurrency()->code }}',
                        'impressions': [{
                            'id': '{{ $product->id ?? null }}',
                            'sku': '{{ $product->sku ?? null }}'
                            'category': '{{ $category ?? null }}',
                            'name': '{{ $product->name ?? null }}',
                            'price': '{{ core()->currency($priceHelper->getMinimalPrice($product)) ?? null }}'
                        }]
                    }
                @else
                    'ecommerce': {
                        'currencyCode': '{{ core()->getCurrentCurrency()->code }}',

                        'impressions': [{
                            'id': '{{ $product->id ?? null }}',
                            'name': '{{ $product->name ?? null }}',
                            'category': '{{ $category ?? null }}',

                            @if ($priceHelper->haveSpecialPrice($product))
                                'price': '{{ core()->currency($priceHelper->getSpecialPrice($product)) ?? null }}'
                            @else
                                'price': '{{ core()->currency($product->price) ?? null }}'
                            @endif
                        }]
                    }
                @endif
            @endforeach
        @endif
    });
</script>