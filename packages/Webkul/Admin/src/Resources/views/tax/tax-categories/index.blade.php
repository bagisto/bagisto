<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.taxes.tax-categories.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.taxes.tax-categories.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            {{-- Create Tax Category Button --}}
            <div class="flex gap-x-[10px] items-center">
                @include('admin::tax.tax-categories.create')
            </div>
        </div>
    </div>
    
    {{-- datagrid will be here --}}
</x-admin::layouts>