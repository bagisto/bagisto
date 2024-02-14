<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.dashboard.index.title')
    </x-slot>

    <!-- User Detailes Section -->
    <div class="flex gap-4 justify-between items-center mb-5 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <p class="text-xl text-gray-800 dark:text-white font-bold !leading-normal">
                @lang('admin::app.dashboard.index.user-name', ['user_name' => auth()->guard('admin')->user()->name])
            </p>

            <p class="text-gray-600 !leading-normal dark:text-gray-300">
                @lang('admin::app.dashboard.index.user-info')
            </p>
        </div>

        <!-- Actions -->
        <v-dashboard-filters>
            <!-- Shimmer -->
            <div class="flex gap-1.5">
                <div class="shimmer w-[140px] h-[39px] rounded-md"></div>
                <div class="shimmer w-[140px] h-[39px] rounded-md"></div>
            </div>
        </v-dashboard-filters>
    </div>

    <!-- Body Component -->
    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
        <!-- Left Section -->
        <div class="flex flex-col gap-8 flex-1 max-xl:flex-auto">
            {!! view_render_event('bagisto.admin.dashboard.overall_detailes.before') !!}

            <!-- Overall Detailes -->
            <div class="flex flex-col gap-2">
                <p class="text-base text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.overall-details')
                </p>

                <!-- Over All Details Section -->
                @include('admin::dashboard.over-all-details')
            </div>

            {!! view_render_event('bagisto.admin.dashboard.overall_detailes.after') !!}

            {!! view_render_event('bagisto.admin.dashboard.todays_detailes.before') !!}

            <!-- Todays Details -->
            <div class="flex flex-col gap-2">
                <p class="text-base text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.today-details')
                </p>

                <!-- Todays Details Section -->
                @include('admin::dashboard.todays-details')
            </div>

            {!! view_render_event('bagisto.admin.dashboard.todays_detailes.after') !!}

            {!! view_render_event('bagisto.admin.dashboard.stock_thereshold.before') !!}
            <!-- Stock Thereshold -->
            <div class="flex flex-col gap-2">
                <p class="text-base text-gray-600 dark:text-gray-300 font-semibold">
                    @lang('admin::app.dashboard.index.stock-threshold')
                </p>

                <!-- Products List -->  
                @include('admin::dashboard.stock-threshold-products')
            </div>
            {!! view_render_event('bagisto.admin.dashboard.stock_thereshold.after') !!}
        </div>

        <!-- Right Section -->
        <div class="flex flex-col gap-2 w-[360px] max-w-full   max-sm:w-full">
            <!-- First Component -->
            <p class="text-base text-gray-600 dark:text-gray-300 font-semibold">
                @lang('admin::app.dashboard.index.store-stats')
            </p>

            {!! view_render_event('bagisto.admin.dashboard.store_stats.before') !!}

            <!-- Store Stats -->
            <div class="rounded bg-white dark:bg-gray-900 box-shadow">
                <!-- Total Sales Detailes -->
                @include('admin::dashboard.total-sales')

                <!-- Total Visitors Detailes -->
                @include('admin::dashboard.total-visitors')

                <!-- Top Selling Products -->
                @include('admin::dashboard.top-selling-products')

                <!-- Top Customers -->
                @include('admin::dashboard.top-customers')
            </div>

            {!! view_render_event('bagisto.admin.dashboard.store_stats.after') !!}
        </div>
    </div>
    
    @pushOnce('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>

        <script type="text/x-template" id="v-dashboard-filters-template">
            <div class="flex gap-1.5">
                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.start"
                        placeholder="@lang('admin::app.dashboard.index.start-date')"
                    />
                </x-admin::flat-picker.date>

                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
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
