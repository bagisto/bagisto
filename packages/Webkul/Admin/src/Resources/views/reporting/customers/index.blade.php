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
    <v-reporting-customers>
        <x-admin::shimmer.reporting.customers/>
    </v-reporting-customers>

    @pushOnce('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>

        <script type="text/x-template" id="v-reporting-customers-template">
            <div class="flex flex-col gap-[15px] flex-1 max-xl:flex-auto">
                <!-- Customers Section -->
                @include('admin::reporting.customers.total-customers')

                <!-- Customers With Most Sales and Customers With Most Orders Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Customers With Most Sales Section -->
                    @include('admin::reporting.customers.most-sales')

                    <!-- Customers With Most Orders Section -->
                    @include('admin::reporting.customers.most-orders')
                </div>

                <!-- Customers Traffic Section -->
                @include('admin::reporting.customers.total-traffic')

                <!-- Top Customer Groups Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Top Customer Groups Section -->
                    @include('admin::reporting.customers.top-customer-groups')

                    <!-- Customer with Most Reviews Section -->
                    @include('admin::reporting.customers.most-reviews')
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-reporting-customers', {
                template: '#v-reporting-customers-template',

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
                                labels: stats['current'].map(({ label }) => label),

                                datasets: [{
                                    data: stats['current'].map(({ total }) => total),
                                    lineTension: 0.2,
                                    pointStyle: false,
                                    borderWidth: 2,
                                    borderColor: '#0E9CFF',
                                    backgroundColor: 'rgba(14, 156, 255, 0.3)',
                                    fill: true,
                                }, {
                                    data: stats['previous'].map(({ total }) => total),
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
