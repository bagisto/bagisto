<div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
    <!-- Left sub-components -->
    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
        <!-- General Section -->
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <div class="shimmer mb-4 h-5 w-16"></div>

            <!-- Name -->
            <div class="mb-4">
                <div class="shimmer mb-1.5 h-3.5 w-16"></div>

                <div class="h-[42px] w-full rounded-md border px-3 py-2.5 dark:border-gray-800 dark:bg-gray-900"></div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <div class="shimmer mb-1.5 h-4 w-16"></div>

                <div class="h-[61px] w-full rounded-md border px-3 py-2.5 dark:border-gray-800 dark:bg-gray-900"></div>
            </div>

            @for ($i = 1; $i < 3; $i++)
                <div class="mb-5 last:!mb-8">
                    <div class="shimmer mb-1.5 h-3.5 w-16"></div>

                    <div class="h-[42px] w-full rounded-md border px-3 py-2.5 dark:border-gray-800 dark:bg-gray-900"></div>
                </div>
            @endfor
        </div>

        <!-- Conditions Section -->
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <div class="flex items-center justify-between gap-4">
                <div class="shimmer h-5 w-20"></div>

                <div class="grid w-[204px] gap-2">
                    <div class="shimmer grid-col-1 h-4"></div>

                    <div class="shimmer h-[42px] rounded"></div>
                </div>
            </div>

            <!-- Button -->
            <div class="secondary-button mt-4 h-[38px] w-[137px]"></div>
        </div>
    </div>

     <!-- Right sub-components -->
    <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
        <x-admin::shimmer.accordion />

        <x-admin::shimmer.accordion />
    </div>
</div>