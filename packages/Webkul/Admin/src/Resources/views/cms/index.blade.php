<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.cms.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.cms.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            {{-- Create New Pages Button --}}
            @if (bouncer()->hasPermission('cms.pages.create'))
                <a 
                    href="{{ route('admin.cms.create') }}" 
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.cms.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    <x-admin::datagrid src="{{ route('admin.cms.index') }}"></x-admin::datagrid>
</x-admin::layouts>