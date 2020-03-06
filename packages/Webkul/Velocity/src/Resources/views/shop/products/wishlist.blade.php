@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

{!! view_render_event('bagisto.shop.products.wishlist.before') !!}
    @auth('customer')
        @php
            $isWished = $wishListHelper->getWishlistProduct($product);
        @endphp

        <a
            class="unset wishlist-icon {{ $addWishlistClass ?? '' }} text-right"
            @if (! $isWished)
                href="{{ route('customer.wishlist.add', $product->product_id) }}"
                title="{{ __('velocity::app.shop.wishlist.add-wishlist-text') }}"
            @elseif (isset($itemId) && $itemId)
                href="{{ route('customer.wishlist.remove', $itemId) }}"
                title="{{ __('velocity::app.shop.wishlist.remove-wishlist-text') }}"
            @endif>

            <wishlist-component active="{{ !$isWished }}" is-customer="true"></wishlist-component>
        </a>
    @endauth

    @guest('customer')
        <wishlist-component
            active="false"
            is-customer="false"
            product-id="{{ $product->id }}"
            product-slug="{{ $product->url_key }}"
            add-class="{{ $addWishlistClass ?? '' }}"
            added-text="{{ __('shop::app.customer.account.wishlist.add') }}"
            remove-text="{{ __('shop::app.customer.account.wishlist.remove') }}">
        </wishlist-component>
    @endauth
{!! view_render_event('bagisto.shop.products.wishlist.after') !!}