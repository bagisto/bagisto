<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.transactions.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('admin::app.sales.transactions.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Export Modal -->
            <x-admin::datagrid.export src="{{ route('admin.sales.transactions.index') }}"></x-admin::datagrid.export>
        </div>
    </div>

    <x-admin::datagrid src="{{ route('admin.sales.transactions.index') }}"></x-admin::datagrid>

</x-admin::layouts>
