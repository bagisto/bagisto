<div class="layered-filter-wrapper">
    {!!view_render_event('bagisto.shop.categories.view.filters.before') !!}

    <v-filters @onFilterApplied='setFilters("filter", $event)'></v-filters>

    {!!view_render_event('bagisto.shop.categories.view.filters.after') !!}
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-filters-template">
        <div class="grid grid-cols-[1fr] panel-side max-w-[400px] gap-[20px] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll pr-[26px] min-w-[342px] max-xl:min-w-[270px] max-md:hidden">
            <div class="pb-[10px] border-b-[1px] border-[#E9E9E9] flex justify-between items-center h-[50px]">
                <p class="text-[18px] font-semibold ">Filters:</p>
                <p class="text-[12px] font-medium" @click='clear()'>Clear All</p>
            </div>

            <v-filter-item
                v-for='(filter, index) in filters.available'
                :key="index"
                :index="index"
                :filter="filter"
                :appliedFilterValues="filters.applied[filter.code]"
                @onFilterAdded="applyFilter($event, filter.code)"
            >
            </v-filter-item>
        </div>
    </script>

    <script type="text/x-template" id="v-filter-item-template">
        <div class="border-b-[1px] border-[#E9E9E9]">
            <div
                :class="`flex pb-[10px] justify-between items-center cursor-pointer select-none ${active ? 'active' : ''}`"
                @click="active = ! active"
            >
                <div class="flex pb-[10px] justify-between items-center">
                    <p class="text-[18px] font-semibold ">@{{ filter.name }}</p>
                </div>

                <span :class="`text-[24px] ${active ? 'icon-arrow-up' : 'icon-arrow-down'}`"></span>
            </div>

            <div class="z-10 bg-white rounded-lg" v-if='active'>
                <ul class="pb-3 text-sm text-gray-700" v-if="filter.type != 'price'">
                    <li v-for='(option, index) in filter.options'>
                        <div class="flex items-center p-2 rounded hover:bg-gray-100">
                            <input
                                type="checkbox"
                                v-bind:value="option.id"
                                :id="option.id"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                v-model="appliedValues"
                                @change="apply($event)"
                            />
                            <label
                                for="checkbox-item-11"
                                class="w-full ml-2 text-sm font-medium text-gray-900 rounded"
                            >
                                @{{ option.name }}
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
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
                        });
                },

                setFilters() {
                    let queryParams = new URLSearchParams(window.location.search);

                    queryParams.forEach((value, filter) => {
                        this.filters.applied[filter] = value.split(',');
                    });

                    this.$emit('onFilterApplied', this.filters.applied);
                },

                applyFilter(values, filter) {
                    if (values.length) {
                        this.filters.applied[filter] = values;
                    } else {
                        delete this.filters.applied[filter];
                    }

                    this.$emit('onFilterApplied', this.filters.applied);
                },

                clear() {
                    this.applyFilter(this.filters.applied = {});
                }
            }
        });

        app.component('v-filter-item', {
            template: '#v-filter-item-template',

            props: [
                'index',
                'filter',
                'appliedFilterValues',
            ],

            data() {
                return {
                    appliedValues: [],

                    active: false,

                    sliderConfig: {
                        value: [0, 0],

                        max: 500,

                        processStyle: {
                            "backgroundColor": "#FF6472"
                        },

                        tooltipStyle: {
                            "backgroundColor": "#FF6472",
                            "borderColor": "#FF6472"
                        }
                    }
                }
            },

            created() {
                if (! this.index) this.active = true;

                if (this.appliedFilterValues !== undefined && this.appliedFilterValues.length) {
                    this.appliedValues = this.appliedFilterValues;

                    if (this.filter.type == 'price') {
                        this.sliderConfig.value = this.appliedFilterValues;
                    }

                    this.active = true;
                }

                this.getMaxPrice();
            },

            methods: {
                getMaxPrice() {
                    if (this.filter['code'] != 'price') {
                        return;
                    }

                    this.$axios.get('{{ route("shop.categories.max_price", $category->id) }}')
                        .then((response) => {
                            let maxPrice = response.data.max_price;

                            this.sliderConfig.max = maxPrice ? ((parseInt(maxPrice) !== 0 || maxPrice) ? parseInt(maxPrice) : 500) : 500;

                            if (! this.appliedFilterValues) {
                                this.sliderConfig.value = [0, this.sliderConfig.max];

                                this.sliderConfig.priceTo = this.sliderConfig.max;
                            }
                        })
                },

                apply(event) {
                    this.$emit('onFilterAdded', this.appliedValues);
                }
            }
        });

    </script>
@endPushOnce