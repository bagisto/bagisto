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

    {{-- Customers Stats Vue Component --}}
    <div class="flex flex-col gap-[15px] flex-1 max-xl:flex-auto">
        <!-- Customers Section -->
        @include('admin::reporting.customers.total-customers')

        <!-- Customers With Most Sales and Customers With Most Orders Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Customers With Most Sales Section -->
            @include('admin::reporting.customers.most-sales')

            <!-- Customers With Most Orders Section -->
            @include('admin::reporting.customers.most-orders')
        </div>

        <!-- Customers Traffic Section -->
        @include('admin::reporting.customers.total-traffic')

        <!-- Top Customer Groups Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Top Customer Groups Section -->
            @include('admin::reporting.customers.top-customer-groups')

            <!-- Customer with Most Reviews Section -->
            @include('admin::reporting.customers.most-reviews')
        </div>
    </div>

    @pushOnce('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>
    @endPushOnce
</x-admin::layouts>
