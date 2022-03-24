<product-collections
    product-title="{{ __('shop::app.home.featured-products') }}"
    product-route="{{ route('velocity.category.details', ['category-slug' => $category]) }}"
    locale-direction="{{ core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr' }}">
</product-collections>