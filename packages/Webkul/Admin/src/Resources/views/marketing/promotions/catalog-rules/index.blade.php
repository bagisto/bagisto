<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.catalog-rules.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center mt-3 max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.marketing.promotions.catalog-rules.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <a 
                href="{{ route('admin.marketing.promotions.catalog_rules.create') }}"
                class="primary-button"
            >
                @lang('admin::app.marketing.promotions.catalog-rules.index.create-btn')
            </a>
        </div>
    </div>
    
    <x-admin::datagrid src="{{ route('admin.marketing.promotions.catalog_rules.index') }}"></x-admin::datagrid>
</x-admin::layouts>