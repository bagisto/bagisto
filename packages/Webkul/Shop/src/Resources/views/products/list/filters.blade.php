<div class="layered-filter-wrapper">
    {!!view_render_event('bagisto.shop.products.list.layered-navigation.before') !!}

    <v-filters @onFilterApplied='setFilters("filter", $event)'></v-filters>

    {!!view_render_event('bagisto.shop.products.list.layered-navigation.after') !!}
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
                :appliedFilterValues="appliedValues[filter.code]"
                @onFilterAdded="applyFilter($event, filter.code)"
            >
            </v-filter-item>
        </div>
    </script>

    <script type="text/x-template" id="v-filter-item-template">
        <div class="border-b-[1px] border-[#E9E9E9]">
            <div :class="`flex pb-[10px] justify-between items-center ${active ? 'active' : ''}`">
                <div 
                    class="flex pb-[10px] justify-between items-center" 
                    @click="active = ! active"
                >
                    <p class="text-[18px] font-semibold ">@{{ filter.name ? filter.name : filter.admin_name }}</p>
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
                                @{{ option.label ? option.label : option.admin_name }}
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
                    appliedValues: [],

                    filters: {
                        available: {},

                        applied: {},
                    },
                };
            },

            created() {
                this.getFilters();

                this.setFilters();
            },

            methods: {
                getFilters() {
                    this.$axios.get('{{ route("shop.categories.attributes", $category->id) }}')
                        .then((response) => {
                            response.data.filter_attributes.forEach((val, index) => {
                                this.filters.available[index] = {
                                    'id'     : val.id,
                                    'name'   : val.name,
                                    'code'   : val.code,
                                    'type'   : val.type,
                                    'options': val.options
                                }
                            })
                        });
                },

                setFilters() {
                    let urlParams = new URLSearchParams(window.location.search);

                    urlParams.forEach((value, index) => {
                        this.filters.applied[index] = value.split(',');
                    });
                },

                applyFilter(values,filter) {
                    if (values.length) {
                        this.filters.applied[filter] = values;
                    } else {
                        delete this.filters.applied[filter];
                    }

                    for (let key in this.filters.applied) {
                        if (key == filter && key != 'page') {
                            this.filters.applied[filter] = this.filters.applied[key].join(',');
                        }
                    }

                    this.$emit('onFilterApplied', this.filters.applied);
                },

                clear() {
                    this.filters.applied = [];

                    this.applyFilter([]);
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
                if (!this.index) this.active = true;

                if (this.appliedFilterValues && this.appliedFilterValues.length) {
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

                    this.$axios.get('{{ route("shop.catalog.categories.maximum_price", $category->id) }}')
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