@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

{!! view_render_event('bagisto.shop.products.wishlist.before') !!}
    @auth('customer')
        @php
            $isWished = $wishListHelper->getWishlistProduct($product);
        @endphp

        <a
            class="unset wishlist-icon col-4 offset-4 text-right"
            @if (! $isWished)
                href="{{ route('customer.wishlist.add', $product->product_id) }}"
            @elseif (isset($itemId) && $itemId)
                href="{{ route('customer.wishlist.remove', $itemId) }}"
            @endif
            >

            <i class="fs24 {{ $isWished ? 'rango-heart-fill' : 'rango-heart'}}"></i>
        </a>
    @endauth

    @guest('customer')
        <a
            href="{{ route('customer.session.index') }}"
            class="unset wishlist-icon col-4 offset-4 text-right">
            <i class="fs24 rango-heart"></i>
        </a>
    @endauth
{!! view_render_event('bagisto.shop.products.wishlist.after') !!}
