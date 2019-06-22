{!! view_render_event('bagisto.shop.products.buy_now.before', ['product' => $product]) !!}

@php
    $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable');

    $function = $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction');
@endphp

@if ($status && ($function == 'hide-buy-cart-guest' || $function == 'hide-price-buy-cart-guest') && ! auth()->guard('customer')->check())
@else
    <button type="submit" data-href="{{ route('shop.product.buynow', $product->product_id)}}" class="btn btn-lg btn-primary buynow" {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
        {{ __('shop::app.products.buy-now') }}
    </button>
@endif
{!! view_render_event('bagisto.shop.products.buy_now.after', ['product' => $product]) !!}