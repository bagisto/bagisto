<!-- Abandoned Carts Vue Component -->
<v-reporting-sales-abandoned-carts>
    <!-- Shimmer -->
    <x-admin::shimmer.reporting.sales.abandoned-carts />
</v-reporting-sales-abandoned-carts>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-sales-abandoned-carts-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.sales.abandoned-carts />
        </template>

        <!-- Abandoned Carts Section -->
        <template v-else>
            <div class="flex-1 relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-base text-gray-600 dark:text-white font-semibold">
                        @lang('admin::app.reporting.sales.index.abandoned-carts')
                    </p>

                    <a
                        href="{{ route('admin.reporting.sales.view', ['type' => 'abandoned-carts']) }}"
                        class="text-sm text-blue-600 cursor-pointer transition-all hover:underline"
                    >
                        @lang('admin::app.reporting.sales.index.view-details')
                    </a>
                </div>

                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Stats -->
                    <div class="flex gap-4 justify-between">
                        <!-- Abandoned Revenue -->
                        <div class="grid gap-1">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.sales.formatted_total }}
                            </p>

                            <p class="text-xs text-gray-600 leading-none dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.sales.index.abandoned-revenue')
                            </p>
                            
                            <div class="flex gap-0.5 items-center">
                                <span
                                    class="text-base  text-emerald-500"
                                    :class="[report.statistics.sales.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-base  text-emerald-500"
                                    :class="[report.statistics.sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.sales.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>

                        <!-- Abandoned Cart -->
                        <div class="grid gap-1">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.carts.current }}
                            </p>

                            <p class="text-xs text-gray-600 leading-none dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.sales.index.abandoned-carts')
                            </p>
                            
                            <div class="flex gap-0.5 items-center">
                                <span
                                    class="text-base  text-emerald-500"
                                    :class="[report.statistics.carts.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-base  text-emerald-500"
                                    :class="[report.statistics.carts.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.carts.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>

                        <!-- Abandoned Rate -->
                        <div class="grid gap-1">
                            <div class="flex gap-0.5">
                                <p
                                    class="text-base  text-emerald-500 leading-none"
                                    :class="[report.statistics.rate.progress >= 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.rate.current.toFixed(2)) }}%
                                </p>

                                <span
                                    class="text-base text-emerald-500 leading-none"
                                    :class="[report.statistics.carts.progress >= 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>
                            </div>

                            <p class="text-xs text-gray-600 leading-none dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.sales.index.abandoned-rate')
                            </p>
                            
                            <div class="flex gap-0.5 items-center">
                                <p
                                    class="text-base text-emerald-500 leading-none"
                                    :class="[report.statistics.rate.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.rate.progress.toFixed(2)) }}%
                                </p>

                                <span
                                    class="text-base text-emerald-500 leading-none"
                                    :class="[report.statistics.carts.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>
                            </div>
                        </div>
                    </div>

                    <!-- Header -->
                    <p class="py-2.5 text-base text-gray-600 dark:text-white font-semibold mt-4">
                        @lang('admin::app.reporting.sales.index.abandoned-products')
                    </p>

                    <!-- Abandoned Products -->
                    <template v-if="report.statistics.products.length">
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="product in report.statistics.products"
                            >
                                <p class="dark:text-white">@{{ product.name }}</p>

                                <div class="flex gap-5 items-center">
                                    <div class="w-full h-2 relative bg-slate-100">
                                        <div
                                            class="h-2 absolute left-0 bg-blue-500"
                                            :style="{ 'width': product.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm text-gray-600 dark:text-gray-300 font-semibold">
                                        @{{ product.count }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template v-else>
                        @include('admin::reporting.empty')
                    </template>

                    <!-- Date Range -->
                    <div class="flex gap-5 justify-end">
                        <div class="flex gap-1 items-center">
                            <span class="w-3.5 h-3.5 rounded-md bg-blue-500"></span>

                            <p class="text-xs dark:text-gray-300">
                                @{{ report.date_range.current }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-reporting-sales-abandoned-carts', {
            template: '#v-reporting-sales-abandoned-carts-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filtets) {
                    this.isLoading = true;

                    var filtets = Object.assign({}, filtets);

                    filtets.type = 'abandoned-carts';

                    this.$axios.get("{{ route('admin.reporting.sales.stats') }}", {
                            params: filtets
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>
@endPushOnce