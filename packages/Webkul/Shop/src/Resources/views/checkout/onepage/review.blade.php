<div class="form-container">
    <div class="form-header">
        <h1>{{ __('shop::app.checkout.onepage.summary') }}</h1>
    </div>

    <div class="address">

        @if ($billingAddress = $cart->billing_address)
            <div class="address-card billing-address">
                <div class="card-title">
                    <span>{{ __('shop::app.checkout.onepage.billing-address') }}</span>
                </div>

                <div class="card-content">
                    {{ $billingAddress->name }}</br>
                    {{ $billingAddress->address1 }}, {{ $billingAddress->address2 ? $billingAddress->address2 . ',' : '' }} {{ $billingAddress->state }}</br>
                    {{ country()->name($billingAddress->country) }} {{ $billingAddress->postcode }}</br>
                    
                    <span class="horizontal-rule"></span>

                    {{ __('shop::app.checkout.onepage.contact') }} : {{ $billingAddress->phone }} 
                </div>
            </div>
        @endif

        @if ($shippingAddress = $cart->shipping_address)
            <div class="address-card shipping-address">
                <div class="card-title">
                    <span>{{ __('shop::app.checkout.onepage.shipping-address') }}</span>
                </div>

                <div class="card-content">
                    {{ $shippingAddress->name }}</br>
                    {{ $shippingAddress->address1 }}, {{ $shippingAddress->address2 ? $shippingAddress->address2 . ',' : '' }} , {{ $shippingAddress->state }}</br>
                    {{ country()->name($shippingAddress->country) }} {{ $shippingAddress->postcode }}</br>
                    
                    <span class="horizontal-rule"></span>

                    {{ __('shop::app.checkout.onepage.contact') }} : {{ $shippingAddress->phone }} 
                </div>
            </div>
        @endif

    </div>

    @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

    <div class="cart-item-list">
        @foreach($cart->items as $item)

            <?php 
                $product = $item->product; 

                $productBaseImage = $productImageHelper->getProductBaseImage($product);
            ?>

            <div class="item">
                <div style="margin-right: 15px;">
                    <img class="item-image" src="{{ $productBaseImage['medium_image_url'] }}" />
                </div>

                <div class="item-details">

                    <div class="item-title">
                        {{ $product->name }}
                    </div>

                    <div class="row">
                        <span class="title">
                            {{ __('shop::app.checkout.onepage.price') }}
                        </span>
                        <span class="value">
                            {{ core()->currency($item->base_price) }}
                        </span>
                    </div>

                    <div class="row">
                        <span class="title">
                            {{ __('shop::app.checkout.onepage.quantity') }}
                        </span>
                        <span class="value">
                            {{ $item->quantity }}
                        </span>
                    </div>

                    @if ($product->type == 'configurable')
                        
                        <div class="summary" >

                            {{ Cart::getProductAttributeOptionDetails($item->child->product)['html'] }}
                            
                        </div>
                    @endif
                </div>

            </div>
        @endforeach

    </div>

    <div class="order-description">

        <div class="pull-left" style="width: 50%;float: left;">
            
            <div class="shipping">
                <div class="decorator">
                    <i class="icon shipping-icon"></i>
                </div>

                <div class="text">
                    {{ core()->currency($cart->selected_shipping_rate->base_price) }}

                    <div class="info">
                        {{ $cart->selected_shipping_rate->method_title }}
                    </div>
                </div>
            </div>

            <div class="payment">
                <div class="decorator">
                    <i class="icon payment-icon"></i>
                </div>

                <div class="text">
                    {{ core()->getConfigData('paymentmethods.' . $cart->payment->method . '.title') }}
                </div>
            </div>

        </div>

        <div class="pull-right" style="width: 50%;float: left;">

            @include('shop::checkout.total.summary', ['cart' => $cart])

        </div>

    </div>

</div>