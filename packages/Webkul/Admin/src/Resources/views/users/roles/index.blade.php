<x-admin::layouts>

    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.users.roles.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.users.roles.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            {{-- Add Role Button --}}
            @if (bouncer()->hasPermission('settings.users.roles.create')) 
                <a 
                    href="{{ route('admin.settings.users.roles.create') }}"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.users.roles.index.create-btn')
                </a>
            @endif
        </div>
    </div>
    
    <x-admin::datagrid :src="route('admin.settings.users.roles.index')"></x-admin::datagrid>
</x-admin::layouts>