@php
    $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;
@endphp

@if ($showCompare)

    <compare-component-with-badge
        is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
        is-text="{{ isset($isText) && $isText ? 'true' : 'false' }}"
        src="{{ auth()->guard('customer')->check() ? route('velocity.customer.product.compare') : route('velocity.product.compare') }}">
    </compare-component-with-badge>

@endif