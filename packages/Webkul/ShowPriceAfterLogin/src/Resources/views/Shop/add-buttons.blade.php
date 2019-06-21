@if (Route::currentRouteName() == "shop.products.index")
    @include ('shop::products.add-to', ['product' => $product])
@else
    @if(auth()->guard('customer')->check() && core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable'))
        @if ($product->type == "configurable")
            <div class="cart-wish-wrap">
                <a href="{{ route('cart.add.configurable', $product->url_key) }}" class="btn btn-lg btn-primary addtocart">
                    {{ __('shop::app.products.add-to-cart') }}
                </a>

                @include('shop::products.wishlist')
            </div>
        @else
            <div class="cart-wish-wrap">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="product" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" value="false" name="is_configurable">
                    <button class="btn btn-lg btn-primary addtocart" {{ $product->haveSufficientQuantity(1) ? '' : 'disabled' }}>{{ __('shop::app.products.add-to-cart') }}</button>
                </form>

                @include('shop::products.wishlist')
            </div>
        @endif



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
    @elseif(! auth()->guard('customer')->check() && core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable') && core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction') == "hide-price-buy-cart-guest")
        <div class="login-to-view-price" style="width:100%;">
            <a class="btn btn-lg btn-primary addtocart" href="{{ route('customer.session.index') }}">
            {{ __('ShowPriceAfterLogin::app.products.login-to-view-price') }}
            </a>
        </div>

    @else
        @if ($product->type == "configurable")
            <div class="cart-wish-wrap">
                <a href="{{ route('cart.add.configurable', $product->url_key) }}" class="btn btn-lg btn-primary addtocart">
                    {{ __('shop::app.products.add-to-cart') }}
                </a>

                @include('shop::products.wishlist')
            </div>
        @else
            <div class="cart-wish-wrap">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="product" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" value="false" name="is_configurable">
                    <button class="btn btn-lg btn-primary addtocart" {{ $product->haveSufficientQuantity(1) ? '' : 'disabled' }}>{{ __('shop::app.products.add-to-cart') }}</button>
                </form>

                @include('shop::products.wishlist')
            </div>
        @endif

    @endif
@endif