<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('booking::app.admin.sales.bookings.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="py-[11px] text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('booking::app.admin.sales.bookings.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Export Modal -->
            <x-admin::datagrid.export src="{{ route('admin.sales.bookings.index') }}"></x-admin::datagrid.export>
        </div>
    </div>

    <!-- Datagrid -->
    <x-admin::datagrid src="{{ route('admin.sales.bookings.index') }}"></x-admin::datagrid>

</x-admin::layouts>