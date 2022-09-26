@if((bool) core()->getConfigData('general.content.shop.wishlist_option'))
    <wishlist-component-with-badge
        is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
        is-text="{{ isset($isText) && $isText ? 'true' : 'false' }}"
        src="{{ route('shop.customer.wishlist.index') }}">
    </wishlist-component-with-badge>

@endif