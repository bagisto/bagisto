<div class="order-summary">
    <div class="price">
        <span>{{ __('shop::app.checkout.total.order-summary') }}</span>
    </div>

    <div class="item-detail">
        <span>
            <label>{{ __('shop::app.checkout.total.sub-total') }}</label>
            <label class="right">{{ core()->currency($cart->sub_total) }}</label>
        </span>
    </div>

    @if ($cart->selected_shipping_rate)
        <div class="item-detail">
            <span>
                <label>{{ __('shop::app.checkout.total.delivery-charges') }}</label>
                <label class="right">{{ core()->currency($cart->selected_shipping_rate->price) }}</label>
            </span>
        </div>
    @endif

    <div class="horizontal-rule">
    </div>

    <div class="payble-amount">
        <span>
            <label>{{ __('shop::app.checkout.total.grand-total') }}</label>
            <label class="right">{{ core()->currency($cart->grand_total) }}</label>
        </span>
    </div>
</div>