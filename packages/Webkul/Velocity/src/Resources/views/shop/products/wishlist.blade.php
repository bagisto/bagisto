@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

{!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    @auth('customer')
        @php
            /* search wishlist on the basis of product's id so that wishlist id can be catched */
            $wishlist = $wishListHelper->getWishlistProduct($product);

            /* link making */
            $href = isset($route) ? $route : ($wishlist ? route('shop.customer.wishlist.remove', $wishlist->id) : route('shop.customer.wishlist.add', $product->id));

            /* method */
            $method = isset($route) ? 'POST' : ( $wishlist ? 'DELETE' : 'POST' );

            /* is confirmation needed */
            $isConfirm = isset($route) ? 'true' : 'false';

            /* title */
            $title = $wishlist ? __('velocity::app.shop.wishlist.remove-wishlist-text') : __('velocity::app.shop.wishlist.add-wishlist-text');
        @endphp

        <a
            class="unset wishlist-icon {{ $addWishlistClass ?? '' }} text-right"
            href="javascript:void(0);"
            title="{{ $title }}"
            onclick="submitWishlistForm(
                '{{ $href }}',
                '{{ $method }}',
                {{ $isConfirm }},
                '{{ csrf_token() }}'
            )"
        >
            <wishlist-component active="{{ $wishlist ? false : true }}"></wishlist-component>

            @if (isset($text))
                {!! $text !!}
            @endif
        </a>
    @endauth

    @guest('customer')
        <form           
            id="wishlist-{{ $product->id }}"
            action="{{ route('shop.customer.wishlist.add', $product->id) }}"
            method="POST">
            @csrf
            
            <a
                class="unset wishlist-icon {{ $addWishlistClass ?? '' }} text-right"
                href="javascript:void(0);"
                title="{{ __('velocity::app.shop.wishlist.add-wishlist-text') }}"
                onclick="document.getElementById('wishlist-{{ $product->id }}').submit();">

                <wishlist-component active="false"></wishlist-component>
            </a>
        </form>
    @endauth

{!! view_render_event('bagisto.shop.products.wishlist.after') !!}
