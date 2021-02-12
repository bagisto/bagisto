@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

{!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    @auth('customer')
        @php
            /* search wishlist on the basis of product's id so that wishlist id can be catched */
            $wishlist = $wishListHelper->getWishlistProduct($product);

            /* link making */
            $href = isset($route) ? $route : ($wishlist ? route('customer.wishlist.remove', $wishlist->id) : route('customer.wishlist.add', $product->product_id));

            /* title */
            $title = $wishlist ? __('velocity::app.shop.wishlist.remove-wishlist-text') : __('velocity::app.shop.wishlist.add-wishlist-text');
        @endphp

        <a
            class="unset wishlist-icon {{ $addWishlistClass ?? '' }} text-right"
            href="{{ $href }}"
            title="{{ $title }}">

            <wishlist-component active="{{ $wishlist ? false : true }}"></wishlist-component>

            @if (isset($text))
                {!! $text !!}
            @endif
        </a>
    @endauth

    @guest('customer')
        <a
            class="unset wishlist-icon {{ $addWishlistClass ?? '' }} text-right"
            href="{{ route('customer.wishlist.add', $product->product_id) }}"
            title="{{ __('velocity::app.shop.wishlist.add-wishlist-text') }}">
            <wishlist-component active="false"></wishlist-component>
        </a>
    @endauth

{!! view_render_event('bagisto.shop.products.wishlist.after') !!}