@php
    $provider = app(\Webkul\Omnibus\Services\OmnibusPriceProviderResolver::class)->resolve($product);
@endphp

{!! $provider->getOmnibusPriceHtml($product) !!}
