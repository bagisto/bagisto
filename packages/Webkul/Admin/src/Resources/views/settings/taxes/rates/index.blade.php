<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.taxes.rates.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.settings.taxes.rates.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Tax Rate Export -->
            <x-admin::datagrid.export src="{{ route('admin.settings.taxes.rates.index') }}" />

            <!-- Create New Tax Rate Button -->
            @if (bouncer()->hasPermission('settings.taxes.tax_rates.create'))
                <a href="{{ route('admin.settings.taxes.rates.create') }}" class="primary-button">
                    @lang('admin::app.settings.taxes.rates.index.button-title')
                </a>
            @endif
        </div>
    </div>

    <x-admin::datagrid
        :src="route('admin.settings.taxes.rates.index')"
        ref="datagrid"
    />
</x-admin::layouts>
