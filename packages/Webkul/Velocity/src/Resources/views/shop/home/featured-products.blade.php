@php
    $count = core()->getConfigData('catalog.products.homepage.no_of_featured_product_homepage') ?: 10;
    $guest_review_status = core()->getConfigData('catalog.products.review.guest_review');
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp

<product-collections
    product-id="fearured-products-carousel"
    :guest-review-status="{{ (Boolean) $guest_review_status ? 'true' : 'false'}}"
    product-title="{{ __('shop::app.home.featured-products') }}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'featured-products', 'count' => $count]) }}"
    locale-direction="{{ $direction }}"
    count="{{ (int) $count }}">
</product-collections>