{!! view_render_event('bagisto.shop.products.list.card.before', ['product' => $product]) !!}

@php
    $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.enableordisable');

    $function = $status = core()->getConfigData('ShowPriceAfterLogin.settings.settings.selectfunction');
@endphp

<div class="product-card">
    @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

    <?php $productBaseImage = $productImageHelper->getProductBaseImage($product); ?>

    @if ($product->new)
        <div class="sticker new">
            {{ __('shop::app.products.new') }}
        </div>
    @endif

    <div class="product-image">
        <a href="{{ route('shop.products.index', $product->url_key) }}" title="{{ $product->name }}">
            <img src="{{ $productBaseImage['medium_image_url'] }}" onerror="this.src='{{ asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.png') }}'"/>
        </a>
    </div>

    <div class="product-information">

        <div class="product-name">
            <a href="{{ url()->to('/').'/products/' . $product->url_key }}" title="{{ $product->name }}">
                <span>
                    {{ $product->name }}
                </span>
            </a>
        </div>

        @include ('shop::products.price', ['product' => $product])

        @if ($status && ($function == 'hide-buy-cart-guest' || $function == 'hide-price-buy-cart-guest')  && ! auth()->guard('customer')->check())
            <div class="login-to-view-price">
                <a class="btn btn-lg btn-primary addtocart" href="{{ route('customer.session.index') }}" style="width:100%;">
                    @if ($function == 'hide-buy-cart-guest')
                        {{ __('ShowPriceAfterLogin::app.products.login-to-buy') }}
                    @else
                        {{ __('ShowPriceAfterLogin::app.products.login-to-view-price') }}
                    @endif

                </a>
            </div>
        @else
            @include('shop::products.add-buttons', ['product' => $product])
        @endif
    </div>

</div>

{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $product]) !!}