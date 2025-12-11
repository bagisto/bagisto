<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.rma.custom-field.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <!-- Title -->
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.sales.rma.custom-field.index.title')
        </p>

        <!-- Create Button -->
        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('sales.custom-field.create'))
                <a
                    class="primary-button"
                    href="{{ route('admin.sales.rma.custom-field.create') }}"
                >
                    @lang('admin::app.sales.rma.custom-field.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.catalog.rma.custom-field.list.before') !!}

    <x-admin::datagrid :src="route('admin.sales.rma.custom-field.index')"/>

    {!! view_render_event('bagisto.admin.catalog.rma.custom-field.list.after') !!}

</x-admin::layouts>