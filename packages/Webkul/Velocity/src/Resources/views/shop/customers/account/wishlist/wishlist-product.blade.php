<div class="col-12 lg-card-container list-card product-card row">
    <div class="product-image">
        @php
            $image = $item->product->getTypeInstance()->getBaseImage($item);
        @endphp

        <a
            title="{{ $item->product->name }}"
            href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}">

            <img
                src="{{ $image['medium_image_url'] }}"
                :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" alt="" />

                <div class="quick-view-in-list">
            </div>
        </a>
    </div>

    <div class="product-information">
        <div class="p-2">
            <div class="product-name">
                <a
                    href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}"
                    title="{{ $item->product->name }}" class="unset">

                    <span class="fs16">{{ $item->product->name }}</span>


                    @if (isset($item->additional['attributes']))
                        <div class="item-options">
                            @foreach ($item->additional['attributes'] as $attribute)
                                <b>{{ $attribute['attribute_name'] }} : </b> {{ $attribute['option_label'] }}
                                </br>
                            @endforeach
                        </div>
                    @endif
                </a>

                @if (isset($item->product->additional['attributes']))
                    <div class="item-options">
                        @foreach ($item->product->additional['attributes'] as $attribute)
                            <b>{{ $attribute['attribute_name'] }} : </b> {{ $attribute['option_label'] }}
                            </br>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="product-price">
                @include ('shop::products.price', ['product' => $item->product])
            </div>

            <div class="cart-wish-wrap mt5">
                @if ($visibility ?? false)
                    <div class="mb-2">
                        <span class="fs16">
                            {{ __('shop::app.customer.account.wishlist.visibility') }} :

                            <span class="badge {{ $item->shared ? 'badge-success' : 'badge-danger' }}">
                                {{ $item->shared ? __('shop::app.customer.account.wishlist.public') : __('shop::app.customer.account.wishlist.private') }}
                            </span>
                        </span>
                    </div>
                @endif

                <div>
                    @include('shop::products.add-to-cart', [
                        'reloadPage'        => true,
                        'addWishlistClass'  => 'pl10',
                        'product'           => $item->product,
                        'addToCartBtnClass' => 'medium-padding',
                        'showCompare'       => core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>