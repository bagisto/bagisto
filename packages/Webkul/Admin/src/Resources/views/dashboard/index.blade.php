<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.dashboard.index.title')
    </x-slot:title>

    <!-- User Detailes Section -->
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        <div class="grid gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.dashboard.index.user-name', ['user_name' => auth()->guard('admin')->user()->name])
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                @lang('admin::app.dashboard.index.user-info')
            </p>
        </div>

        <!-- Actions -->
        <v-dashboard-filters>
            <!-- Shimmer -->
            <div class="flex gap-[6px]">
                <div class="shimmer w-[140px] h-[39px] rounded-[6px]"></div>
                <div class="shimmer w-[140px] h-[39px] rounded-[6px]"></div>
            </div>
        </v-dashboard-filters>
    </div>

    <!-- Body Component -->
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        <!-- Left Section -->
        <div class=" flex flex-col gap-[30px] flex-1 max-xl:flex-auto">
            <!-- Overall Detailes -->
            <div class="flex flex-col gap-[8px]">
                <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.overall-details')
                </p>

                <!-- Over All Details Section -->
                @include('admin::dashboard.over-all-details')
            </div>

            <!-- Todays Details -->
            <div class="flex flex-col gap-[8px]  ">
                <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.today-details')
                </p>

                <!-- Todays Details Section -->
                @include('admin::dashboard.todays-details')
            </div>

            <!-- Stock Thereshold -->
            <div class="flex flex-col gap-[8px]  ">
                <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.stock-threshold')
                </p>

                <!-- Products List -->  
                @include('admin::dashboard.stock-threshold-products')
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full   max-sm:w-full">
            <!-- First Component -->
            <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                @lang('admin::app.dashboard.index.store-stats')
            </p>

            <!-- Store Stats -->
            <div class="rounded-[4px] bg-white dark:bg-gray-900 box-shadow">
                <!-- Total Sales Detailes -->
                @include('admin::dashboard.total-sales')

                <!-- Total Visitors Detailes -->
                @include('admin::dashboard.total-visitors')

                <!-- Top Selling Products -->
                @include('admin::dashboard.top-selling-products')

                <!-- Top Customers -->
                @include('admin::dashboard.top-customers')
            </div>
        </div>
    </div>
    
    @pushOnce('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>

        <script type="text/x-template" id="v-dashboard-filters-template">
            <div class="flex gap-[6px]">
                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.start"
                        placeholder="@lang('admin::app.dashboard.index.start-date')"
                    />
                </x-admin::flat-picker.date>

                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.end"
                        placeholder="@lang('admin::app.dashboard.index.end-date')"
                    />
                </x-admin::flat-picker.date>
            </div>
        </script>

        <script type="module">
            app.component('v-dashboard-filters', {
                template: '#v-dashboard-filters-template',

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
