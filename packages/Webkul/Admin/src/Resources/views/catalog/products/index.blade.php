<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.products.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.catalog.products.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            {!! view_render_event('bagisto.admin.catalog.products.create.before') !!}

            @if (bouncer()->hasPermission('catalog.products.create'))
                <a
                    href="{{ route('admin.catalog.products.create') }}"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.catalog.products.index.add')
                </a>
            @endif

            {!! view_render_event('bagisto.admin.catalog.products.create.after') !!}
        </div>
    </div>
    
    {!! view_render_event('bagisto.admin.catalog.products.list.before') !!}

    {{-- datagrid will be here --}}

    {!! view_render_event('bagisto.admin.catalog.products.list.after') !!}

</x-admin::layouts>