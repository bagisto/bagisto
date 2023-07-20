<x-admin::layouts>
    @include ('admin::layouts.tabs')

    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.email-marketing.newsletters.title')
    </x-slot:title>

    <div class="mt-5">
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.email-marketing.newsletters.title')
            </p>
        </div>
    
        <div class="page-content">
            <datagrid-plus src="{{ route('admin.customers.subscribers.index') }}"></datagrid-plus>
        </div>
    </div>
</x-admin::layouts>