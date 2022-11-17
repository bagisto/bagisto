@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@auth('customer')
    {!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    <form id="wishlist-{{ $product->id }}" action="{{ route('shop.customer.wishlist.add', $product->id) }}" method="POST">
        @csrf
    </form>

    <a
        @if ($wishListHelper->getWishlistProduct($product))
            class="add-to-wishlist already"
            title="{{ __('shop::app.customer.account.wishlist.remove-wishlist-text') }}"
        @else
            class="add-to-wishlist"
            title="{{ __('shop::app.customer.account.wishlist.add-wishlist-text') }}"
        @endif
        id="wishlist-changer"
        style="margin-right: 15px;"
        href="javascript:void(0);"
        onclick="document.getElementById('wishlist-{{ $product->id }}').submit();">

        <span class="icon wishlist-icon"></span>

    </a>

    {!! view_render_event('bagisto.shop.products.wishlist.after') !!}
@endauth
