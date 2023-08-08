<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.currencies.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.settings.currencies.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            @include('admin::settings.currencies.create')
        </div>
    </div>
    
    {{-- datagrid will be here --}}
</x-admin::layouts>