<x-admin::layouts>
    <x-slot:title>
        Theme Customization
    </x-slot:title>
   
    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            Theme Customization
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            <div class="flex gap-x-[10px] items-center">
                {{-- Create Tax Category Button --}}
                <a
                    href="{{ route('admin.theme.create') }}"
                    class="primary-button"
                >
                    Create Theme
                </a>
            </div>
        </div>
    </div>
    
    <x-admin::datagrid :src="route('admin.theme.index')"></x-admin::datagrid>
    
</x-admin::layouts>