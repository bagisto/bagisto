<div class="box-shadow rounded-sm bg-white dark:bg-gray-900">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer h-4.25 w-27"></div>

        <div class="shimmer h-4.25 w-20.5"></div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="flex gap-2.5 border-b p-4 last:border-b-0 dark:border-gray-800">
                <!-- Product Image -->
                <div class="shimmer h-16.25 w-16.25 rounded-sm"></div>

                <!-- Product Details -->
                <div class="flex w-62.75 flex-col gap-1.5">
                    <!-- Product Name -->
                    <div class="shimmer h-4.25 w-full"></div>

                    <div class="shimmer h-4.25 w-16.25"></div>

                    <div class="shimmer h-4.25 w-16.25"></div>

                    <div class="mt-2 flex gap-2.5">
                        <!-- Product Price -->
                        <div class="shimmer h-4.25 w-10.5"></div>

                        <!-- Grand Total -->
                        <div class="shimmer h-4.25 w-20.5"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>