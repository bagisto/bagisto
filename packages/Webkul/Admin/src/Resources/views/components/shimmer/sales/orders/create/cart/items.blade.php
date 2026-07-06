<div class="box-shadow rounded-sm bg-white dark:bg-gray-900">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer h-4.25 w-27"></div>

        <div class="flex items-center gap-4">
            <div class="shimmer h-4.25 w-33.5"></div>

            <div class="shimmer h-10 w-30.75"></div>
        </div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="row grid border-b bg-white p-4 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950">
                <div class="flex justify-between gap-2.5">
                    <div class="flex gap-2.5">
                        <!-- Product Image -->
                        <div class="shimmer h-16.25 w-16.25 rounded-sm"></div>

                        <!-- Product Details -->
                        <div class="flex w-62.75 flex-col gap-1.5">
                            <!-- Product Name -->
                            <div class="shimmer h-4.25 w-full"></div>

                            <div class="shimmer h-4.25 w-16.25"></div>

                            <div class="shimmer h-4.25 w-16.25"></div>
                        </div>
                    </div>

                    <div class="grid">
                        <div class="shimmer h-4.25 w-16.25"></div>
                    </div>
                </div>

                <div class="mt-2 flex justify-end gap-2.5">
                    <!-- Product Price -->
                    <div class="shimmer h-4.25 w-10.5"></div>

                    <!-- Grand Total -->
                    <div class="shimmer h-4.25 w-26.25"></div>
                </div>
            </div>
        @endfor
    </div>
</div>