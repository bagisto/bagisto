@inject ('priceHelper', 'Webkul\Product\Helpers\Price')

@inject('productFlat', 'Webkul\Product\Repositories\ProductFlatRepository')

@php
    $uri = request()->getRequestUri();

    $uri = explode('/', $uri);

    $slug = last($uri);

    $product = $productFlat->findWhere([
        'url_key' => $slug,
        'locale' => app()->getLocale(),
        'channel' => core()->getCurrentChannel()->code
    ]);
@endphp

@if (count($product))
    @php
        $product = $product->first()->product;

        $category = $product->categories->first()->slug;
    @endphp

    <script>
        dataLayer.push({
            'pageCategory': 'shop.products.index',

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
        });
    </script>
@endif