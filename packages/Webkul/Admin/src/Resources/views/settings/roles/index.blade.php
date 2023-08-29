<x-admin::layouts>

    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.roles.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.roles.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            {{-- Add Role Button --}}
            @if (bouncer()->hasPermission('settings.users.roles.create')) 
                <a 
                    href="{{ route('admin.settings.roles.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.settings.roles.index.create-btn')
                </a>
            @endif
        </div>
    </div>
    
    <x-admin::datagrid src="{{ route('admin.settings.roles.index') }}"></x-admin::datagrid>
</x-admin::layouts>