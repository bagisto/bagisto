{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}

@php
$status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable');

$function = core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction');
@endphp

<div class="add-to-buttons">
    @if ($product->type != 'configurable')
        @if ($product->totalQuantity() < 1 && $product->allow_preorder)
            <button type="submit" class="btn btn-lg btn-primary addtocart" style="width: 100%">
                {{ __('preorder::app.shop.products.preorder') }}
            </button>
        @else
            @include ('shop::products.add-to-cart', ['product' => $product])

            @include ('shop::products.buy-now')
        @endif
    @else
        @include ('shop::products.add-to-cart', ['product' => $product])

        @include ('shop::products.buy-now')

        @if(! auth()->guard('customer')->check() && $status && $function == "hide-price-buy-cart-guest")
            <div class="login-to-view-price" style="width:100%;">
                <a class="btn btn-lg btn-primary addtocart" href="{{ route('customer.session.index') }}">
                {{ __('ShowPriceAfterLogin::app.products.login-to-view-price') }}
                </a>
            </div>
        @elseif (! auth()->guard('customer')->check() && $status && ($function == "hide-buy-cart-guest"))
            <div class="login-to-view-price" style="width:100%;">
                <a class="btn btn-lg btn-primary addtocart" href="{{ route('customer.session.index') }}">
                {{ __('ShowPriceAfterLogin::app.products.login-to-buy') }}
                </a>
            </div>
        @endif
    @endif
</div>

{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}