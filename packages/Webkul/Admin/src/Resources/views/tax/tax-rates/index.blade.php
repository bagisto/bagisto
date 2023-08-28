<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.taxes.tax-rates.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.taxes.tax-rates.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            {{-- Create New Pages Button --}}
            @if (bouncer()->hasPermission('settings.taxes.tax-rates.create'))
                <a 
                    href="{{ route('admin.settings.taxes.tax_rates.create') }}"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.settings.taxes.tax-rates.index.button-title')
                </a>
            @endif
        </div>
    </div>
    
    <x-admin::datagrid :src="route('admin.settings.taxes.tax_rates.index')"></x-admin::datagrid>
</x-admin::layouts>