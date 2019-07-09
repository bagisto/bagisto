{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}
@php
    $status = core()->getConfigData('showpriceafterlogin.settings.settings.enableordisable');

    $function = core()->getConfigData('showpriceafterlogin.settings.settings.selectfunction');
@endphp

@if (($status && ! auth()->guard('customer')->check()) && ($function == 'hide-buy-cart-guest' || $function == 'hide-price-buy-cart-guest'))
    <div class="login-to-view-price">
        <a class="btn btn-lg btn-primary addtocart" href="{{ route('customer.session.index') }}" style="width:100%;">
            {{ __('showpriceafterlogin::app.products.login-to-view-price') }}
        </a>
    </div>
@else
    <button type="submit" class="btn btn-lg btn-primary addtocart" {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
        {{ __('shop::app.products.add-to-cart') }}
    </button>
@endif
{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}