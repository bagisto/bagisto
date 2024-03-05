<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.index.title')
    </x-slot>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.data-transfer.imports.index.title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            <!-- Create New Tax Rate Button -->
            @if (bouncer()->hasPermission('settings.data_transfer.imports.create'))
                <a href="{{ route('admin.settings.data_transfer.imports.create') }}" class="primary-button">
                    @lang('admin::app.settings.data-transfer.imports.index.button-title')
                </a>
            @endif
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.settings.data_transfer.imports.index')"/>
</x-admin::layouts>