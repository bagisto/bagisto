@php
    if (request()->has('query')) {
        $title = trans('shop::app.search.title', ['query' => request()->query('query')]);
    } else {
        $title = trans('shop::app.search.results');
    }
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ $title }}"/>

    <meta name="keywords" content="{{ $title }}"/>
@endPush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $title }}
    </x-slot>

    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">
        @if (request()->has('image-search'))
            @include('shop::search.images.results')
        @endif

        <div class="flex justify-between items-center mt-8">
            <h1 class="text-2xl font-medium">
                {{ $title }}
            </h1>
        </div>
    </div>
        
    <!-- Product Listing -->
    <v-search>
        <x-shop::shimmer.categories.view />
    </v-search>

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-search-template"
        >
            <div class="container px-[60px] max-lg:px-8 max-sm:px-4">
                <div class="flex gap-10 items-start md:mt-10 max-lg:gap-5">
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
                            class="grid grid-cols-1 gap-6 mt-8"
                            v-if="filters.toolbar.mode === 'list'"
                        >
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <x-shop::shimmer.products.cards.list count="12" />
                            </template>

                            <!-- Product Card Listing -->
                            <template v-else>
                                <template v-if="products.length">
                                    <x-shop::products.card
                                        ::mode="'list'"
                                        v-for="product in products"
                                    />
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="grid items-center justify-items-center place-content-center w-full m-auto h-[476px] text-center">
                                        <img src="{{ bagisto_asset('images/thank-you.png') }}"/>
                                  
                                        <p class="text-xl">
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
                                <div class="grid grid-cols-3 gap-8 mt-8 max-sm:mt-5 max-1060:grid-cols-2 max-sm:justify-items-center max-sm:gap-4">
                                    <x-shop::shimmer.products.cards.grid count="12" />
                                </div>
                            </template>

                            <!-- Product Card Listing -->
                            <template v-else>
                                <template v-if="products.length">
                                    <div class="grid grid-cols-3 gap-8 mt-8 max-sm:mt-5 max-1060:grid-cols-2 max-sm:justify-items-center max-sm:gap-4">
                                        <x-shop::products.card
                                            ::mode="'grid'"
                                            v-for="product in products"
                                        />
                                    </div>
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="grid items-center justify-items-center place-content-center w-full m-auto h-[476px] text-center">
                                        <img src="{{ bagisto_asset('images/thank-you.png') }}"/>
                                        
                                        <p class="text-xl">
                                            @lang('shop::app.categories.view.empty')
                                        </p>
                                    </div>
                                </template>
                            </template>
                        </div>

                        <!-- Load More Button -->
                        <button
                            class="secondary-button block mx-auto w-max py-3 mt-[60px] px-11 rounded-2xl text-base text-center"
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

                        this.$axios.get(("{{ route('shop.api.products.index') }}"), { 
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
