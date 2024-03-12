<div class="grid grid-cols-[1fr_auto] gap-8 max-lg:grid-cols-[1fr]">
    <div>
        <!-- Billing Address Shimmer -->
        <div class="mt-8 mb-7">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h2 class="shimmer w-[180px] h-[32px]"></h2>
                
                <span class="shimmer w-6 h-6"></span>
            </div>

            <x-shop::shimmer.checkout.onepage.address />
        </div>

        <!-- Shipping Method Shimmer -->
        <x-shop::shimmer.checkout.onepage.shipping-method />

        <!-- Payment Method Shimmer -->
        <x-shop::shimmer.checkout.onepage.payment-method />
    </div>

    <x-shop::shimmer.checkout.onepage.cart-summary />
</div>
