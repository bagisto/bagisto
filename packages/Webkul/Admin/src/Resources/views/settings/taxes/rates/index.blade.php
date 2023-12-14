<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.settings.taxes.rates.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-xl leading-none text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.taxes.rates.index.title')
        </p>
        
        <div class="flex gap-x-2.5 items-center">
            <!-- Create New Pages Button -->
            @if (bouncer()->hasPermission('settings.taxes.tax-rates.create'))
                <a 
                    href="{{ route('admin.settings.taxes.rates.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.settings.taxes.rates.index.button-title')
                </a>
            @endif
        </div>
    </div>
    
    <x-admin::datagrid :src="route('admin.settings.taxes.rates.index')"></x-admin::datagrid>
</x-admin::layouts>