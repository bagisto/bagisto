{!! view_render_event('bagisto.shop.categories.view.toolbar.before') !!}

<v-toolbar @filter-applied='setFilters("toolbar", $event)'></v-toolbar>

{!! view_render_event('bagisto.shop.categories.view.toolbar.after') !!}

@inject('toolbar' , 'Webkul\Product\Helpers\Toolbar')

@pushOnce('scripts')
    <script type="text/x-template" id='v-toolbar-template'>
        <div class="flex justify-between max-md:items-center">
            <div class="text-[16px] font-medium hidden max-md:block">
                <!-- @translations -->
                @lang('Filters')
            </div>

            <div>
                <select
                    class="custom-select max-w-[200px] bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px]  max-md:border-0 max-md:outline-none max-md:w-[110px] cursor-pointer"
                    v-model="filters.applied.sort"
                    @change="apply('sort', filters.applied.sort)"
                >
                    <option value=''>
                        @lang('shop::app.products.sort-by.title')
                    </option>

                    <option
                        :value="sort.value"
                        v-for="(sort, key) in filters.available.sort"
                        v-text="sort.title"
                    >
                    </option>
                </select>
            </div>

            <div class="flex gap-[40px] items-center max-md:hidden">
                <select
                    class="custom-select max-w-[120px] bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px] cursor-pointer"
                    v-model="filters.applied.limit"
                    @change="apply('limit', filters.applied.limit)"
                >
                    <option value=''>
                        <!-- @translations -->
                        @lang('Show')
                    </option>

                    <option
                        :value="limit"
                        v-for="limit in filters.available.limit"
                        v-text="limit"
                    >
                    </option>
                </select>

                <div class="flex items-center gap-[20px]">
                    <span 
                        class="icon-listing text-[24px] cursor-pointer"
                        @click="mode('list')" 
                    >
                    </span>

                    <span 
                        @click="mode()" 
                        class="icon-grid-view text-[24px] cursor-pointer"
                    >
                    </span>
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
                        available: {
                            sort: @json($toolbar->getAvailableOrders()),

                            limit: @json($toolbar->getAvailableLimits()),
                        },

                        applied: {
                            sort: '{{ $toolbar->getOrder($params)['value'] }}',

                            limit: '{{ $toolbar->getLimit($params) }}',
                        }
                    }
                };
            },

            mounted() {
                this.$emit('filter-applied', this.filters.applied);

                let query = new URLSearchParams(window.location.search);

                this.$parent.$data.mode = query.get('mode') ?? 'grid';
            },

            methods: {
                apply(type, value) {
                    this.filters.applied[type] = value;

                    this.$emit('filter-applied', this.filters.applied);
                },

                mode(value = 'grid') {
                    this.filters.applied['mode'] = value;

                    this.$parent.$data.mode = value;

                    this.$emit('filter-applied', this.filters.applied);
                }
            },
        });
    </script>
@endPushOnce
