<div class="border-b dark:border-gray-800">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer h-[17px] w-[157px]"></div>

        <div class="shimmer h-[17px] w-[83px]"></div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="flex gap-2.5 border-b p-4 last:border-b-0 dark:border-gray-800">
                <!-- Product Image -->
                <div class="shimmer h-[65px] w-[65px] rounded"></div>

                <!-- Product Details -->
                <div class="flex w-[251px] flex-col gap-1.5">
                    <!-- Product Name -->
                    <div class="shimmer h-[17px] w-full"></div>

                    <div class="flex justify-between">
                        <!-- Product Price -->
                        <div class="shimmer h-[17px] w-[52px]"></div>

                        <!-- Grand Total -->
                        <div class="shimmer h-[17px] w-[72px]"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>