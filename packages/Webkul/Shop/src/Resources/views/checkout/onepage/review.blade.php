<div class="form-container">
    <div class="form-header">
        <h1>{{ __('shop::app.checkout.onepage.summary') }}</h1>
    </div>

    <div class="address">

        <div class="address-card shipping-address left">
            <div class="card-title">
                <span>Shipping address</span>
            </div>

            <div class="card-content">
                John Doe</br>
                25 , Washington</br>
                USA 5751434</br>
                
                <span class="horizontal-rule"></span>

                Contact : 9876543210 
            </div>
        </div>

        <div class="address-card billing-addres right">
            <div class="card-title">
                <span>Billing address</span>
            </div>

            <div class="card-content">
                John Doe</br>
                25 , Washington</br>
                USA 5751434</br>
                
                <span class="horizontal-rule"></span>

                Contact : 9876543210 
            </div>
        </div>

    </div>

    @inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

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

                    <div class="summary" >
                        Color : Gray, Size : S
                    </div>
                </div>

            </div>
        @endforeach

    </div>

    <div class="order-description">

        <div class="pull-left">
            
            <div class="shipping">
                <div class="decorator">
                    <i class="icon shipping-icon"></i>
                </div>

                <div class="text">
                    $ 25.00

                    <div class="info">
                        FedEx Shipping
                    </div>
                </div>
            </div>

            <div class="payment">
                <div class="decorator">
                    <i class="icon payment-icon"></i>
                </div>

                <div class="text">
                    Net banking 
                </div>
            </div>

        </div>

        <div class="pull-right">

            @include('shop::checkout.total.summary', ['cart' => $cart])

        </div>

    </div>

</div>