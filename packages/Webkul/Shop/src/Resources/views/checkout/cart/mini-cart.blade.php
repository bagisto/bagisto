@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

<?php $cart = cart()->getCart(); ?>

@if ($cart)
    @php
        Cart::collectTotals();
    @endphp

    <?php $items = $cart->items; ?>

    <div class="dropdown-toggle">
        <a class="cart-link" href="{{ route('shop.checkout.cart.index') }}">
            <span class="icon cart-icon"></span>
        </a>

        <span class="name">
            {{ __('shop::app.header.cart') }}
            <span class="count"> ({{ $cart->items->count() }})</span>
        </span>

        <i class="icon arrow-down-icon"></i>
    </div>

    <div class="dropdown-list" style="display: none; top: 50px; right: 0px">
        <div class="dropdown-container">
            <div class="dropdown-cart">
                <div class="dropdown-header">
                    <p class="heading">
                        {{ __('shop::app.checkout.cart.cart-subtotal') }} -

                        {!! view_render_event('bagisto.shop.checkout.cart-mini.subtotal.before', ['cart' => $cart]) !!}

                        {{ core()->currency($cart->base_sub_total) }}

                        {!! view_render_event('bagisto.shop.checkout.cart-mini.subtotal.after', ['cart' => $cart]) !!}
                    </p>
                </div>

                <div class="dropdown-content">
                    @foreach ($items as $item)

                        <div class="item">
                            <div class="item-image" >
                                <?php
                                    if ($item->type == "configurable")
                                        $images = $productImageHelper->getProductBaseImage($item->child->product);
                                    else
                                        $images = $productImageHelper->getProductBaseImage($item->product);
                                ?>
                                <img src="{{ $images['small_image_url'] }}" />
                            </div>

                            <div class="item-details">
                                {!! view_render_event('bagisto.shop.checkout.cart-mini.item.name.before', ['item' => $item]) !!}

                                <div class="item-name">{{ $item->name }}</div>

                                {!! view_render_event('bagisto.shop.checkout.cart-mini.item.name.after', ['item' => $item]) !!}


                                {!! view_render_event('bagisto.shop.checkout.cart-mini.item.options.before', ['item' => $item]) !!}

                                @if ($item->type == "configurable")
                                    <div class="item-options">
                                        {{ trim(Cart::getProductAttributeOptionDetails($item->child->product)['html']) }}
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.checkout.cart-mini.item.options.after', ['item' => $item]) !!}


                                {!! view_render_event('bagisto.shop.checkout.cart-mini.item.price.before', ['item' => $item]) !!}

                                <div class="item-price">{{ core()->currency($item->base_total) }}</div>

                                {!! view_render_event('bagisto.shop.checkout.cart-mini.item.price.after', ['item' => $item]) !!}


                                {!! view_render_event('bagisto.shop.checkout.cart-mini.item.quantity.before', ['item' => $item]) !!}

                                <div class="item-qty">Quantity - {{ $item->quantity }}</div>

                                {!! view_render_event('bagisto.shop.checkout.cart-mini.item.quantity.after', ['item' => $item]) !!}
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class="dropdown-footer">
                    <a href="{{ route('shop.checkout.cart.index') }}">{{ __('shop::app.minicart.view-cart') }}</a>

                    <a class="btn btn-primary btn-lg" style="color: white;" href="{{ route('shop.checkout.onepage.index') }}">{{ __('shop::app.minicart.checkout') }}</a>
                </div>
            </div>
        </div>
    </div>

@else

    <div class="dropdown-toggle">
        <div style="display: inline-block; cursor: pointer;">
            <span class="icon cart-icon"></span>
            <span class="name">{{ __('shop::app.minicart.cart') }}<span class="count"> ({{ __('shop::app.minicart.zero') }}) </span></span>
        </div>
    </div>
@endif