@auth('customer')
    <a class="add-to-wishlist" href="{{ route('customer.wishlist.add', $product->id) }}" id="wishlist-changer">
        <span class="icon wishlist-icon"></span>
    </a>
@endauth
