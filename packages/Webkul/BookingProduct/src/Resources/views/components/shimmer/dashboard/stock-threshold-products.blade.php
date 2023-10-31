<div class="rounded-[4px] box-shadow">
    @for ($i = 1; $i <= 5; $i++)
        <div class="row grid grid-cols-2 gap-y-[24px] p-[16px] bg-white dark:bg-gray-900 border-b-[1px] dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950 max-sm:grid-cols-[1fr_auto]">
            <div class="flex gap-[10px]">
                <div class="shimmer w-[65px] h-[65px] rounded-[4px]"></div>

                <div class="flex flex-col gap-[6px]">
                    <!-- Product Name -->
                    <div class="shimmer w-[150px] h-[17px] rounded-[4px]"></div>
                    
                    <!-- Product SKU -->
                    <div class="shimmer w-[150px] h-[17px] rounded-[4px]"></div>
                </div>
            </div>

            <div class="flex gap-[6px] items-center justify-between">
                <div class="flex flex-col gap-[6px]">
                    <!-- Product Price -->
                    <div class="shimmer w-[50px] h-[17px] rounded-[4px]"></div>

                    <!-- Total Product Stock -->
                    <div class="shimmer w-[50px] h-[17px] rounded-[4px]"></div>
                </div>

                <!-- View More Icon -->
                <div class="shimmer w-[36px] h-[36px] rounded-[6px]"></div>
            </div>
        </div>
    @endfor
</div>