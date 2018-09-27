<div class="order-summary">
    <h3>{{ __('shop::app.checkout.total.order-summary') }}</h3>

    <div class="item-detail">
        <label>{{ __('shop::app.checkout.total.sub-total') }}</label>
        <label class="right">{{ core()->currency($cart->sub_total) }}</label>
    </div>

    @if ($cart->selected_shipping_rate)
        <div class="item-detail">
            <label>{{ __('shop::app.checkout.total.delivery-charges') }}</label>
            <label class="right">{{ core()->currency($cart->selected_shipping_rate->price) }}</label>
        </div>
    @endif

    <div class="payble-amount">
        <label>{{ __('shop::app.checkout.total.grand-total') }}</label>
        <label class="right">{{ core()->currency($cart->grand_total) }}</label>
    </div>
</div>