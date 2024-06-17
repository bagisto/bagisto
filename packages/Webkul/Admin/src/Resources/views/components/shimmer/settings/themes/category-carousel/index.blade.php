<div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <div class="mb-2.5 flex items-center justify-between gap-x-2.5">
            <div class="flex flex-col gap-1">
                <div class="shimmer h-[17px] w-40"></div>

                <div class="shimmer h-[17px] w-[434px]"></div>
            </div>
        </div>

        @for ($i = 1; $i < 3; $i++)
            <div class="mb-4">
                <div class="shimmer mb-1.5 flex h-4 w-16 items-center"></div>

                <div class="shimmer flex h-10 w-full rounded-md py-px"></div>
            </div>
        @endfor

        <!-- Horizontal line -->
        <span class="mb-4 mt-4 block w-full border-b dark:border-gray-800"></span>

        <!-- Filters Section -->
        <div class="flex items-center justify-between gap-x-2.5">
            <div class="shimmer h-6 w-16"></div>

            <div class="flex gap-2.5">
                <div class="shimmer h-10 w-[105px] rounded-md"></div>
            </div>
        </div>

        <!-- Filter attributes -->
        <div class="grid">
            <div class="flex cursor-pointer items-center justify-between gap-2.5 py-5">
                <div class="flex gap-2.5">
                    <div class="grid place-content-start gap-1.5">
                        <div class="shimmer h-4 w-24"></div>

                        <div class="shimmer h-4 w-16"></div>
                    </div>
                </div>

                <!-- Delete button -->
                <div class="grid place-content-start gap-1 text-right">
                    <div class="flex items-center gap-x-5">
                        <div class="shimmer h-4 w-12"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>