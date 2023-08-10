<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.customers.reviews.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="py-[11px] text-[20px] text-gray-800 font-bold">
            @lang('admin::app.customers.reviews.index.title')
        </p>

    </div>
    
    <x-admin::datagrid src="{{ route('admin.customer.review.index') }}"></x-admin::datagrid>
</x-admin::layouts>