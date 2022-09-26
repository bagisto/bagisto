{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

    <div class="mx-0 no-padding">
        @if ((bool) core()->getConfigData('general.content.shop.compare_option'))
            <compare-component
                @auth('customer')
                    customer="true"
                @endif

                @guest('customer')
                    customer="false"
                @endif

                slug="{{ $product->url_key }}"
                product-id="{{ $product->id }}"
                add-tooltip="{{ __('velocity::app.customer.compare.add-tooltip') }}"
            ></compare-component>
        @endif

        @if (core()->getConfigData('general.content.shop.wishlist_option'))
            @include('shop::products.wishlist', [
                'addClass' => $addWishlistClass ?? ''
            ])
        @endif

        <div class="add-to-cart-btn pl0">
            <add-to-cart
                form="true"
                csrf-token='{{ csrf_token() }}'
                product-flat-id="{{ $product->id }}"
                product-id="{{ $product->product_id }}"
                reload-page="{{ $reloadPage ?? false }}"
                move-to-cart="{{ $moveToCart ?? false }}"
                wishlist-move-route="{{ $wishlistMoveRoute ?? false }}"
                add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                is-enable={{ ! $product->isSaleable() ? 'false' : 'true' }}
                show-cart-icon={{ empty($showCartIcon) }}
                btn-text="{{ (! isset($moveToCart) && $product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}">
            </add-to-cart>
        </div>
    </div>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}