<!-- Wishlist Added Shimmer -->
<div class="relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div class="shimmer w-[150px] h-[17px]"></div>

        <div class="shimmer w-[79px] h-[21px]"></div>
    </div>

    <div class="grid gap-4">
        <div class="flex gap-4 place-content-start">
            <div class="flex gap-4 items-center">
                <div class="shimmer w-[120px] h-9"></div>
                <div class="shimmer w-[75px] h-[17px]"></div>
            </div>
        </div>

        <div class="shimmer w-[120px] h-5"></div>

        <!-- Graph Shimmer -->
        <x-admin::shimmer.reporting.graph :count=15 />

        <!-- Date Range -->
        <div class="flex gap-5 justify-center">
            <div class="flex gap-1 items-center">
                <div class="shimmer w-3.5 h-3.5 rounded-md"></div>
                <div class="shimmer w-[143px] h-[17px]"></div>
            </div>
            
            <div class="flex gap-1 items-center">
                <div class="shimmer w-3.5 h-3.5 rounded-md"></div>
                <div class="shimmer w-[143px] h-[17px]"></div>
            </div>
        </div>
    </div>
</div>