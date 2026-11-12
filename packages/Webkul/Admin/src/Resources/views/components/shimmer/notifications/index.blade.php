<div>
    <!-- Page Header -->
    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <div class="shimmer h-6 w-36"></div>

            <div class="shimmer h-4 w-80 max-w-full"></div>
        </div>

        <div class="shimmer h-10 w-36 rounded-md"></div>
    </div>

    <div class="flex items-start gap-4 max-lg:flex-col">
        <!-- Filters -->
        <div class="box-shadow grid w-64 shrink-0 gap-1 rounded-md bg-white p-2 dark:bg-gray-900 max-lg:w-full">
            @for ($i = 0; $i < 6; $i++)
                <div class="flex items-center gap-2.5 px-3 py-2">
                    <div class="shimmer h-5 w-5 rounded-full"></div>

                    <div class="shimmer h-4 w-24"></div>
                </div>
            @endfor
        </div>

        <!-- Feed -->
        <div class="box-shadow min-w-0 flex-1 rounded-md bg-white dark:bg-gray-900 max-lg:w-full">
            <x-admin::shimmer.notifications.list />
        </div>
    </div>
</div>
