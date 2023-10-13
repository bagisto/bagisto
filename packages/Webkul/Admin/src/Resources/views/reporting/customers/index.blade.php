<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.customers.index.title')
    </x-slot:title>

    {{-- Page Header --}}
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        <div class="grid gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.reporting.customers.index.title')
            </p>
        </div>
    </div>
</x-admin::layouts>