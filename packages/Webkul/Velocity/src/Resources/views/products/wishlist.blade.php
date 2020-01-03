@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@auth('customer')
    {!! view_render_event('bagisto.shop.products.wishlist.before') !!}

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

    {!! view_render_event('bagisto.shop.products.wishlist.after') !!}
@endauth