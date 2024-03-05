<div class="grid grid-cols-[1fr_auto] gap-8 max-lg:grid-cols-[1fr]">
    <div>
        <!-- Billing Address Shimmer -->
        <x-shop::shimmer.checkout.onepage.address />

        <!-- Shipping Method Shimmer -->
        <x-shop::shimmer.checkout.onepage.shipping-method />

        <!-- Payment Method Shimmer -->
        <x-shop::shimmer.checkout.onepage.payment-method />
    </div>

    <x-shop::shimmer.checkout.onepage.cart-summary />
</div>
