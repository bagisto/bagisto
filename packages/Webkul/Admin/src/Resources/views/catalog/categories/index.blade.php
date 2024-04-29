<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.categories.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.catalog.categories.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            {!! view_render_event('bagisto.admin.catalog.categories.index.create-button.before') !!}

            @if (bouncer()->hasPermission('catalog.categories.create'))
                <a href="{{ route('admin.catalog.categories.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.catalog.categories.index.add-btn')
                    </div>
                </a>
            @endif

            {!! view_render_event('bagisto.admin.catalog.categories.index.create-button.after') !!}
        </div>        
    </div>

    {!! view_render_event('bagisto.admin.catalog.categories.list.before') !!}

    <x-admin::datagrid :src="route('admin.catalog.categories.index')" />

    {!! view_render_event('bagisto.admin.catalog.categories.list.after') !!}

</x-admin::layouts>
