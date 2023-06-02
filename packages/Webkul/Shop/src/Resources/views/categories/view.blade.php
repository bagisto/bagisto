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
    <v-category category-id="{{ $category->id }}"></v-category>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-category-template">
            <div class="container px-[60px] max-lg:px-[30px]">
                <div class="flex gap-[40px] mt-[40px] items-start max-lg:gap-[20px]">
                    <!--Filters-->
                    @include ('shop::categories.filters')

                    <!-- Product Listing Container -->
                    <div>
                        <!-- Product Listing Toolbar -->
                        @include ('shop::categories.toolbar')

                        <!-- Product Card Container -->
                        <div class="grid grid-cols-3 gap-8 mt-[30px] max-sm:mt-[20px] max-1060:grid-cols-2 max-868:grid-cols-1 max-sm:justify-items-center">
                            <x-shop::products.card v-for="product in products"></x-shop::products.card>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-category', {
                template: '#v-category-template',

                props: ['categoryId'],

                data() {
                    return {
                        filters: {},

                        products: [],
                    }
                },

                methods: {
                    setFilters(type, filters) {
                        this.filters[type] = filters;

                        if (this.filters.filter != undefined && this.filters.toolbar != undefined) {
                            let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar);

                            queryParams = this.removeJsonEmptyValues(queryParams);

                            let queryString = this.jsonToQueryString(queryParams);

                            if (queryString = this.jsonToQueryString(queryParams)) {
                                window.history.pushState({}, '', '?' + queryString);
                            }

                            this.getProducts(queryParams);
                        }
                    },

                    getProducts(params) {
                        this.$axios.get("{{ route('shop.products.index') }}", {
                                params: Object.assign({}, params, {category_id: this.categoryId})
                            }).then(response => {
                                this.products = response.data.data;
                            }).catch(error => {
                                console.log(error);
                            })
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
