<div class="rounded box-shadow">
    @for ($i = 1; $i <= 5; $i++)
        <div class="row grid grid-cols-2 gap-y-6 p-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950 max-sm:grid-cols-[1fr_auto]">
            <div class="flex gap-2.5">
                <div class="shimmer w-[65px] h-[65px] rounded"></div>

                <div class="flex flex-col gap-1.5">
                    <!-- Product Name -->
                    <div class="shimmer w-[150px] h-[17px] rounded"></div>
                    
                    <!-- Product SKU -->
                    <div class="shimmer w-[150px] h-[17px] rounded"></div>
                </div>
            </div>

            <div class="flex gap-1.5 items-center justify-between">
                <div class="flex flex-col gap-1.5">
                    <!-- Product Price -->
                    <div class="shimmer w-[50px] h-[17px] rounded"></div>

                    <!-- Total Product Stock -->
                    <div class="shimmer w-[50px] h-[17px] rounded"></div>
                </div>

                <!-- View More Icon -->
                <div class="shimmer w-9 h-9 rounded-md"></div>
            </div>
        </div>
    @endfor
</div>