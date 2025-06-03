{!! view_render_event('bagisto.shop.categories.view.filters.before') !!}

<!-- Desktop Filters Navigation -->
<div v-if="! isMobile">
    <!-- Filters Vue Component -->
    <v-filters
        @filter-applied="setFilters('filter', $event)"
        @filter-clear="clearFilters('filter', $event)"
    >
        <!-- Category Filter Shimmer Effect -->
        <x-shop::shimmer.categories.filters />
    </v-filters>
</div>

<!-- Mobile Filters Navigation -->
<div
    class="fixed bottom-0 z-10 grid w-full max-w-full grid-cols-[1fr_auto_1fr] items-center justify-items-center border-t border-zinc-200 bg-white px-5 ltr:left-0 rtl:right-0"
    v-if="isMobile"
>
    <!-- Filter Drawer -->
    <x-shop::drawer
        position="left"
        width="100%"
        ::is-active="isDrawerActive.filter"
    >
        <!-- Drawer Toggler -->
        <x-slot:toggle>
            <div
                class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase max-md:py-3"
                @click="isDrawerActive.filter = true"
            >
                <span class="icon-filter-1 text-2xl"></span>

                @lang('shop::app.categories.filters.filter')
            </div>
        </x-slot>

        <!-- Drawer Header -->
        <x-slot:header>
            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold">
                    @lang('shop::app.categories.filters.filters')
                </p>

                <p
                    class="cursor-pointer text-sm font-medium ltr:mr-[50px] rtl:ml-[50px]"
                    @click="clearFilters('filter', '')"
                >
                    @lang('shop::app.categories.filters.clear-all')
                </p>
            </div>
        </x-slot>

        <!-- Drawer Content -->
        <x-slot:content>
            <!-- Filters Vue Component -->
            <v-filters
                @filter-applied="setFilters('filter', $event)"
                @filter-clear="clearFilters('filter', $event)"
            >
                <!-- Category Filter Shimmer Effect -->
                <x-shop::shimmer.categories.filters />
            </v-filters>
        </x-slot>
    </x-shop::drawer>

    <!-- Separator -->
    <span class="h-5 w-0.5 bg-zinc-200"></span>

    <!-- Sort Drawer -->
    <x-shop::drawer
        position="bottom"
        width="100%"
        ::is-active="isDrawerActive.toolbar"
    >
        <!-- Drawer Toggler -->
        <x-slot:toggle>
            <div
                class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase max-md:py-3"
                @click="isDrawerActive.toolbar = true"
            >
                <span class="icon-sort-1 text-2xl"></span>

                @lang('shop::app.categories.filters.sort')
            </div>
        </x-slot>

        <!-- Drawer Header -->
        <x-slot:header>
            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold">
                    @lang('shop::app.categories.filters.sort')
                </p>
            </div>
        </x-slot>

        <!-- Drawer Content -->
        <x-slot:content class="!px-0">
            @include('shop::categories.toolbar')
        </x-slot>
    </x-shop::drawer>
</div>

{!! view_render_event('bagisto.shop.categories.view.filters.after') !!}

