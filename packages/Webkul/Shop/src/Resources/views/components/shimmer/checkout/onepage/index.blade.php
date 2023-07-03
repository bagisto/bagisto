<div class="grid grid-cols-[1fr_auto] gap-[30px] max-lg:grid-cols-[1fr]">
    <div>
        {{-- Billing Address --}}
        <x-shop::shimmer.checkout.onepage.address></x-shop::shimmer.checkout.onepage.address>

        {{-- Shipping method --}}
        <x-shop::shimmer.checkout.onepage.shipping-method></x-shop::shimmer.checkout.onepage.shipping-method>

        {{-- Payment method --}}
        <x-shop::shimmer.checkout.onepage.payment-method></x-shop::shimmer.checkout.onepage.payment-method>
    </div>

    <x-shop::shimmer.checkout.onepage.cart-summary></x-shop::shimmer.checkout.onepage.cart-summary>
</div>
