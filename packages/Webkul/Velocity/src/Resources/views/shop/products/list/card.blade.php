@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
{{--  @include('shop::UI.product-quick-view')  --}}

@php
    if (isset($checkmode) && $checkmode && $toolbarHelper->getCurrentMode() == "list") {
        $list = true;
    }
@endphp

@php
    $productBaseImage = $productImageHelper->getProductBaseImage($product);
    $totalReviews = $reviewHelper->getTotalReviews($product);
    $avgRatings = ceil($reviewHelper->getAverageRating($product));
@endphp

{!! view_render_event('bagisto.shop.products.list.card.before', ['product' => $product]) !!}
    @if (isset($list) && $list)
        <div class="col-12 lg-card-container list-card product-card row">
            <div class="product-image">
                <a
                    title="{{ $product->name }}"
                    href="{{ route('shop.productOrCategory.index', $product->url_key) }}">

                    <img src="{{ $productBaseImage['medium_image_url'] }}" />
                </a>
            </div>

            <div class="product-information">
                <div>
                    <div class="product-name">
                        <a
                            href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
                            title="{{ $product->name }}" class="unset">

                            <span class="fs16">{{ $product->name }}</span>
                        </a>
                    </div>

                    <div class="product-price">
                        @include ('shop::products.price', ['product' => $product])
                    </div>

                    <div class="product-rating">
                        <star-ratings ratings="{{ $avgRatings }}"></star-ratings>
                        <span>{{ $totalReviews }} Ratings</span>
                    </div>

                    <div class="cart-wish-wrap mt5">
                        @include ('shop::products.add-to-cart', [
                            'product' => $product,
                            'addToCartBtnClass' => 'medium-padding'
                        ])
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card grid-card product-card-new">
            <a
                href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
                title="{{ $product->name }}"
                class="product-image-container">

                <img
					loading="lazy"
                    class="card-img-top"
                    src="{{ $productBaseImage['medium_image_url'] }}"
                    alt="{{ $product->name }}">

                 {{-- <quick-view-btn details="{{ $product }}"></quick-view-btn> --}}
            </a>

            <div class="card-body">
                <div class="product-name col-12 no-padding">
                    <a
                        href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
                        title="{{ $product->name }}"
                        class="unset">

                        <span class="fs16">{{ $product->name }}</span>
                    </a>
                </div>

                <div class="product-price fs16">
                    @include ('shop::products.price', ['product' => $product])
                </div>

                @if ($totalReviews)
                    <div class="product-rating col-12 no-padding">
                        <star-ratings ratings="{{ $avgRatings }}"></star-ratings>
                        <span class="align-top">{{ $totalReviews }} Ratings</span>
                    </div>
                @else
                    <div class="product-rating col-12 no-padding">
                        <span class="fs14">{{ __('velocity::app.products.be-first-review') }}</span>
                    </div>
                @endif

                <div class="cart-wish-wrap row col-12 no-padding ml0">
                    @include ('shop::products.add-to-cart', [
                        'product' => $product,
                        'addWishlistClass' => 'col-lg-4 col-md-4 col-sm-12 offset-lg-4 pr0',
                        'addToCartBtnClass' => $addToCartBtnClass ?? '',
                        'btnText' => $btnText ?? null
                    ])
                </div>
            </div>
        </div>
    @endif

{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $product]) !!}
