<div class="box-shadow rounded bg-white dark:bg-gray-900">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer h-[17px] w-[108px]"></div>

        <div class="flex items-center gap-4">
            <div class="shimmer h-[17px] w-[134px]"></div>

            <div class="shimmer h-10 w-[123px]"></div>
        </div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="row grid border-b bg-white p-4 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950">
                <div class="flex justify-between gap-2.5">
                    <div class="flex gap-2.5">
                        <!-- Product Image -->
                        <div class="shimmer h-[65px] w-[65px] rounded"></div>

                        <!-- Product Details -->
                        <div class="flex w-[251px] flex-col gap-1.5">
                            <!-- Product Name -->
                            <div class="shimmer h-[17px] w-full"></div>

                            <div class="shimmer h-[17px] w-[65px]"></div>

                            <div class="shimmer h-[17px] w-[65px]"></div>
                        </div>
                    </div>

                    <div class="grid">
                        <div class="shimmer h-[17px] w-[65px]"></div>
                    </div>
                </div>

                <div class="mt-2 flex justify-end gap-2.5">
                    <!-- Product Price -->
                    <div class="shimmer h-[17px] w-[42px]"></div>

                    <!-- Grand Total -->
                    <div class="shimmer h-[17px] w-[105px]"></div>
                </div>
            </div>
        @endfor
    </div>
</div>