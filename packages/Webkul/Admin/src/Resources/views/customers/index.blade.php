<x-admin::layouts>
    <div class="flex-1 h-full max-w-full px-[16px] pt-[11px] pb-[22px] max-lg:px-[16px]">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">Customers</p>
            <div class="flex gap-x-[10px] items-center">
                @include('admin::customers.create')
            </div>
        </div>
    </div>
</x-admin::layouts>