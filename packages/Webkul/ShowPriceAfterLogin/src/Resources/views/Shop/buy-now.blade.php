{!! view_render_event('bagisto.shop.products.buy_now.before', ['product' => $product]) !!}

@php
    $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable');

    $function = core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction');
@endphp

@if(auth()->guard('customer')->check() && $status)
    <button type="submit" data-href="{{ route('shop.product.buynow', $product->id)}}" class="btn btn-lg btn-primary buynow"  {{ $product->type != 'configurable' && !$product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
        {{ __('shop::app.products.buy-now') }}
    </button>

@elseif(! auth()->guard('customer')->check() && $status && ( $function == "hide-price-buy-cart-guest" || $function == "hide-buy-cart-guest"))


@else
    <button type="submit" data-href="{{ route('shop.product.buynow', $product->id)}}" class="btn btn-lg btn-primary buynow"  {{ $product->type != 'configurable' && !$product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
        {{ __('shop::app.products.buy-now') }}
    </button>
@endif

{!! view_render_event('bagisto.shop.products.buy_now.after', ['product' => $product]) !!}