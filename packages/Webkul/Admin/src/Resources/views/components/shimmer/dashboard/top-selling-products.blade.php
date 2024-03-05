<div class="border-b dark:border-gray-800">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer w-[157px] h-[17px]"></div>

        <div class="shimmer w-[83px] h-[17px]"></div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="flex gap-2.5 p-4 border-b dark:border-gray-800 last:border-b-0">
                <!-- Product Image -->
                <div class="shimmer w-[65px] h-[65px] rounded"></div>

                <!-- Product Detailes -->
                <div class="flex flex-col gap-1.5 w-[251px]">
                    <!-- Product Name -->
                    <div class="shimmer w-full h-[17px]"></div>

                    <div class="flex justify-between">
                        <!-- Product Price -->
                        <div class="shimmer w-[52px] h-[17px]"></div>

                        <!-- Grand Total -->
                        <div class="shimmer w-[72px] h-[17px]"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>