<!-- Pannel Content -->
<div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
    <!-- Left Component -->
    <div class="flex flex-1 flex-col gap-2 overflow-auto max-xl:flex-auto">
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <div class="shimmer mb-4 h-4 w-16"></div>

            @for ($i = 1; $i < 10; $i++)
                <div class="mb-6 h-14 w-full">
                    <div class="shimmer mb-2 h-4 w-24"></div>

                    <div class="shimmer flex h-10 w-full rounded-md py-px"></div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Right Component -->
    <div class="flex w-[360px] max-w-full flex-col gap-2">
        <!-- General -->
        <div class="box-shadow rounded bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between gap-x-5 p-4">
                <p class="shimmer w-20 p-2.5"></p>
                
                <p class="shimmer w-5 p-2.5"></p>
            </div>
            
            <div class="px-4 pb-4">
                @for ($i = 1; $i < 4; $i++)
                    <div class="mb-4 last:mb-0">
                        <div class="shimmer mb-1.5 h-4 w-24"></div>

                        <div class="shimmer flex h-10 w-full rounded-md py-px"></div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Validation Section -->
        <div class="box-shadow rounded bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between gap-x-5 p-4">
                <p class="shimmer w-24 p-2.5"></p>
                
                <p class="shimmer w-5 p-2.5"></p>
            </div>

            <div class="px-4 pb-4">
                <div class="mb-2 flex items-center gap-2.5">
                    <div class="shimmer h-6 w-6"></div>

                    <div class="shimmer h-4 w-20"></div>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="shimmer h-6 w-6"></div>

                    <div class="shimmer h-4 w-20"></div>
                </div>
            </div>
        </div>

        <!-- Configuration Section -->
        <x-admin::shimmer.accordion />
    </div>
</div>