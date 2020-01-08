@php
    $activeShippings = [];
    $activePayments = [];

    $shippings = core()->getConfigData('sales.carriers');
    $payments = core()->getConfigData('sales.paymentmethods');

    foreach($shippings as $ship)
    {
        if ($ship['active'] == "true") {
            array_push($activeShippings, $ship['title']);
        }
    }

    foreach($payments as $payment)
    {
        if ($payment['active'] == "true") {
            array_push($activePayments, $payment['title']);
        }
    }
@endphp

<div class="col-4 footer-rt-content">

    <div class="row">
        <div class="mb5">
            <h3>{{ __('velocity::app.home.payment-methods') }}</h3>
        </div>

        <div class="payment-methods">
            @foreach($activePayments as $paymentMethod)
                <div class="method-sticker">
                    {{ $paymentMethod}}
                </div>
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="mb5">
            <h3>{{ __('velocity::app.home.payment-methods') }}</h3>
        </div>

        <div class="shipping-methods">
            @foreach($activeShippings as $shippingMethod)
                <div class="method-sticker">
                    {{ $shippingMethod}}
                </div>
            @endforeach
        </div>
    </div>

</div>