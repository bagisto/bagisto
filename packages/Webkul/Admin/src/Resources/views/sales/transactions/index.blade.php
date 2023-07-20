<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.transactions.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.sales.transactions.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <a 
                href="{{ route('admin.sales.transactions.create') }}"
                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
            >
                @lang('admin::app.sales.transactions.create.add')
            </a>
        </div>
    </div>
    
    {{-- datagrid will be here --}}
</x-admin::layouts>