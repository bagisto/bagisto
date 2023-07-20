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
    </div>
</x-admin::layouts>