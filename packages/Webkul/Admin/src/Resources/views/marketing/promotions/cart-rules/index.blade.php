<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.promotions.cart-rules.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center mt-3 max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.promotions.cart-rules.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            @if (bouncer()->hasPermission('marketing.promotions.cart-rules.create'))
                <a 
                    href="{{ route('admin.cart_rules.create') }}"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.promotions.cart-rules.index.create-btn')
                </a>
            @endif
        </div>
    </div>
    
    {{-- datagrid will be here --}}
</x-admin::layouts>