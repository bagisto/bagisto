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

        @if($wishlist)
            <form
                class="d-none"
                id="wishlist-{{ $wishlist->id }}"
                action="{{ $href }}"
                method="POST">
                @method('DELETE')

                @csrf
            </form>
        @else
            <form
                class="d-none"
                id="wishlist-{{ $product->product_id }}"
                action="{{ $href }}"
                method="POST">
                @csrf
            </form>
        @endif

        <a
            class="unset wishlist-icon {{ $addWishlistClass ?? '' }} text-right"
            href="javascript:void(0);"
            title="{{ $title }}"
            onclick="document.getElementById('wishlist-{{ $wishlist ? $wishlist->id : $product->product_id }}').submit();">

            <wishlist-component active="{{ $wishlist ? false : true }}"></wishlist-component>

            @if (isset($text))
                {!! $text !!}
            @endif
        </a>
    @endauth

    @guest('customer')
        <form
            class="d-none"
            id="wishlist-{{ $product->product_id }}"
            action="{{ route('customer.wishlist.add', $product->product_id) }}"
            method="POST">
            @csrf
        </form>

        <a
            class="unset wishlist-icon {{ $addWishlistClass ?? '' }} text-right"
            href="javascript:void(0);"
            title="{{ __('velocity::app.shop.wishlist.add-wishlist-text') }}"
            onclick="document.getElementById('wishlist-{{ $product->product_id }}').submit();">

            <wishlist-component active="false"></wishlist-component>

        </a>
    @endauth

{!! view_render_event('bagisto.shop.products.wishlist.after') !!}