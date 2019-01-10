@auth('customer')
    {!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    <a class="add-to-wishlist" href="{{ route('customer.wishlist.add', $product->id) }}" id="wishlist-changer">
        <span class="icon wishlist-icon"></span>
    </a>

    {!! view_render_event('bagisto.shop.products.wishlist.after') !!}
@endauth
