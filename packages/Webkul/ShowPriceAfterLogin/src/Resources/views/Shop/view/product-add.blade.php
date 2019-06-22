{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}

<div class="add-to-buttons">
    @include ('shop::products.add-to-cart', ['product' => $product])

    @include ('shop::products.buy-now')

    @if(! auth()->guard('customer')->check() && core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable') && core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction') == "hide-price-buy-cart-guest")
        <div class="login-to-view-price" style="width:100%;">
            <a class="btn btn-lg btn-primary addtocart" href="{{ route('customer.session.index') }}">
            {{ __('ShowPriceAfterLogin::app.products.login-to-view-price') }}
            </a>
        </div>
    @elseif(
    !auth()->guard('customer')->check()
    && core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable')
    && (
        core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction') == "hide-buy-cart-guest")
    )
        <div class="login-to-view-price" style="width:100%;">
            <a class="btn btn-lg btn-primary addtocart" href="{{ route('customer.session.index') }}">
            {{ __('ShowPriceAfterLogin::app.products.login-to-buy') }}
            </a>
        </div>
    @endif
</div>

{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}