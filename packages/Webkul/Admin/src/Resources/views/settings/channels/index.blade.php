<x-admin::layouts>

    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.settings.channels.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.channels.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            {{-- Create New Channel Button --}}
            @if (bouncer()->hasPermission('settings.channels.create'))
                <a 
                    href="{{ route('admin.settings.channels.create') }}"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.settings.channels.index.create-btn')
                </a>
            @endif
        </div>
    </div>
    
    <x-admin::datagrid src="{{ route('admin.settings.channels.index') }}"></x-admin::datagrid>

</x-admin::layouts>