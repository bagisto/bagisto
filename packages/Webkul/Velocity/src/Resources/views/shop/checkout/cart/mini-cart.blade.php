<div class="mini-cart-container pull-right">
    @php
        $minimumOrderAmount = (int) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;
    @endphp

    <mini-cart
        view-cart="{{ route('shop.checkout.cart.index') }}"
        cart-text="{{ __('shop::app.minicart.view-cart') }}"
        checkout-text="{{ __('shop::app.minicart.checkout') }}"
        checkout-url="{{ route('shop.checkout.onepage.index') }}"
        subtotal-text="{{ __('shop::app.checkout.cart.cart-subtotal') }}"
        is-minimum-order-completed="{{ (bool) Cart::checkMinimumOrder() }}"
        minimum-order-message="{{ __('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]) }}">
    </mini-cart>
</div>