@php
    $activeShippings = [];
    $activePayments = [];

    $shippings = core()->getConfigData('sales.carriers');
    $payments = core()->getConfigData('sales.paymentmethods');

    foreach($shippings as $ship) {
        if ($ship['active'] == "true") {
            array_push($activeShippings, $ship['title']);
        }
    }

    foreach($payments as $payment) {
        if ($payment['active'] == "true") {
            array_push($activePayments, $payment['title']);
        }
    }
@endphp

<div class="col-lg-4 col-md-12 col-sm-12 footer-rt-content">
    <div class="row">
        <div class="mb5 col-12">
            <h3>{{ __('velocity::app.home.payment-methods') }}</h3>
        </div>

        <div class="payment-methods col-12">
            @foreach($activePayments as $paymentMethod)
                <div class="method-sticker">
                    {{ $paymentMethod}}
                </div>
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="mb5 col-12">
            <h3>{{ __('velocity::app.home.shipping-methods') }}</h3>
        </div>

        <div class="shipping-methods col-12">
            @foreach($activeShippings as $shippingMethod)
                <div class="method-sticker">
                    {{ $shippingMethod}}
                </div>
            @endforeach
        </div>
    </div>
</div>