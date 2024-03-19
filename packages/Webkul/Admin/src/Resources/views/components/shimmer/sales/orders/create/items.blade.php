<div class="bg-white dark:bg-gray-900 rounded box-shadow">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer w-[108px] h-[17px]"></div>

        <div class="shimmer w-[82px] h-[17px]"></div>
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

                    <div class="shimmer w-[65px] h-[17px]"></div>

                    <div class="shimmer w-[65px] h-[17px]"></div>

                    <div class="flex gap-2.5 mt-2">
                        <!-- Product Price -->
                        <div class="shimmer w-[42px] h-[17px]"></div>

                        <!-- Grand Total -->
                        <div class="shimmer w-[82px] h-[17px]"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>