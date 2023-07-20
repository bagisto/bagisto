<x-admin::layouts>
    <x-slot:title>
        @lang('bookingproduct::app.admin.sales.bookings.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="py-[11px] text-[20px] text-gray-800 font-bold">
            @lang('bookingproduct::app.admin.sales.bookings.index.title')
        </p>
    </div>
    
    {{-- datagrid will be here --}}
</x-admin::layouts>