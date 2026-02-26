<div class="grid grid-cols-[1fr_auto] gap-8 max-lg:grid-cols-[1fr] max-md:gap-5">
    <div class="max-sm:hidden">
        <!-- Billing Address Shimmer -->
        <div class="mb-7 mt-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="shimmer h-8 w-[180px]"></h2>
                
                <span class="shimmer h-6 w-6"></span>
            </div>

            <x-shop::shimmer.checkout.onepage.address />
        </div>

        <!-- Shipping Method Shimmer -->
        <x-shop::shimmer.checkout.onepage.shipping-method />

        <!-- Payment Method Shimmer -->
        <x-shop::shimmer.checkout.onepage.payment-method />
    </div>

    <x-shop::shimmer.checkout.onepage.cart-summary />

    <!-- For Mobile View Billing Address -->
    <div class="sm:hidden">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="shimmer h-8 w-[180px]"></h2>
            
            <span class="shimmer h-6 w-6"></span>
        </div>

        <x-shop::shimmer.checkout.onepage.address />
    </div>
</div>
