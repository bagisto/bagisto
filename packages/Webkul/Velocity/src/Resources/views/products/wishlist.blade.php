@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@auth('customer')
    {!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    @php
        $isWished = $wishListHelper->getWishlistProduct($product);
    @endphp

    <a
        class="unset wishlist-icon col-4 offset-4 text-right"
        href="{{ route('customer.wishlist.' . ($isWished ? 'remove' : 'add'), $product->product_id) }}">

        <i class="fs24 {{ $isWished ? 'rango-heart-fill' : 'rango-heart'}}"></i>
    </a>

    {!! view_render_event('bagisto.shop.products.wishlist.after') !!}
@endauth