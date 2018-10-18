@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

<?php $cart = cart()->getCart(); ?>

@if($cart)
    <?php $items = $cart->items; ?>

    <div class="dropdown-toggle">
        <div style="display: inline-block; cursor: pointer;">
            <span class="name">
                Cart
                <span class="count"> ({{ intval($cart->items_qty) }})</span>
            </span>
        </div>

        <i class="icon arrow-down-icon active"></i>
    </div>

    <div class="dropdown-list" style="display: none; top: 50px; right: 0px">
        <div class="dropdown-container">
            <div class="dropdown-cart">
                <div class="dropdown-header">
                    <p class="heading">
                        {{ __('shop::app.checkout.cart.cart-subtotal') }} - 
                        {{ core()->currency($cart->sub_total) }}
                    </p>
                </div>

                <div class="dropdown-content">
                    @foreach($items as $item)
                        @if($item->type == "configurable")

                            <div class="item">
                                <div class="item-image" >
                                    @php
                                        $images = $productImageHelper->getProductBaseImage($item->child->product);
                                    @endphp
                                    <img src="{{ $images['small_image_url'] }}" />
                                </div>

                                <div class="item-details">

                                    <div class="item-name">{{ $item->child->name }}</div>

                                    <div class="item-price">{{ core()->currency($item->total) }}</div>

                                    <div class="item-qty">Quantity - {{ $item->quantity }}</div>
                                </div>
                            </div>

                        @else

                            <div class="item">
                                <div class="item-image" >
                                    @php
                                        $images = $productImageHelper->getProductBaseImage($item->product);
                                    @endphp
                                    <img src="{{ $images['small_image_url'] }}" />
                                </div>

                                <div class="item-details">

                                    <div class="item-name">{{ $item->name }}</div>

                                    <div class="item-price">{{ $item->total }}</div>

                                    <div class="item-qty">Quantity - {{ $item->quantity }}</div>
                                </div>
                            </div>

                        @endif
                    @endforeach
                </div>

                <div class="dropdown-footer">
                    <a href="{{ route('shop.checkout.cart.index') }}">View Shopping Cart</a>

                    <a class="btn btn-primary btn-lg" style="color: white;" href="{{ route('shop.checkout.onepage.index') }}">CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>

@else

    <div class="dropdown-toggle">
        <div style="display: inline-block; cursor: pointer;">

            <span class="name">Cart<span class="count"> (0) </span></span>
        </div>
    </div>
@endif