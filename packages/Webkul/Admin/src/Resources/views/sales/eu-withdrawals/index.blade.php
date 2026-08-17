<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.eu_withdrawal.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.eu_withdrawal.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <x-admin::datagrid.export src="{{ route('admin.sales.eu-withdrawals.index') }}" />
        </div>
    </div>

    {!! view_render_event('bagisto.admin.sales.eu_withdrawals.index.datagrid.before') !!}

    <x-admin::datagrid :src="route('admin.sales.eu-withdrawals.index')" />

    {!! view_render_event('bagisto.admin.sales.eu_withdrawals.index.datagrid.after') !!}
</x-admin::layouts>
