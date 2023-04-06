<product-collections
    product-id="fearured-products-carousel"
    :can-guest-review="{{ (bool) $guest_review_status ? 'true' : 'false'}}"
    product-title="{{ __('shop::app.home.featured-products') }}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'featured-products', 'count' => $count]) }}"
    locale-direction="{{ $direction }}"
    count="{{ (int) $count }}">
</product-collections>