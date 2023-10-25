<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.products.index.title')
    </x-slot:title>

    {{-- Page Header --}}
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        <div class="grid gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.reporting.products.index.title')
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

    {{-- Products Stats Vue Component --}}
    <div class="flex flex-col gap-[15px] flex-1 max-xl:flex-auto">
        <!-- Total Sold Quantities and Products Added to Wishlist Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Total Sold Quantities Section -->
            @include('admin::reporting.products.sold-quantities')

            <!-- Products Added to Wishlist Section -->
            @include('admin::reporting.products.wishlist-products')
        </div>

        <!-- Top Selling Products By Revenue and Top Selling Products By Quantity Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Top Selling Products By Revenue Section -->
            @include('admin::reporting.products.top-selling-by-revenue')

            <!-- Top Selling Products By Quantity Section -->
            @include('admin::reporting.products.top-selling-by-quantity')
        </div>

        <!-- Products With Most Reviews and Products Wiht Most Visits Sections Container -->
        <div class="flex justify-between gap-[15px] flex-1 [&>*]:flex-1 max-xl:flex-auto">
            <!-- Products With Most Reviews Section -->
            @include('admin::reporting.products.most-reviews')

            <!-- Products Wiht Most Visits Section -->
            @include('admin::reporting.products.most-visits')
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
                        placeholder="@lang('admin::app.reporting.products.index.start-date')"
                    />
                </x-admin::flat-picker.date>

                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        v-model="filters.end"
                        placeholder="@lang('admin::app.reporting.products.index.end-date')"
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
