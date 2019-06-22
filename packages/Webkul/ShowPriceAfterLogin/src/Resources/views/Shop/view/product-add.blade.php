{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}
@php
    $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable');
    $function = core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction');
@endphp

<div class="add-to-buttons">
    @include ('shop::products.add-to-cart', ['product' => $product])

    @include ('shop::products.buy-now')

    @if(! auth()->guard('customer')->check() && $status)
        <div class="login-to-view-price" style="width:100%;">
            <a class="btn btn-lg btn-primary addtocart" href="{{ route('customer.session.index') }}">
                @if ($function == "hide-price-buy-cart-guest")
                    {{ __('ShowPriceAfterLogin::app.products.login-to-buy') }}
                @elseif ($function == "hide-buy-cart-guest")
                    {{ __('ShowPriceAfterLogin::app.products.login-to-view-price') }}
                @endif
            </a>
        </div>
</div>

{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}