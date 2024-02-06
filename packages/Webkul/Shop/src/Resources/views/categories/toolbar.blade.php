{!! view_render_event('bagisto.shop.categories.view.toolbar.before') !!}

<v-toolbar @filter-applied='setFilters("toolbar", $event)'></v-toolbar>

{!! view_render_event('bagisto.shop.categories.view.toolbar.after') !!}

@inject('toolbar' , 'Webkul\Product\Helpers\Toolbar')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id='v-toolbar-template'
    >
        <div>
            <!-- Desktop Toolbar -->
            <div class="flex justify-between max-md:hidden">
                {!! view_render_event('bagisto.shop.categories.toolbar.filter.before') !!}

                <!-- Product Sorting Filters -->
                <x-shop::dropdown position="bottom-left">
                    <x-slot:toggle>
                        <!-- Dropdown Toggler -->
                        <button class="flex justify-between items-center gap-4 max-w-[200px] w-full p-3.5 rounded-lg bg-white border border-[#E9E9E9] text-base transition-all hover:border-gray-400 focus:border-gray-400 max-md:pr-2.5 max-md:pl-2.5 max-md:border-0 max-md:w-[110px] cursor-pointer">
                            @{{ sortLabel ?? "@lang('shop::app.products.sort-by.title')" }}

                            <span
                                class="text-2xl icon-arrow-down"
                                role="presentation"
                            ></span>
                        </button>
                    </x-slot>
                
                    <!-- Dropdown Content -->
                    <x-slot:menu>
                        <x-shop::dropdown.menu.item
                            v-for="(sort, key) in filters.available.sort"
                            ::class="{'bg-gray-100': sort.value == filters.applied.sort}"
                            @click="apply('sort', sort.value)"
                        >
                            @{{ sort.title }}
                        </x-shop::dropdown.menu.item>
                    </x-slot>
                </x-shop::dropdown>

                {!! view_render_event('bagisto.shop.categories.toolbar.filter.after') !!}

                {!! view_render_event('bagisto.shop.categories.toolbar.pagination.before') !!}

                <!-- Product Pagination Limit -->
                <div class="flex gap-10 items-center">
                    <!-- Product Pagination Limit -->
                    <x-shop::dropdown position="bottom-right">
                        <x-slot:toggle>
                            <!-- Dropdown Toggler -->
                            <button class="flex gap-4 justify-between items-center max-w-[200px] bg-white border border-[#E9E9E9] text-base rounded-lg w-full p-3.5 max-md:pr-2.5 transition-all hover:border-gray-400 focus:border-gray-400 max-md:pl-2.5 max-md:border-0 max-md:w-[110px] cursor-pointer">
                                @{{ filters.applied.limit ?? "@lang('shop::app.categories.toolbar.show')" }}

                                <span
                                    class="text-2xl icon-arrow-down"
                                    role="presentation"
                                ></span>
                            </button>
                        </x-slot>
                    
                        <!-- Dropdown Content -->
                        <x-slot:menu>
                            <x-shop::dropdown.menu.item
                                v-for="(limit, key) in filters.available.limit"
                                ::class="{'bg-gray-100': limit == filters.applied.limit}"
                                @click="apply('limit', limit)"
                            >
                                @{{ limit }}
                            </x-shop::dropdown.menu.item>
                        </x-slot>
                    </x-shop::dropdown>

                    <!-- Listing Mode Switcher -->
                    <div class="flex gap-5 items-center">
                        <span
                            class="text-2xl cursor-pointer"
                            role="button"
                            aria-label="@lang('shop::app.categories.toolbar.list')"
                            tabindex="0"
                            :class="(filters.applied.mode === 'list') ? 'icon-listing-fill' : 'icon-listing'"
                            @click="changeMode('list')"
                        >
                        </span>

                        <span
                            class="text-2xl cursor-pointer"
                            role="button"
                            aria-label="@lang('shop::app.categories.toolbar.grid')"
                            tabindex="0"
                            :class="(filters.applied.mode === 'grid') ? 'icon-grid-view-fill' : 'icon-grid-view'"
                            @click="changeMode()"
                        >
                        </span>
                    </div>
                </div>

                {!! view_render_event('bagisto.shop.categories.toolbar.pagination.after') !!}
            </div>

            <!-- Modile Toolbar -->
            <div class="md:hidden">
                <ul>
                    <li
                        class="p-2.5"
                        :class="{'bg-gray-100': sort.value == filters.applied.sort}"
                        v-for="(sort, key) in filters.available.sort"
                        @click="apply('sort', sort.value)"
                    >
                        @{{ sort.title }}
                    </li>
                </ul>
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

                            mode: @json($toolbar->getAvailableModes()),
                        },

                        default: {
                            sort: '{{ $toolbar->getOrder([])['value'] }}',

                            limit: '{{ $toolbar->getLimit([]) }}',

                            mode: '{{ $toolbar->getMode([]) }}',
                        },

                        applied: {
                            sort: '{{ $toolbar->getOrder($params ?? [])['value'] }}',

                            limit: '{{ $toolbar->getLimit($params ?? []) }}',

                            mode: '{{ $toolbar->getMode($params ?? []) }}',
                        }
                    }
                };
            },

            mounted() {
                this.setFilters();
            },

            computed: {
                sortLabel() {
                    return this.filters.available.sort.find(sort => sort.value === this.filters.applied.sort).title;
                }
            },

            methods: {
                apply(type, value) {
                    this.filters.applied[type] = value;

                    this.setFilters();
                },

                changeMode(value = 'grid') {
                    this.filters.applied['mode'] = value;

                    this.setFilters();
                },

                setFilters() {
                    let filters = {};

                    for (let key in this.filters.applied) {
                        if (this.filters.applied[key] != this.filters.default[key]) {
                            filters[key] = this.filters.applied[key];
                        }
                    }

                    this.$emit('filter-applied', filters);
                }
            },
        });
    </script>
@endPushOnce
