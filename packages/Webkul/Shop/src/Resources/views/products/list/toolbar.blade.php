
{!! view_render_event('bagisto.shop.products.list.toolbar.before') !!}

<v-toolbar @onFilterApplied='setFilters("toolbar", $event)'></v-toolbar>

{!! view_render_event('bagisto.shop.products.list.toolbar.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id='v-toolbar-template'>
        <div class="flex justify-between max-md:items-center">
            <div class="text-[16px] font-medium hidden max-md:block">Filters</div>

            <div>
                <select 
                    class="custom-select max-w-[200px] bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px]  max-md:border-0 max-md:outline-none max-md:w-[110px]"
                    v-model="filters.applied.sort"
                    @change="apply('sort', $event)"
                >
                    <option value=''>Sort by</option>
                    <option :value="key" v-for="(sort, key) in filters.available.sort">
                        @{{ sort }}
                    </option>
                </select>
            </div>

            <div class="flex gap-[40px] items-center max-md:hidden">
                <select 
                    class="custom-select max-w-[120px] bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px]"
                    v-model="filters.applied.limit"
                    @change="apply('limit', $event)"
                >
                    <option value=''>Show</option>
                    <option :value="limit" v-for="limit in filters.available.limit">
                        @{{ limit }}
                    </option>
                </select>

                <div class="flex items-center gap-[20px]">
                    <span class="icon-listing text-[24px]"></span>
                    <span class="icon-grid-view text-[24px]"></span>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-toolbar', {
            template: '#v-toolbar-template',

            data() {
                return {
                    filters: {
                        queryParams: @json(request()->input()),

                        available: {
                            sort: @json($productHelper->getAvailableOrders()),

                            limit: @json($productHelper->getAvailableLimits()),
                        },

                        applied: {
                            sort: "{{ request()->query('sort') ?? (core()->getConfigData('catalog.products.storefront.sort_by') ?? 'price-desc') }}",

                            limit: "{{ request()->query('limit') ?? 12 }}",

                            category_id: @json($category->id)
                        }
                    }
                };
            },

            mounted() {
                this.$emit('onFilterApplied', this.filters.applied);
            },

            methods: {
                apply(type, value) {
                    this.filters.applied[type] = value.target.value;

                    this.$emit('onFilterApplied', this.filters.applied);
                }
            },
        });
    </script>
@endPushOnce