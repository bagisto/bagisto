<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.roles.index.title')
    </x-slot>

    <div class="flex justify-between items-center">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.roles.index.title')
        </p>
        
        <div class="flex gap-x-2.5 items-center">
            <!-- Add Role Button -->
            @if (bouncer()->hasPermission('settings.roles.create')) 
                <a 
                    href="{{ route('admin.settings.roles.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.settings.roles.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.settings.roles.list.before') !!}
    
    <x-admin::datagrid src="{{ route('admin.settings.roles.index') }}" />

    {!! view_render_event('bagisto.admin.settings.roles.list.after') !!}

</x-admin::layouts>