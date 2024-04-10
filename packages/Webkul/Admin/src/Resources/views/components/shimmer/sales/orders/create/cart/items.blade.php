<div class="bg-white dark:bg-gray-900 rounded box-shadow">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer w-[108px] h-[17px]"></div>

        <div class="flex gap-4 items-center">
            <div class="shimmer w-[134px] h-[17px]"></div>

            <div class="shimmer w-[123px] h-[40px]"></div>
        </div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="row grid p-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950">
                <div class="flex justify-between gap-2.5">
                    <div class="flex gap-2.5">
                        <!-- Product Image -->
                        <div class="shimmer w-[65px] h-[65px] rounded"></div>

                        <!-- Product Detailes -->
                        <div class="flex flex-col gap-1.5 w-[251px]">
                            <!-- Product Name -->
                            <div class="shimmer w-full h-[17px]"></div>

                            <div class="shimmer w-[65px] h-[17px]"></div>

                            <div class="shimmer w-[65px] h-[17px]"></div>
                        </div>
                    </div>

                    <div class="grid">
                        <div class="shimmer w-[65px] h-[17px]"></div>
                    </div>
                </div>

                <div class="flex gap-2.5 justify-end mt-2">
                    <!-- Product Price -->
                    <div class="shimmer w-[42px] h-[17px]"></div>

                    <!-- Grand Total -->
                    <div class="shimmer w-[105px] h-[17px]"></div>
                </div>
            </div>
        @endfor
    </div>
</div>