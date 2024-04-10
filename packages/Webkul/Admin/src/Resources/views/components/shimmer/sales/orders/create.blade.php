<div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
    <!-- Left Container -->
    <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto overflow-y-auto">
        <!-- Cart Items Shimmer Effect -->
        <x-admin::shimmer.sales.orders.create.cart.items />
    </div>

    <!-- Right Container -->
    <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
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