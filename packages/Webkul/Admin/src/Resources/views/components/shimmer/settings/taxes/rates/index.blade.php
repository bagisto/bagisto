
<div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
    <!-- Left Component -->
    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.settings.taxes.rates.create.general')
            </p>

            <div>
                @for ($i = 1; $i < 5; $i++)
                    <div class="mb-4 last:!mb-0">
                        <div class="shimmer mb-1.5 h-4 w-28"></div>

                        <div class="h-11 w-full rounded-md border px-3 py-5 text-sm text-gray-600 transition-all last:!h-10 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-gray-400"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Right Component -->
    <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">
        <div class="box-shadow rounded bg-white p-1.5 dark:bg-gray-900">
            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.settings.taxes.rates.create.settings')
            </p>

            <div class="px-3">
                <div class="py-1.5">
                    <div class="shimmer mb-2 h-4 w-28"></div>

                    <div class="shimmer h-5 w-8 rounded-full"></div>
                </div>

                <div class="mb-1 mt-2 py-1.5">
                    <div class="shimmer mb-2 h-4 w-16"></div>

                    <div class="h-10 w-full rounded-md border px-3 py-5 text-sm text-gray-600 transition-all focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-gray-400"></div>
                </div>
            </div>
        </div>
    </div>
</div>