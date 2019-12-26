@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('productRatingHelper', 'Webkul\Product\Helpers\Review')

@php
    $recentlyViewedProducts = app('Webkul\Product\Repositories\ProductRepository')->getNewProducts(3)->items();
@endphp

<div class="col-3 recently-viewed">
    <div class="row mb20 pl20">
        <div class="col-12 no-padding">
            <h2 class="fs20 fw6">{{ __('velocity::app.products.recently-viewed') }}</h2>
        </div>
    </div>

    @foreach ($recentlyViewedProducts as $index => $product)

        @php
            $productBaseImage = $productImageHelper->getProductBaseImage($product);
            $productAverageRating = $productRatingHelper->getAverageRating($product);
        @endphp

        <div class="row small-card-container">
            <div class="col-4 product-image-container mr15">
                <a href="{{ route('shop.productOrCategory.index', $product->url_key) }}" class="unset">
                    <div class="product-image" style="background-image: url({{ $productBaseImage['small_image_url'] }})"></div>
                </a>
            </div>

            <div class="col-8 no-padding card-body align-vertical-top">
                <div class="no-padding">
                    <div class="fs16 text-nowrap">{{ $product->name }}</div>

                    <div class="fs18 card-current-price fw6">
                        @include ('shop::products.price', [
                            'product' => $product,
                            'showDiscount' => false,
                        ])
                    </div>

                    <star-ratings ratings="{{ intval($productAverageRating) }}"></star-ratings>
                </div>

            </div>
        </div>
    @endforeach
</div>
