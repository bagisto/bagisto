<x-booking::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('booking::app.admin.sales.bookings.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="py-[11px] text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('booking::app.admin.sales.bookings.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Export Modal -->
            <x-booking::datagrid.export src="{{ route('admin.sales.bookings.index') }}"></x-booking::datagrid.export>
        </div>
    </div>

    <x-booking::datagrid src="{{ route('admin.sales.bookings.index') }}"></x-booking::datagrid>

</x-booking::layouts>