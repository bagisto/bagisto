@php
    /**
     * Product model.
     *
     * @var \Webkul\Product\Models\Product
     */
    $product = $item->product;
@endphp

<div class="col-12 lg-card-container list-card product-card row">
    <div class="product-image">
        <a
            title="{{ $product->name }}"
            href="{{ route('shop.productOrCategory.index', $product->url_key) }}">

            <img
                src="{{ productimage()->getProductBaseImage($product)['medium_image_url'] }}"
                :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" alt="" />

                <div class="quick-view-in-list">
            </div>
        </a>
    </div>

    <div class="product-information">
        <div class="p-2">
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

            <div class="cart-wish-wrap mt5">
                <div class="mb-2">
                    <span class="fs16">
                        {{ __('shop::app.customer.account.wishlist.visibility') }} :

                        <span class="badge {{ $item->shared ? 'badge-success' : 'badge-danger' }}">
                            {{ $item->shared ? __('shop::app.customer.account.wishlist.public') : __('shop::app.customer.account.wishlist.private') }}
                        </span>
                    </span>
                </div>

                <div>
                    @include('shop::products.add-to-cart', [
                        'addWishlistClass'  => 'pl10',
                        'product'           => $product,
                        'addToCartBtnClass' => 'medium-padding',
                        'showCompare'       => core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>