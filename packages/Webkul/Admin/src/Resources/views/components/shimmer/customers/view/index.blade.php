<div class="grid px-1 py-2">
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="flex items-center gap-2.5">
            <div class="shimmer h-6 w-24"></div>

            <div class="shimmer mx-1 h-6 w-14 rounded-full"></div>
        </div>

        <div class="shimmer h-6 w-12"></div>
    </div>
</div>

<div class="mt-8 flex flex-wrap items-center gap-x-1 gap-y-2">
    <div class="shimmer mx-1 h-8 w-35.25"></div>

    <div class="shimmer mx-1 h-8 w-44.5"></div>

    <div class="shimmer mx-1 h-8 w-41.5"></div>
</div>

<div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
    <!-- Left Section -->
    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
        <div class="box-shadow rounded-sm bg-white p-4 dark:bg-gray-900">
            <div class="flex justify-between">
                <div class="shimmer h-4.5 w-19.25"></div>

                <div class="shimmer h-4.5 w-40"></div>
            </div>

            <div>
                <x-admin::shimmer.datagrid.toolbar />

                <div class="table-responsive box-shadow mt-4 grid w-full overflow-hidden rounded-sm bg-white dark:bg-gray-900">

                    <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />

                    <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
                </div>
            </div>
        </div>
    </div>

    <!--Right Section -->
    <div class="flex w-90 max-w-full flex-col gap-2 max-sm:w-full">
        <div class="box-shadow rounded-sm bg-white dark:bg-gray-900">
            <x-admin::shimmer.accordion class="h-50!" />
        </div>

        <div class="box-shadow rounded-sm bg-white dark:bg-gray-900">
            <x-admin::shimmer.accordion />
        </div>
    </div>
</div>