{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

@if(auth()->guard('customer')->check() && core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable'))
    <button type="submit" class="btn btn-lg btn-primary addtocart" {{ $product->type != 'configurable' && !$product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
        {{ __('shop::app.products.add-to-cart') }}
    </button>

    @elseif(
        !auth()->guard('customer')->check()
        && core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable')
        && (
            core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction') == "hide-price-buy-cart-guest"
            || core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction') == "hide-buy-cart-guest")
        )



@else
    <button type="submit" class="btn btn-lg btn-primary addtocart" {{ $product->type != 'configurable' && !$product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
        {{ __('shop::app.products.add-to-cart') }}
    </button>

@endif
{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}