@pushOnce('scripts')
    <!-- Filters Vue template -->
    <script
        type="text/x-template"
        id="v-filters-template"
    >
        <!-- Filter Shimmer Effect -->
        <template v-if="isLoading">
            <x-shop::shimmer.categories.filters />
        </template>

        <!-- Filters Container -->
        <template v-else>
            <div class="panel-side journal-scroll grid max-h-[1320px] min-w-[342px] grid-cols-[1fr] overflow-y-auto overflow-x-hidden max-xl:min-w-[270px] md:max-w-[342px] md:ltr:pr-7 md:rtl:pl-7">
                <!-- Filters Header Container -->
                <div class="flex h-[50px] items-center justify-between border-b border-zinc-200 pb-2.5 max-md:hidden">
                    <p class="text-lg font-semibold max-sm:font-medium">
                        @lang('shop::app.categories.filters.filters')
                    </p>

                    <p
                        class="cursor-pointer text-xs font-medium"
                        tabindex="0"
                        @click="clear()"
                    >
                        @lang('shop::app.categories.filters.clear-all')
                    </p>
                </div>

                <!-- Filters Items Vue Component -->
                <v-filter-item
                    ref="filterItemComponent"
                    :key="filterIndex"
                    :filter="filter"
                    v-for='(filter, filterIndex) in filters.available'
                    @values-applied="applyFilter(filter, $event)"
                >
                </v-filter-item>
            </div>
        </template>
    </script>

    <!-- Filter Item Vue template -->
    <script
        type="text/x-template"
        id="v-filter-item-template"
    >
        <x-shop::accordion class="last:border-b-0">
            <!-- Filter Item Header -->
            <x-slot:header class="px-0 py-2.5 max-sm:!pb-1.5">
                <div class="flex items-center justify-between">
                    <p class="text-lg font-semibold max-sm:text-base max-sm:font-medium">
                        @{{ filter.name }}
                    </p>
                </div>
            </x-slot>

            <!-- Filter Item Content -->
            <x-slot:content class="!p-0">
                <!-- Price Range Filter -->
                <ul v-if="filter.type === 'price'">
                    <li>
                        <v-price-filter
                            :key="refreshKey"
                            :default-price-range="appliedValues"
                            @set-price-range="applyValue($event)"
                        >
                        </v-price-filter>
                    </li>
                </ul>

                <!-- Checkbox Filter Options -->
                <template v-else>
                    <!-- Search Box For Options -->
                    <div
                        class="flex flex-col gap-1"
                        v-if="filter.type !== 'boolean'"
                    >
                        <div class="relative">
                            <div class="icon-search pointer-events-none absolute top-3 flex items-center text-2xl max-md:text-xl max-sm:top-2.5 ltr:left-3 rtl:right-3"></div>

                            <input
                                type="text"
                                class="block w-full rounded-xl border border-zinc-200 px-11 py-3.5 text-sm font-medium text-gray-900 max-md:rounded-lg max-md:px-10 max-md:py-3 max-md:font-normal max-sm:text-xs"
                                placeholder="@lang('shop::app.categories.filters.search.title')"
                                v-model="searchQuery"
                                v-debounce:500="searchOptions"
                            />
                        </div>

                        <p
                            class="mt-1 flex flex-row-reverse text-xs text-gray-600"
                            v-text="
                                '@lang('shop::app.categories.filters.search.results-info', ['currentCount' => 'currentCount', 'totalCount' => 'totalCount'])'
                                    .replace('currentCount', options.length)
                                    .replace('totalCount', meta.total)
                            "
                            v-if="meta && meta.total > 0"
                        >
                        </p>
                    </div>

                    <!-- Filter Options -->
                    <ul class="pb-3 text-base text-gray-700">
                        <template v-if="options.length">
                            <li
                                :key="`${filter.id}_${option.id}`"
                                v-for="(option, optionIndex) in options"
                            >
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2">
                                    <input
                                        type="checkbox"
                                        :id="`filter_${filter.id}_option_ ${option.id}`"
                                        class="peer hidden"
                                        :value="option.id"
                                        v-model="appliedValues"
                                        @change="applyValue"
                                    />

                                    <label
                                        class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl"
                                        role="checkbox"
                                        aria-checked="false"
                                        :aria-label="option.name"
                                        :aria-labelledby="'label_option_' + option.id"
                                        tabindex="0"
                                        :for="`filter_${filter.id}_option_ ${option.id}`"
                                    >
                                    </label>

                                    <label
                                        class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0"
                                        :id="'label_option_' + option.id"
                                        :for="`filter_${filter.id}_option_ ${option.id}`"
                                        role="button"
                                        tabindex="0"
                                    >
                                        @{{ option.name }}
                                    </label>
                                </div>
                            </li>
                        </template>

                        <template v-else>
                            <li
                                class="flex flex-col items-center justify-center gap-2 py-2"
                                v-if="! isLoadingMore"
                            >
                                @lang('shop::app.categories.filters.search.no-options-available')
                            </li>

                            <div
                                class="mt-2"
                                v-else
                            >
                                <div class="flex flex-col items-center justify-between">
                                    <div class="shimmer h-5 w-[50%] self-end rounded"></div>
                                </div>

                                <div class="z-10 grid gap-1 rounded-lg bg-white">
                                    <div class="flex items-center gap-x-4 ltr:pl-2 rtl:pr-2">
                                        <div class="shimmer h-5 w-5 rounded"></div>

                                        <div class="p-2 ltr:pl-0 rtl:pr-0">
                                            <div class="shimmer h-5 w-[100px]"></div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-x-4 rounded ltr:pl-2 rtl:pr-2">
                                        <div class="shimmer h-5 w-5 rounded"></div>

                                        <div class="p-2 ltr:pl-0 rtl:pr-0">
                                            <div class="shimmer h-5 w-[100px]"></div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-x-4 rounded ltr:pl-2 rtl:pr-2">
                                        <div class="shimmer h-5 w-5 rounded"></div>

                                        <div class="p-2 ltr:pl-0 rtl:pr-0">
                                            <div class="shimmer h-5 w-[100px]"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ul>

                    <!-- Load More Button -->
                    <div class="flex justify-center pb-3" v-if="meta && meta.current_page < meta.last_page">
                        <button
                            type="button"
                            class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            @click="loadMoreOptions"
                            :disabled="isLoadingMore"
                        >
                            <span v-if="isLoadingMore">
                                @lang('shop::app.categories.filters.search.loading')
                            </span>

                            <span v-else>
                                @lang('shop::app.categories.filters.search.load-more')
                            </span>
                        </button>
                    </div>
                </template>
            </x-slot>
        </x-shop::accordion>
    </script>

    <script
        type="text/x-template"
        id="v-price-filter-template"
    >
        <div>
            <!-- Price Range Filter Shimmer -->
            <template v-if="isLoading">
                <x-shop::shimmer.range-slider />
            </template>

            <template v-else>
                <x-shop::range-slider
                    ::key="refreshKey"
                    default-type="price"
                    ::default-allowed-max-range="allowedMaxPrice"
                    ::default-min-range="minRange"
                    ::default-max-range="maxRange"
                    @change-range="setPriceRange($event)"
                />
            </template>
        </div>
    </script>

    <script type='module'>
        app.component('v-filters', {
            template: '#v-filters-template',

            data() {
                return {
                    isLoading: true,

                    filters: {
                        available: {},

                        applied: {},
                    },
                };
            },

            mounted() {
                this.getFilters();

                this.setFilters();
            },

            methods: {
                getFilters() {
                    this.$axios.get('{{ route("shop.api.categories.attributes") }}', {
                            params: {
                                category_id: "{{ isset($category) ? $category->id : ''  }}",
                            }
                        })
                        .then((response) => {
                            this.isLoading = false;

                            this.filters.available = response.data.data;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },

                setFilters() {
                    let queryParams = new URLSearchParams(window.location.search);

                    queryParams.forEach((value, filter) => {
                        /**
                         * Removed all toolbar filters in order to prevent key duplication.
                         */
                        if (! ['sort', 'limit', 'mode'].includes(filter)) {
                            this.filters.applied[filter] = value.split(',');
                        }
                    });

                    this.$emit('filter-applied', this.filters.applied);
                },

                applyFilter(filter, values) {
                    if (values.length) {
                        this.filters.applied[filter.code] = values;
                    } else {
                        delete this.filters.applied[filter.code];
                    }

                    this.$emit('filter-applied', this.filters.applied);
                },

                clear() {
                    /**
                     * Clearing parent component.
                     */
                    this.filters.applied = {};

                    /**
                     * Clearing child components. Improvisation needed here.
                     */
                    this.$refs.filterItemComponent.forEach((filterItem) => {
                        if (filterItem.filter.code === 'price') {
                            filterItem.$data.appliedValues = null;
                        } else {
                            filterItem.$data.appliedValues = [];
                        }
                    });

                    this.$emit('filter-applied', this.filters.applied);
                },
            },
        });

        app.component('v-filter-item', {
            template: '#v-filter-item-template',

            props: ['filter'],

            data() {
                return {
                    options: [],

                    meta: null,

                    appliedValues: null,

                    currentPage: 1,

                    searchQuery: '',

                    isLoadingMore: true,

                    refreshKey: 0,
                }
            },

            watch: {
                appliedValues() {
                    if (this.filter.code === 'price' && ! this.appliedValues) {
                        ++this.refreshKey;
                    }
                },
            },

            mounted() {
                this.fetchFilterOptions();

                if (this.filter.code === 'price') {
                    /**
                     * Improvisation needed here for `this.$parent.$data`.
                     */
                    this.appliedValues = this.$parent.$data.filters.applied[this.filter.code]?.join(',');

                    ++this.refreshKey;

                    return;
                }

                /**
                 * Improvisation needed here for `this.$parent.$data`.
                 */
                this.appliedValues = this.$parent.$data.filters.applied[this.filter.code] ?? [];
            },

            methods: {
                applyValue($event) {
                    if (this.filter.code === 'price') {
                        this.appliedValues = $event;

                        this.$emit('values-applied', this.appliedValues);

                        return;
                    }

                    this.$emit('values-applied', this.appliedValues);
                },

                /**
                 * Search options based on query
                 */
                searchOptions() {
                    this.currentPage = 1;

                    this.fetchFilterOptions(true);
                },

                /**
                 * Load more options when "Load more" button is clicked
                 */
                loadMoreOptions() {
                    this.currentPage++;

                    this.fetchFilterOptions(false);
                },

                fetchFilterOptions(replace = true) {
                    this.isLoadingMore = true;

                    const url = `{{ route("shop.api.categories.attribute_options", 'attribute_id') }}`.replace('attribute_id', this.filter.id);

                    this.$axios.get(url, {
                        params: {
                            page: this.currentPage,
                            search: this.searchQuery,
                        }
                    })
                    .then(response => {
                        this.isLoadingMore = false;

                        this.options = replace
                            ? response.data.data
                            : [...this.options, ...response.data.data];

                        this.meta = response.data.meta;
                    })
                    .catch(error => {
                        this.isLoadingMore = false;
                    });
                },
            },
        });

        app.component('v-price-filter', {
            template: '#v-price-filter-template',

            props: ['defaultPriceRange'],

            data() {
                return {
                    refreshKey: 0,

                    isLoading: true,

                    allowedMaxPrice: 100,

                    priceRange: this.defaultPriceRange ?? [0, 100].join(','),
                };
            },

            computed: {
                minRange() {
                    let priceRange = this.priceRange.split(',');

                    return priceRange[0];
                },

                maxRange() {
                    let priceRange = this.priceRange.split(',');

                    return priceRange[1];
                }
            },

            mounted() {
                this.getMaxPrice();
            },

            methods: {
                getMaxPrice() {
                    this.$axios.get('{{ route("shop.api.categories.max_price", $category->id ?? '') }}')
                        .then((response) => {
                            this.isLoading = false;

                            /**
                             * If data is zero, then default price will be displayed.
                             */
                            if (response.data.data.max_price) {
                                this.allowedMaxPrice = response.data.data.max_price;
                            }

                            if (! this.defaultPriceRange) {
                                this.priceRange = [0, this.allowedMaxPrice].join(',');
                            }

                            ++this.refreshKey;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },

                setPriceRange($event) {
                    this.priceRange = [$event.minRange, $event.maxRange].join(',');

                    this.$emit('set-price-range', this.priceRange);
                },
            },
        });
    </script>
@endPushOnce
