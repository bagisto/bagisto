@props(['title'])

<div class="flex items-center justify-between">
    <p class="text-xl font-bold text-gray-800 dark:text-white">
        {{ $title }}
    </p>

    <div class="flex items-center gap-x-2.5">
        <!-- Back Button -->
        <a class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
            @lang('admin::app.catalog.attributes.create.back-btn')
        </a>

        <!-- Save Button -->
        <button
            type="submit"
            class="primary-button"
        >
            @lang('admin::app.catalog.attributes.create.save-btn')
        </button>
    </div>
</div>

<!-- Pannel Content -->
<div class="mt-3.5 flex gap-2.5">
    <!-- Left Component -->
    <div class="flex flex-1 flex-col gap-2 overflow-auto">
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <div class="shimmer mb-4 h-5 w-[50px]"></div>

            <div>
                <div class="mb-6 h-[55px] w-full">
                    <div class="shimmer mb-2 h-4 w-[100px]"></div>

                    <div class="shimmer flex min-h-[39px] w-full rounded-md px-3 py-2"></div>
                </div>

                <div class="mb-6 h-[55px] w-full">
                    <div class="shimmer mb-2 h-4 w-[100px]"></div>

                    <div class="shimmer flex min-h-[39px] w-full rounded-md px-3 py-2"></div>
                </div>

                <div class="mb-2 h-[55px] w-full">
                    <div class="shimmer mb-2 h-4 w-[100px]"></div>

                    <div class="shimmer flex min-h-[39px] w-full rounded-md px-3 py-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Component -->
    <div class="flex w-[360px] max-w-full flex-col gap-2">
        <!-- General -->
        <div class="box-shadow rounded bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between p-1.5">
                <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.catalog.attributes.create.general')
                </p>
            </div>

            <div class="px-4 pb-4">
                <div class="mb-6 h-[55px] w-full">
                    <div class="shimmer mb-2 h-4 w-[100px]"></div>

                    <div class="shimmer flex min-h-[39px] w-full rounded-md px-3 py-2"></div>
                </div>

                <div class="mb-6 h-[55px] w-full">
                    <div class="shimmer mb-2 h-4 w-[100px]"></div>

                    <div class="shimmer flex min-h-[39px] w-full rounded-md px-3 py-2"></div>
                </div>

                <div class="mb-2 h-[55px] w-full">
                    <div class="shimmer mb-2 h-4 w-[100px]"></div>

                    <div class="shimmer flex min-h-[39px] w-full rounded-md px-3 py-2"></div>
                </div>
            </div>
        </div>

        <!-- Validation Section -->
        <div class="box-shadow rounded bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between p-1.5">
                <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.catalog.attributes.create.validations')
                </p>

                <div class="shimmer mx-2.5 h-6 w-6 rounded"></div>
            </div>

            <div class="px-4 pb-4">
                <div class="mb-2 flex items-center gap-5">
                    <div class="shimmer h-6 w-6"></div>

                    <div class="shimmer h-6 w-16"></div>
                </div>

                <div class="flex items-center gap-5">
                    <div class="shimmer h-6 w-6"></div>

                    <div class="shimmer h-6 w-16"></div>
                </div>
            </div>
        </div>

        <!-- Configuration Section -->
        <div class="box-shadow rounded bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between p-1.5">
                <div class="shimmer h-5 w-[90px]"></div>

                <div class="shimmer mx-2.5 h-6 w-6 rounded"></div>
            </div>

            <div class="px-4 pb-4">
                <div class="mb-3 flex items-center gap-5">
                    <div class="shimmer h-5 w-6"></div>

                    <div class="shimmer h-5 w-[64px]"></div>
                </div>

                <div class="flex items-center gap-5">
                    <div class="shimmer h-5 w-6"></div>

                    <div class="shimmer h-5 w-[64px]"></div>
                </div>
            </div>
        </div>
    </div>
</div>