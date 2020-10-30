@php
    $count = core()->getConfigData('catalog.products.homepage.no_of_featured_product_homepage');
    $count = $count ? $count : 10;
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp

<featured-products
    card-title="{{ __('shop::app.home.featured-products') }}"
    locale-direction="{{ $direction }}"
    count="{{ $count }}">
</featured-products>
