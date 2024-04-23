<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.refunds.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.sales.refunds.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sales.refunds.index')" />
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.sales.refunds.index')" />
</x-admin::layouts>
