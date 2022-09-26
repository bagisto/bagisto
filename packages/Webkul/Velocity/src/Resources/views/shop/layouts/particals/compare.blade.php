@if ((bool) core()->getConfigData('general.content.shop.compare_option'))
    <compare-component-with-badge
        is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
        is-text="{{ ! empty($isText) ? 'true' : 'false' }}"
        src="{{ auth()->guard('customer')->check() ? route('velocity.customer.product.compare') : route('velocity.product.compare') }}">
    </compare-component-with-badge>
@endif