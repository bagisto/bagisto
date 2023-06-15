<x-shop::layouts>
    {{-- Hero Image --}}
    <div class="container mt-[30px] px-[60px] max-lg:px-[30px]">
        <div>
            <img
                class="rounded-[12px]"
                src='{{ bagisto_asset("images/product-hero.png") }}'
            >
        </div>
    </div>

    {{-- Product Listing --}}
    <v-category
        src="{{ route('shop.products.index', ['category_id' => $category->id]) }}"
        category-id="{{ $category->id }}"
    >
        <x-shop::shimmer.categories.view></x-shop::shimmer.categories.view>
    </v-category>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-category-template">
            <div class="container px-[60px] max-lg:px-[30px]">
                <div class="flex gap-[40px] mt-[40px] items-start max-lg:gap-[20px]">
                    <!-- Product Listing Filters -->
                    @include('shop::categories.filters')

                    <!-- Product Listing Container -->
                    <div class="flex-1">
                        <!-- Product Listing Toolbar -->
                        @include('shop::categories.toolbar')

                        <!-- Product Card Container -->
                        <div class="grid grid-cols-3 gap-8 mt-[30px] max-sm:mt-[20px] max-1060:grid-cols-2 max-868:grid-cols-1 max-sm:justify-items-center">
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <x-shop::shimmer.products.card count="12"></x-shop::shimmer.products.card>
                            </template>

                            <!-- Product Card Listing -->
                            <template v-else>
                                <x-shop::products.card v-for="product in products"></x-shop::products.card>
                            </template>
                        </div>

                        <!-- Load More Button -->
                        <button
                            class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center"
                            @click="loadMoreProducts()"
                            v-if="links.next"
                        >
                            <!-- @translations -->
                            @lang('Load More')
                        </button>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-category', {
                template: '#v-category-template',

                props: [
                    'src',
                    'categoryId',
                ],

                data() {
                    return {
                        isLoading: true,

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
                        this.$axios.get(this.src, { params: this.queryParams })
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
