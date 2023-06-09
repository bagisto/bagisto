{!!view_render_event('bagisto.shop.categories.view.filters.before') !!}

<v-filters
    @filter-applied="setFilters('filter', $event)"
    @filter-clear="clearFilters('filter', $event)"
>
</v-filters>

{!!view_render_event('bagisto.shop.categories.view.filters.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-filters-template">
        <div class="grid grid-cols-[1fr] panel-side max-w-[400px] gap-[20px] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll pr-[26px] min-w-[342px] max-xl:min-w-[270px] max-md:hidden">
            <div class="pb-[10px] border-b-[1px] border-[#E9E9E9] flex justify-between items-center h-[50px]">
                <p class="text-[18px] font-semibold ">
                    <!-- @translations -->
                    @lang('Filters:')
                </p>

                <p class="text-[12px] font-medium cursor-pointer" @click='clear()'>
                    <!-- @translations -->
                    @lang('Clear All')
                </p>
            </div>

            <v-filter-item
                ref="filterItemComponent"
                :key="filterIndex"
                :filter="filter"
                v-for='(filter, filterIndex) in filters.available'
                @values-applied="applyFilter(filter, $event)"
            >
            </v-filter-item>
        </div>
    </script>

    <script type="text/x-template" id="v-filter-item-template">
        <x-shop::accordion>
            <x-slot:header>
                <div class="flex pb-[10px] justify-between items-center">
                    <p
                        class="text-[18px] font-semibold"
                        v-text="filter.name"
                    >
                    </p>
                </div>
            </x-slot:header>

            <x-slot:content>
                <ul v-if="filter.type === 'price'">
                    <li>
                        <x-shop::range-slider></x-shop::range-slider>
                    </li>
                </ul>

                <ul class="pb-3 text-sm text-gray-700" v-else>
                    <li
                        :key="option.id"
                        v-for="(option, optionIndex) in filter.options"
                    >
                        <div class="select-none items-center flex gap-x-[15px] pl-2 rounded hover:bg-gray-100">
                            <input
                                type="checkbox"
                                :id="option.id"
                                class="hidden peer"
                                :value="option.id"
                                v-model="appliedValues"
                                @change="applyValue()"
                            />

                            <label
                                class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check peer-checked:bg-navyBlue peer-checked:rounded-[4px] peer-checked:text-white  cursor-pointer"
                                :for="option.id"
                            ></label>

                            <label
                                :for="option.id"
                                class="w-full text-[16px] text-gray-900 p-2 pl-0 cursor-pointer"
                                v-text="option.name"
                            >
                            </label>
                        </div>
                    </li>
                </ul>
            </x-slot:content>
        </x-shop::accordion>
    </script>

    <script type='module'>
        app.component('v-filters', {
            template: '#v-filters-template',

            data() {
                return {
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
                    this.$axios.get('{{ route("shop.categories.attributes", $category->id) }}')
                        .then((response) => {
                            this.filters.available = response.data.data;
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },

                setFilters() {
                    let queryParams = new URLSearchParams(window.location.search);

                    queryParams.forEach((value, filter) => {
                        /**
                         * Removed all toolbar filters in order to prevent key duplication.
                         */
                        if (! ['sort', 'limit'].includes(filter)) {
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
                        filterItem.$data.appliedValues = [];
                    });

                    this.$emit('filter-applied', this.filters.applied);
                },
            },
        });

        app.component('v-filter-item', {
            template: '#v-filter-item-template',

            props: [
                'filter',
            ],

            data() {
                return {
                    active: true,

                    appliedValues: [],
                }
            },

            mounted() {
                /**
                 * Improvisation needed here. Need to figure out other way and sepration also.
                 */
                this.appliedValues = this.$parent.$data.filters.applied[this.filter.code] ?? [];

                this.getMaxPrice();
            },

            methods: {
                getMaxPrice() {
                    if (this.filter['code'] != 'price') {
                        return;
                    }

                    this.$axios.get('{{ route("shop.categories.max_price", $category->id) }}')
                        .then((response) => {})
                        .catch(error => {
                            console.log(error);
                        });
                },
                
                applyValue() {
                    this.$emit('values-applied', this.appliedValues);
                },
            },
        });
    </script>
@endPushOnce
