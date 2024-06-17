@for ($i = 1; $i <= 2; $i++)
    <div class="grid gap-4 border-b p-4 last:border-b-0 dark:border-gray-800">
        <div class="flex justify-between gap-4">
            <!-- Infomration -->
            <div class="flex items-center gap-2">
                <div class="shimmer h-6 w-6"></div>

                <div class="shimmer h-[17px] w-[70px]"></div>
            </div>

            <!-- Total -->
            <div class="shimmer h-6 w-[49px]"></div>
        </div>

        <!-- Description -->
        <div class="shimmer h-[17px] w-[150px]"></div>
    </div>
@endfor