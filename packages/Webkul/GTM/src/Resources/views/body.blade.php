@if (core()->getConfigData('general.gtm.values.status'))

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ core()->getConfigData('general.gtm.values.container_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    @if (\Route::current()->getName() == 'shop.products.index')
        @include('gtm::product')
    @endif

    @if (\Route::current()->getName() == 'shop.categories.index')
        @include('gtm::category')
    @endif

    @if (\Route::current()->getName() == 'shop.checkout.cart.index')
        @include('gtm::cart')
    @endif

    @if (\Route::current()->getName() == 'shop.checkout.success')
        @include('gtm::checkout-success')
    @endif

@endif