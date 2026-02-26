<div class="border-b dark:border-gray-800">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer h-[17px] w-[157px]"></div>

        <div class="shimmer h-[17px] w-[83px]"></div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="flex gap-8 border-b p-4 last:border-b-0 dark:border-gray-800">
                <div class="flex h-[38px] w-full justify-between gap-1.5">
                    <div class="flex flex-col gap-y-1">
                        <!-- Customer Name -->
                        <div class="shimmer h-[19px] w-[137px]"></div>

                        <!-- Customer Email -->
                        <div class="shimmer h-[19px] w-[137px]"></div>
                    </div>

                    <div class="flex flex-col gap-y-1">
                        <!-- Grand Total -->
                        <div class="shimmer h-[19px] w-[72px]"></div>

                        <!-- TOtal Orders count -->
                        <div class="shimmer h-[19px] w-[72px]"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>