<x-admin::layouts>

    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.channels.index.title')
    </x-slot>

    <div class="flex justify-between items-center">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.channels.index.title')
        </p>
        
        <div class="flex gap-x-2.5 items-center">
            <!-- Create New Channel Button -->
            @if (bouncer()->hasPermission('settings.channels.create'))
                <a 
                    href="{{ route('admin.settings.channels.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.settings.channels.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.settings.channels.list.before') !!}
    
    <x-admin::datagrid src="{{ route('admin.settings.channels.index') }}" />

    {!! view_render_event('bagisto.settings.channels.list.after') !!}

</x-admin::layouts>