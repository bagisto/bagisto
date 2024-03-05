<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.invoices.index.title')
    </x-slot>

    <div class="flex  gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="py-3 text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.sales.invoices.index.title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            <!-- Export Modal -->
            <x-admin::datagrid.export src="{{ route('admin.sales.invoices.index') }}" />
        </div>
    </div>

    <x-admin::datagrid src="{{ route('admin.sales.invoices.index') }}" />

</x-admin::layouts>
