{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}
@php
    $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable');

    $function = $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction');
@endphp

@if ($status && ($function == 'hide-buy-cart-guest' || $function == 'hide-price-buy-cart-guest'))
@else
    <button type="submit" class="btn btn-lg btn-primary addtocart" {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
        {{ __('shop::app.products.add-to-cart') }}
    </button>
@endif
{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}