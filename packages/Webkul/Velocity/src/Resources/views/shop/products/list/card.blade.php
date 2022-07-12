@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@php
    $list = $toolbarHelper->getCurrentMode() == 'list'
        ? true
        : false;

    $productBaseImage = productimage()->getProductBaseImage($product);

    $totalReviews = $reviewHelper->getTotalReviews($product);

    $avgRatings = ceil($reviewHelper->getAverageRating($product));
@endphp

{!! view_render_event('bagisto.shop.products.list.card.before', ['product' => $product]) !!}
    @if (
        isset($list)
        && $list
    )
        <div class="col-12 lg-card-container list-card product-card row">
            <div class="product-image">
                <a
                    title="{{ $product->name }}"
                    href="{{ route('shop.productOrCategory.index', $product->url_key) }}">
                    <img
                        src="{{ $productBaseImage['medium_image_url'] }}"
                        :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" alt="" />

                    <div class="quick-view-in-list">
                        <product-quick-view-btn :quick-view-details="{{ json_encode($velocityHelper->formatProduct($product)) }}"></product-quick-view-btn>
                    </div>
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

                        @if (
                            isset($additionalAttributes)
                            && $additionalAttributes
                        )
                            @if (isset($item->additional['attributes']))
                                <div class="item-options">

                                    @foreach ($item->additional['attributes'] as $attribute)
                                        <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                    @endforeach

                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="product-price">
                        @include ('shop::products.price', ['product' => $product])
                    </div>

                    @if( $totalReviews )
                        <div class="product-rating">
                            <star-ratings ratings="{{ $avgRatings }}"></star-ratings>

                            <span>{{ $totalReviews }} Ratings</span>
                        </div>
                    @endif

                    <div class="cart-wish-wrap mt5">
                        @include ('shop::products.add-to-cart', [
                            'addWishlistClass'  => 'pl10',
                            'product'           => $product,
                            'addToCartBtnClass' => 'medium-padding',
                            'showCompare'       => core()->getConfigData('general.content.shop.compare_option') == "1"
                                                    ? true : false,
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
                class="{{ $cardClass ?? 'product-image-container' }}">

                <img
                    loading="lazy"
                    class="card-img-top"
                    alt="{{ $product->name }}"
                    src="{{ $productBaseImage['large_image_url'] }}"
                    :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />

                    <product-quick-view-btn :quick-view-details="{{ json_encode($velocityHelper->formatProduct($product)) }}"></product-quick-view-btn>
            </a>

            @if (! $product->getTypeInstance()->haveSpecialPrice() && $product->new)
                <div class="sticker new">
                    {{ __('shop::app.products.new') }}
                </div>
            @endif

            <div class="card-body">
                <div class="product-name col-12 no-padding">
                    <a
                        href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
                        title="{{ $product->name }}"
                        class="unset">

                        <span class="fs16">{{ $product->name }}</span>

                        @if (
                            isset($additionalAttributes)
                            && $additionalAttributes
                        )
                            @if (isset($item->additional['attributes']))
                                <div class="item-options">

                                    @foreach ($item->additional['attributes'] as $attribute)
                                        <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                    @endforeach

                                </div>
                            @endif
                        @endif
                    </a>
                </div>

                <div class="product-price fs16">
                    @include ('shop::products.price', ['product' => $product])
                </div>

                @if ($totalReviews)
                    <div class="product-rating col-12 no-padding">
                        <star-ratings ratings="{{ $avgRatings }}"></star-ratings>
                        <span class="align-top">
                            {{ __('velocity::app.products.ratings', ['totalRatings' => $totalReviews ]) }}
                        </span>
                    </div>
                @else
                    <div class="product-rating col-12 no-padding">
                        <span class="fs14">{{ __('velocity::app.products.be-first-review') }}</span>
                    </div>
                @endif

                <div class="cart-wish-wrap no-padding ml0">
                    @include ('shop::products.add-to-cart', [
                        'product'           => $product,
                        'btnText'           => $btnText ?? null,
                        'moveToCart'        => $moveToCart ?? null,
                        'wishlistMoveRoute' => $wishlistMoveRoute ?? null,
                        'reloadPage'        => $reloadPage ?? null,
                        'addToCartForm'     => $addToCartForm ?? false,
                        'addToCartBtnClass' => $addToCartBtnClass ?? '',
                        'showCompare'       => core()->getConfigData('general.content.shop.compare_option') == "1"
                                                ? true : false,
                    ])
                </div>
            </div>
        </div>
    @endif

{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $product]) !!}
