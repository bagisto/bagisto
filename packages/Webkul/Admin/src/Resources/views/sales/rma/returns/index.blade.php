<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.rma.sales.rma.all-rma.index.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-16 max-sm:flex-wrap">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.rma.rma.index.rma-title')
        </h1>

        <div class="flex items-center gap-x-2.5">
            <x-admin::datagrid.export src="{{ route('admin.sales.rma.index') }}" />

            <a
                href="{{ route('admin.sales.rma.create') }}"
                class="primary-button"
            >
                @lang('admin::app.rma.rma.index.create-rma-title')
            </a>
        </div>

        <!-- Export Modal -->
    </div>

    {!! view_render_event('bagisto.admin.rma.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.sales.rma.index') }}" />

    {!! view_render_event('bagisto.admin.rma.list.after') !!}

</x-admin::layouts>
