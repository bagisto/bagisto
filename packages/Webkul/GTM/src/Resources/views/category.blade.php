@inject ('priceHelper', 'Webkul\Product\Helpers\Price')

@inject('productFlat', 'Webkul\Product\Repositories\ProductFlatRepository')

@php
    $uri = request()->getRequestUri();

    $uri = explode('/', $uri);

    $slug = last($uri);

    $productRepository = app('Webkul\Product\Repositories\ProductRepository');

    $categoryRepository = app('Webkul\Category\Repositories\CategoryRepository');

    $category = $categoryRepository->findBySlugOrFail($slug);

    $products = $productRepository->getAll($category->id);

    $toolbarHelper =  app('Webkul\Product\Helpers\Toolbar');
@endphp

<script>
    dataLayer.push({
        'pageCategory': 'shop.categories.index',

        @if ($toolbarHelper->getCurrentMode() == 'grid')
            @foreach ($products as $productFlat)
                @if ($productFlat->product->type == 'configurable')
                    'ecommerce': {
                        'currencyCode': '{{ core()->getCurrentCurrency()->code }}',

                        'impressions': [{
                            'id': '{{ $productFlat->id ?? null }}',
                            'sku': '{{ $productproductFlat->sku ?? null }}'
                            'category': '{{ $slug ?? null }}',
                            'name': '{{ $productFlat->name ?? null }}',
                            'price': '{{ core()->currency($priceHelper->getMinimalPrice($productFlat->product)) ?? null }}'
                        }]
                    }
                @else
                    'ecommerce': {
                        'currencyCode': '{{ core()->getCurrentCurrency()->code }}',

                        'impressions': [{
                            'id': '{{ $productFlat->product->id ?? null }}',
                            'name': '{{ $productFlat->name ?? null }}',
                            'category': '{{ $slug ?? null }}',

                            @if ($priceHelper->haveSpecialPrice($productFlat->product))
                                'price': '{{ core()->currency($priceHelper->getSpecialPrice($productFlat->product)) ?? null }}'
                            @else
                                'price': '{{ core()->currency($productFlat->price) ?? null }}'
                            @endif
                        }]
                    }
                @endif
            @endforeach
        @else
            @foreach ($products as $productFlat)
                @if ($productFlat->product->type == 'configurable')
                    'ecommerce': {
                        'currencyCode': '{{ core()->getCurrentCurrency()->code }}',
                        'impressions': [{
                            'id': '{{ $productFlat->id ?? null }}',
                            'sku': '{{ $productFlat->sku ?? null }}'
                            'category': '{{ $category ?? null }}',
                            'name': '{{ $productFlat->name ?? null }}',
                            'price': '{{ core()->currency($priceHelper->getMinimalPrice($productFlat->product)) ?? null }}'
                        }]
                    }
                @else
                    'ecommerce': {
                        'currencyCode': '{{ core()->getCurrentCurrency()->code }}',

                        'impressions': [{
                            'id': '{{ $productFlat->id ?? null }}',
                            'name': '{{ $productFlat->name ?? null }}',
                            'category': '{{ $category ?? null }}',

                            @if ($priceHelper->haveSpecialPrice($productFlat->product))
                                'price': '{{ core()->currency($priceHelper->getSpecialPrice($productFlat->product)) ?? null }}'
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