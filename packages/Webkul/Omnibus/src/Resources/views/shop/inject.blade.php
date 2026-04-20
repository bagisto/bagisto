@php
    $manager = app(\Webkul\Omnibus\Services\OmnibusPriceManager::class);
@endphp

{!! $manager->getOmnibusPriceHtml($product) !!}
