<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.themes.index.title')
    </x-slot:title>
   
    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.themes.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            <div class="flex gap-x-[10px] items-center">
                {{-- Create Tax Category Button --}}
                <a
                    href="{{ route('admin.theme.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.settings.themes.index.create-btn')
                </a>
            </div>
        </div>
    </div>
    
    <x-admin::datagrid :src="route('admin.theme.index')"></x-admin::datagrid>
    
</x-admin::layouts>