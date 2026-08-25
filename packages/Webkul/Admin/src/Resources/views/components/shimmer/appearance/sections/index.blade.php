<div class="flex h-[calc(100vh-140px)] min-h-[480px] flex-col">
    <!-- Page Header -->
    <div class="flex shrink-0 items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <div class="shimmer h-6 w-32 rounded"></div>

            <div class="shimmer h-3 w-64 rounded"></div>
        </div>

        <div class="shimmer h-9 w-40 rounded"></div>
    </div>

    <div class="mt-4 flex min-h-0 flex-1 gap-4 max-lg:flex-col">
        <!-- Section List -->
        <div class="box-shadow flex w-[340px] shrink-0 flex-col overflow-hidden rounded bg-white dark:bg-gray-900 max-lg:w-full">
            <div class="flex shrink-0 items-center gap-2 border-b p-4 dark:border-gray-800">
                <div class="shimmer h-5 w-24 rounded"></div>

                <div class="shimmer h-6 w-6 rounded-full"></div>
            </div>

            <div class="flex min-h-0 flex-1 flex-col overflow-hidden">
                @for ($row = 0; $row < 16; $row++)
                    <div class="flex shrink-0 items-center gap-2 border-b px-3 py-2.5 dark:border-gray-800">
                        <div class="shimmer h-4 w-3 rounded"></div>

                        <div class="grid flex-1 gap-1.5">
                            <div class="shimmer h-3.5 w-32 rounded"></div>

                            <div class="shimmer h-3 w-20 rounded"></div>
                        </div>

                        <div class="shimmer h-5 w-9 rounded-full"></div>

                        <div class="shimmer h-4 w-1 rounded"></div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Preview -->
        <div class="box-shadow flex min-w-0 flex-1 flex-col overflow-hidden rounded bg-white dark:bg-gray-900">
            <div class="flex shrink-0 items-center justify-between gap-2 border-b p-4 dark:border-gray-800">
                <div class="shimmer h-5 w-20 rounded"></div>

                <div class="flex items-center gap-1">
                    <div class="shimmer h-6 w-16 rounded"></div>

                    <div class="shimmer h-6 w-14 rounded"></div>

                    <div class="shimmer h-6 w-14 rounded"></div>

                    <div class="shimmer h-6 w-6 rounded"></div>
                </div>
            </div>

            <div class="min-h-0 flex-1 bg-gray-100 p-4 dark:bg-gray-950">
                <div class="shimmer h-full w-full rounded bg-white dark:bg-gray-900"></div>
            </div>
        </div>
    </div>
</div>
