<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.families.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('admin::app.catalog.families.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            @if (bouncer()->hasPermission('catalog.families.create'))
                <a href="{{ route('admin.catalog.families.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.catalog.families.index.add')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.catalog.families.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.catalog.families.index') }}"></x-admin::datagrid>

    {!! view_render_event('bagisto.admin.catalog.families.list.after') !!}

</x-admin::layouts>