<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.customers.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.customers.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            {{-- Create a new Customer --}}
            @include('admin::customers.create')
        </div>

        <div class="flex gap-x-[10px] items-center">
            <a href="{{ route('admin.customer.view', 1) }}">
                <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                    @lang('Customer View')
                </div>
            </a>
            
        </div>
    </div>
</x-admin::layouts>