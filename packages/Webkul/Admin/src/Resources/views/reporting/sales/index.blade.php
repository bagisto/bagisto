<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.sales.index.title')
    </x-slot:title>

    {{-- Page Header --}}
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        {{-- Title --}}
        <div class="flex gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.reporting.sales.index.title')
            </p>
        </div>

        <!-- Actions -->
        <v-reporting-filters>
            {{-- Shimmer --}}
            <div class="flex gap-[6px]">
                <div class="shimmer w-[140px] h-[39px] rounded-[6px]"></div>
                <div class="shimmer w-[140px] h-[39px] rounded-[6px]"></div>
            </div>
        </v-reporting-filters>
    </div>

    {{-- Sales Stats Vue Component --}}
    <div class="flex flex-col gap-[15px] flex-1 max-xl:flex-auto">
        <!-- Sales Section -->
        @include('admin::reporting.sales.total-sales')

        <!-- Purchase Funnel and Abandoned Carts Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Purchase Funnel Section -->
            @include('admin::reporting.sales.purchase-funnel')

            <!-- Abandoned Carts Section -->
            @include('admin::reporting.sales.abandoned-carts')
        </div>

        <!-- Total Orders and Average Order Value Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Total Orders Section -->
            @include('admin::reporting.sales.total-orders')

            <!-- Average Order Value Section -->
            @include('admin::reporting.sales.average-order-value')
        </div>

        <!-- Tax Collected and Shipping Collected Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Tax Collected Section -->
            @include('admin::reporting.sales.tax-collected')

            <!-- Shipping Collected Section -->
            @include('admin::reporting.sales.shipping-collected')
        </div>

        <!-- Refunds and Top Payment Methods Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Refunds Section -->
            @include('admin::reporting.sales.total-refunds')

            <!-- Top Payment Methods Section -->
            @include('admin::reporting.sales.top-payment-methods')
        </div>
    </div>

    @pushOnce('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>

        <script type="text/x-template" id="v-reporting-filters-template">
            <div class="flex gap-[6px]">
                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.start"
                        placeholder="@lang('admin::app.reporting.sales.index.start-date')"
                    />
                </x-admin::flat-picker.date>

                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.end"
                        placeholder="@lang('admin::app.reporting.sales.index.end-date')"
                    />
                </x-admin::flat-picker.date>
            </div>
        </script>

        <script type="module">
            app.component('v-reporting-filters', {
                template: '#v-reporting-filters-template',

                data() {
                    return {
                        filters: {
                            start: "{{ $startDate->format('Y-m-d') }}",
                            
                            end: "{{ $endDate->format('Y-m-d') }}",
                        }
                    }
                },

                watch: {
                    filters: {
                        handler() {
                            this.$emitter.emit('reporting-filter-updated', this.filters);
                        },

                        deep: true
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
