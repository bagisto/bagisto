<div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
    <!-- Left Container -->
    <div class="flex flex-1 flex-col gap-2 overflow-y-auto max-xl:flex-auto">
        <!-- Cart Items Shimmer Effect -->
        <x-admin::shimmer.sales.orders.create.cart.items />
    </div>

    <!-- Right Container -->
    <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
        <!-- Cart Items Shimmer Effect -->
        <x-admin::shimmer.sales.orders.create.items />

        <!-- Wishlist Items Shimmer Effect -->
        <x-admin::shimmer.sales.orders.create.items />

        <!-- Compare Items Shimmer Effect -->
        <x-admin::shimmer.sales.orders.create.items />

        <!-- Recent Order Items Shimmer Effect -->
        <x-admin::shimmer.sales.orders.create.items />
    </div>
</div>