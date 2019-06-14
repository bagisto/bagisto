<div class="form-container" id="">
    <div class="form-header mb-30" id="">
        <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.summary') }}</span>
    </div>

    <div class="address-summary" id="">
        @if ($billingAddress = $cart->billing_address)
            <div class="billing-address" id="">
                <div class="card-title mb-20" id="">
                    <b>{{ __('shop::app.checkout.onepage.billing-address') }}</b>
                </div>

                <div class="card-content" id="">
                    <ul>
                        <li class="mb-10">
                            {{ $billingAddress->name }}
                        </li>
                        <li class="mb-10">
                            {{ $billingAddress->address1 }},<br/> {{ $billingAddress->state }}
                        </li>
                        <li class="mb-10">
                            {{ country()->name($billingAddress->country) }} {{ $billingAddress->postcode }}
                        </li>

                        <span class="horizontal-rule mb-15 mt-15"></span>

                        <li class="mb-10">
                            {{ __('shop::app.checkout.onepage.contact') }} : {{ $billingAddress->phone }}
                        </li>
                    </ul>
                </div>
            </div>
        @endif

        @if ($shippingAddress = $cart->shipping_address)
            <div class="shipping-address" id="">
                <div class="card-title mb-20" id="">
                    <b>{{ __('shop::app.checkout.onepage.shipping-address') }}</b>
                </div>

                <div class="card-content" id="">
                    <ul>
                        <li class="mb-10">
                            {{ $shippingAddress->name }}
                        </li>
                        <li class="mb-10">
                            {{ $shippingAddress->address1 }},<br/> {{ $shippingAddress->state }}
                        </li>
                        <li class="mb-10">
                            {{ country()->name($shippingAddress->country) }} {{ $shippingAddress->postcode }}
                        </li>

                        <span class="horizontal-rule mb-15 mt-15"></span>

                        <li class="mb-10">
                            {{ __('shop::app.checkout.onepage.contact') }} : {{ $shippingAddress->phone }}
                        </li>
                    </ul>
                </div>
            </div>
        @endif

    </div>

    @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

    <div class="cart-item-list mt-20" id="">
        @foreach ($cart->items as $item)

            <?php
                $product = $item->product;

                $productBaseImage = $productImageHelper->getProductBaseImage($product);
            ?>

            <div class="item mb-5" style="margin-bottom: 5px;" id="">
                <div class="item-image" id="">
                    <img src="{{ $productBaseImage['medium_image_url'] }}" />
                </div>

                <div class="item-details" id="">

                    {!! view_render_event('bagisto.shop.checkout.name.before', ['item' => $item]) !!}

                    <div class="item-title" id="">
                        {{ $product->name }}
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.name.after', ['item' => $item]) !!}
                    {!! view_render_event('bagisto.shop.checkout.price.before', ['item' => $item]) !!}

                    <div class="row" id="">
                        <span class="title" id="">
                            {{ __('shop::app.checkout.onepage.price') }}
                        </span>
                        <span class="value">
                            {{ core()->currency($item->base_price) }}
                        </span>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.price.after', ['item' => $item]) !!}
                    {!! view_render_event('bagisto.shop.checkout.quantity.before', ['item' => $item]) !!}

                    <div class="row" id="">
                        <span class="title">
                            {{ __('shop::app.checkout.onepage.quantity') }}
                        </span>
                        <span class="value">
                            {{ $item->quantity }}
                        </span>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.quantity.after', ['item' => $item]) !!}

                    @if ($product->type == 'configurable')
                        {!! view_render_event('bagisto.shop.checkout.options.after', ['item' => $item]) !!}

                        <div class="summary" id="">
                            {{ Cart::getProductAttributeOptionDetails($item->child->product)['html'] }}
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.options.after', ['item' => $item]) !!}
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="order-description mt-20" id="">
        <div class="pull-left" style="width: 60%; float: left;" id="">
            <div class="shipping" id="">
                <div class="decorator" id="">
                    <i class="icon shipping-icon"></i>
                </div>

                <div class="text" id="">
                    {{ core()->currency($cart->selected_shipping_rate->base_price) }}

                    <div class="info" id="">
                        {{ $cart->selected_shipping_rate->method_title }}
                    </div>
                </div>
            </div>

            <div class="payment" id="">
                <div class="decorator" id="">
                    <i class="icon payment-icon"></i>
                </div>

                <div class="text" id="">
                    {{ core()->getConfigData('sales.paymentmethods.' . $cart->payment->method . '.title') }}
                </div>
            </div>

        </div>

        <div class="pull-right" style="width: 40%; float: left;" id="">
            @include('shop::checkout.total.summary', ['cart' => $cart])
        </div>
    </div>
</div>