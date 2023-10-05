{{-- SEO Meta Content --}}
@push('meta')
    <meta name="description" content="@lang('shop::app.search.title', ['query' => request()->query('query')])"/>

    <meta name="keywords" content="@lang('shop::app.search.title', ['query' => request()->query('query')])"/>
@endPush

<x-shop::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.search.title', ['query' => request()->query('query')])
    </x-slot>

    <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
        @if (request()->has('image-search'))
            @include('shop::search.images.results')
        @endif

        <div class="flex justify-between items-center mt-[30px]">
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.search.title', ['query' => request()->query('query')])
            </h2>
        </div>
    </div>
        
    {{-- Product Listing --}}
    <v-search>
        <x-shop::shimmer.categories.view/>
    </v-search>

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-search-template"
        >
            <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
                <div class="flex gap-[40px] items-start md:mt-[40px] max-lg:gap-[20px]">
                    <!-- Product Listing Filters -->
                    @include('shop::categories.filters')

                    <!-- Product Listing Container -->
                    <div class="flex-1">
                        <!-- Desktop Product Listing Toolbar -->
                        <div class="max-md:hidden">
                            @include('shop::categories.toolbar')
                        </div>

                        <!-- Product List Card Container -->
                        <div
                            class="grid grid-cols-1 gap-[25px] mt-[30px]"
                            v-if="filters.toolbar.mode === 'list'"
                        >
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <x-shop::shimmer.products.cards.list count="12"></x-shop::shimmer.products.cards.list>
                            </template>

                            <!-- Product Card Listing -->
                            <template v-else>
                                <template v-if="products.length">
                                    <x-shop::products.card
                                        ::mode="'list'"
                                        v-for="product in products"
                                    >
                                    </x-shop::products.card>
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="grid items-center justify-items-center place-content-center w-[100%] m-auto h-[476px] text-center">
                                        <img src="{{ bagisto_asset('images/thank-you.png') }}"/>
                                  
                                        <p class="text-[20px]">
                                            @lang('shop::app.categories.view.empty')
                                        </p>
                                    </div>
                                </template>
                            </template>
                        </div>

                        <!-- Product Grid Card Container -->
                        <div v-else>
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <div class="grid grid-cols-3 gap-8 mt-[30px] max-sm:mt-[20px] max-1060:grid-cols-2 max-sm:justify-items-center max-sm:gap-[16px]">
                                    <x-shop::shimmer.products.cards.grid count="12"></x-shop::shimmer.products.cards.grid>
                                </div>
                            </template>

                            <!-- Product Card Listing -->
                            <template v-else>
                                <template v-if="products.length">
                                    <div class="grid grid-cols-3 gap-8 mt-[30px] max-sm:mt-[20px] max-1060:grid-cols-2 max-sm:justify-items-center max-sm:gap-[16px]">
                                        <x-shop::products.card
                                            ::mode="'grid'"
                                            v-for="product in products"
                                        >
                                        </x-shop::products.card>
                                    </div>
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="grid items-center justify-items-center place-content-center w-[100%] m-auto h-[476px] text-center">
                                        <img src="{{ bagisto_asset('images/thank-you.png') }}"/>
                                        
                                        <p class="text-[20px]">
                                            @lang('shop::app.categories.view.empty')
                                        </p>
                                    </div>
                                </template>
                            </template>
                        </div>

                        <!-- Load More Button -->
                        <button
                            class="secondary-button block mx-auto w-max py-[11px] mt-[60px] px-[43px] rounded-[18px] text-base text-center"
                            @click="loadMoreProducts"
                            v-if="links.next"
                        >
                            @lang('shop::app.categories.view.load-more')
                        </button>
                    </div>
                </div>
            </div>
    </script>

        <script type="module">
            app.component('v-search', {
                template: '#v-search-template',

                data() {
                    return {
                        isMobile: window.innerWidth <= 767,

                        isLoading: true,

                        isDrawerActive: {
                            toolbar: false,
                            
                            filter: false,
                        },

                        filters: {
                            toolbar: {},
                            
                            filter: {},
                        },

                        products: [],

                        links: {},
                    }
                },

                computed: {
                    queryParams() {
                        let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar);

                        return this.removeJsonEmptyValues(queryParams);
                    },

                    queryString() {
                        return this.jsonToQueryString(this.queryParams);
                    },
                },

                watch: {
                    queryParams() {
                        this.getProducts();
                    },

                    queryString() {
                        window.history.pushState({}, '', '?' + this.queryString);
                    },
                },

                methods: {
                    setFilters(type, filters) {
                        this.filters[type] = filters;
                    },

                    clearFilters(type, filters) {
                        this.filters[type] = {};
                    },

                    getProducts() {
                        this.isDrawerActive = {
                            toolbar: false,
                            
                            filter: false,
                        };

                        this.$axios.get(("{{ route('shop.api.products.index', ['name' => request('query')]) }}"), { 
                            params: this.queryParams 
                        })
                            .then(response => {
                                this.isLoading = false;

                                this.products = response.data.data;

                                this.links = response.data.links;
                            }).catch(error => {
                                console.log(error);
                            });
                    },

                    loadMoreProducts() {
                        if (this.links.next) {
                            this.$axios.get(this.links.next).then(response => {
                                this.products = [...this.products, ...response.data.data];

                                this.links = response.data.links;
                            }).catch(error => {
                                console.log(error);
                            });
                        }
                    },

                    removeJsonEmptyValues(params) {
                        Object.keys(params).forEach(function (key) {
                            if ((! params[key] && params[key] !== undefined)) {
                                delete params[key];
                            }

                            if (Array.isArray(params[key])) {
                                params[key] = params[key].join(',');
                            }
                        });

                        return params;
                    },

                    jsonToQueryString(params) {
                        let parameters = new URLSearchParams();

                        for (const key in params) {
                            parameters.append(key, params[key]);
                        }

                        return parameters.toString();
                    }
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
