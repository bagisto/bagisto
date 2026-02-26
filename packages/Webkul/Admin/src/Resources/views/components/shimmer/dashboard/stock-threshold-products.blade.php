<div class="box-shadow rounded">
    @for ($i = 1; $i <= 5; $i++)
        <div class="row grid grid-cols-2 gap-y-6 border-b bg-white p-4 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950 max-sm:grid-cols-[1fr_auto]">
            <div class="flex gap-2.5">
                <div class="shimmer h-[65px] w-[65px] rounded"></div>

                <div class="flex flex-col gap-1.5">
                    <!-- Product Name -->
                    <div class="shimmer h-[17px] w-[150px] rounded"></div>
                    
                    <!-- Product SKU -->
                    <div class="shimmer h-[17px] w-[150px] rounded"></div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-1.5">
                <div class="flex flex-col gap-1.5">
                    <!-- Product Price -->
                    <div class="shimmer h-[17px] w-[50px] rounded"></div>

                    <!-- Total Product Stock -->
                    <div class="shimmer h-[17px] w-[50px] rounded"></div>
                </div>

                <!-- View More Icon -->
                <div class="shimmer h-9 w-9 rounded-md"></div>
            </div>
        </div>
    @endfor
</div>