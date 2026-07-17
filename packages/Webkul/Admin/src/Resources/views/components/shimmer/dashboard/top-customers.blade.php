<div class="border-b dark:border-gray-800">
    <div class="flex items-center justify-between p-4">
        <div class="shimmer h-4.25 w-39.25"></div>

        <div class="shimmer h-4.25 w-20.75"></div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="flex gap-8 border-b p-4 last:border-b-0 dark:border-gray-800">
                <div class="flex h-9.5 w-full justify-between gap-1.5">
                    <div class="flex flex-col gap-y-1">
                        <!-- Customer Name -->
                        <div class="shimmer h-4.75 w-34.25"></div>

                        <!-- Customer Email -->
                        <div class="shimmer h-4.75 w-34.25"></div>
                    </div>

                    <div class="flex flex-col gap-y-1">
                        <!-- Grand Total -->
                        <div class="shimmer h-4.75 w-18"></div>

                        <!-- TOtal Orders count -->
                        <div class="shimmer h-4.75 w-18"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>