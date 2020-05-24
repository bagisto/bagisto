@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@auth('customer')
    {!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    <a
        @if ($wishListHelper->getWishlistProduct($product))
            class="add-to-wishlist already"
        @else
            class="add-to-wishlist"
        @endif
        id="wishlist-changer"
        style="margin-right: 15px;"
        href="{{ route('customer.wishlist.add', $product->product_id) }}">
        <span class="icon wishlist-icon"></span>
    </a>

    {!! view_render_event('bagisto.shop.products.wishlist.after') !!}
@endauth
