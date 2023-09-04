<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.families.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.catalog.families.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <a href="{{ route('admin.catalog.families.create') }}">
                <div class="primary-button">
                    @lang('admin::app.catalog.families.index.add')
                </div>
            </a>
        </div>
    </div>

    <x-admin::datagrid src="{{ route('admin.catalog.families.index') }}"></x-admin::datagrid>
</x-admin::layouts>