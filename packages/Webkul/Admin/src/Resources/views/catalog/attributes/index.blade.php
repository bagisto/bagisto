<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.attributes.index.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-[16px] max-sm:flex-wrap">
        {{-- Title --}}
        <p class="text-[20px] font-bold text-gray-800">
            @lang('admin::app.catalog.attributes.index.title')
        </p>

        <div class="flex items-center gap-x-[10px]">
            <a href="{{ route('admin.catalog.attributes.create') }}">
                <div class="cursor-pointer rounded-[6px] border border-blue-700 bg-blue-600 px-[12px] py-[6px] font-semibold text-gray-50">
                    @lang('admin::app.catalog.attributes.index.create-btn')
                </div>
            </a>
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.catalog.attributes.index')"></x-admin::datagrid>
</x-admin::layouts>
