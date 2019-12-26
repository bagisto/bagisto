@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@auth('customer')
    {!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    @php
        $isWished = $wishListHelper->getWishlistProduct($product);
    @endphp

    <a
        href="
            @if ($isWished)
                {{ route('customer.wishlist.remove', $product->product_id) }}
            @else
                {{ route('customer.wishlist.add', $product->product_id) }}
            @endif
        "
        class="unset wishlist-icon col-4 offset-4 text-right">

        <i
            class="fs24
            @if ($isWished)
                rango-heart-fill
            @else
                rango-heart
            @endif
        "></i>
    </a>

    {!! view_render_event('bagisto.shop.products.wishlist.after') !!}
@endauth