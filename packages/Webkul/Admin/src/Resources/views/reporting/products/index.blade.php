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
    </div>

    {{-- Customers Stats Vue Component --}}
    <v-reporting-products>
        <x-admin::shimmer.reporting.customers/>
    </v-reporting-products>

    @pushOnce('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>

        <script type="text/x-template" id="v-reporting-products-template">
            <div class="flex flex-col gap-[15px] flex-1 max-xl:flex-auto">
                <!-- Total Sold Quantities and Products Added to Wishlist Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Total Sold Quantities Section -->
                    @include('admin::reporting.products.sold-quantities')

                    <!-- Products Added to Wishlist Section -->
                    @include('admin::reporting.products.wishlist-products')
                </div>

                <!-- Top Selling Products By Revenue and Top Selling Products By Quantity Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Top Selling Products By Revenue Section -->
                    @include('admin::reporting.products.top-selling-by-revenue')

                    <!-- Top Selling Products By Quantity Section -->
                    @include('admin::reporting.products.top-selling-by-quantity')
                </div>

                <!-- Products With Most Reviews and Products Wiht Most Visits Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Products With Most Reviews Section -->
                    @include('admin::reporting.products.most-reviews')

                    <!-- Products Wiht Most Visits Section -->
                    @include('admin::reporting.products.most-visits')
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-reporting-products', {
                template: '#v-reporting-products-template',

                data() {
                    return {
                        charts: {}
                    }
                },

                methods: {
                    prepareChart(type, stats) {
                        if (this.charts[type]) {
                           this.charts[type].destroy();
                        }

                        this.charts[type] = new Chart(document.getElementById(type), {
                            type: 'line',
                            
                            data: {
                                labels: stats['current']['label'],

                                datasets: [{
                                    data: stats['current']['total'],
                                    lineTension: 0.2,
                                    pointStyle: false,
                                    borderWidth: 2,
                                    borderColor: '#0E9CFF',
                                    backgroundColor: 'rgba(14, 156, 255, 0.3)',
                                    fill: true,
                                }, {
                                    data: stats['previous']['total'],
                                    lineTension: 0.2,
                                    pointStyle: false,
                                    borderWidth: 2,
                                    borderColor: '#34D399',
                                    backgroundColor: 'rgba(52, 211, 153, 0.3)',
                                    fill: true,
                                }],
                            },
                    
                            options: {
                                aspectRatio: 3.17,
                                
                                plugins: {
                                    legend: {
                                        display: false
                                    },

                                    {{-- tooltip: {
                                        enabled: false,
                                    } --}}
                                },
                                
                                scales: {
                                    x: {
                                        beginAtZero: true,

                                        border: {
                                            dash: [8, 4],
                                        }
                                    },

                                    y: {
                                        beginAtZero: true,
                                        border: {
                                            dash: [8, 4],
                                        }
                                    }
                                }
                            }
                        });
                    },

                    resetLoading() {
                        for (let key in this.isLoading) {
                            this.isLoading[key] = false;
                        }
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
