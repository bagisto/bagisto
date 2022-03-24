@php
    $count = core()->getConfigData('catalog.products.homepage.no_of_new_product_homepage');
    $count = $count ? $count : 10;
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp

{!! view_render_event('bagisto.shop.new-products.before') !!}

<product-collections
    count="{{ (int) $count }}"
    product-id="new-products-carousel"
    product-title="{{ __('shop::app.home.new-products') }}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'new-products', 'count' => $count]) }}"
    locale-direction="{{ $direction }}"
    show-recently-viewed="{{ (Boolean) $showRecentlyViewed ? 'true' : 'false' }}"
    recently-viewed-title="{{ __('velocity::app.products.recently-viewed') }}"
    no-data-text="{{ __('velocity::app.products.not-available') }}">
</product-collections>

{!! view_render_event('bagisto.shop.new-products.after') !!}
