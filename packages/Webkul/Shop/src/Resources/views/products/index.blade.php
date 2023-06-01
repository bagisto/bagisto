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
    <v-category></v-category>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-category-template">
        <div class="container px-[60px] max-lg:px-[30px]">
            <div class="flex gap-[40px] mt-[40px] items-start max-lg:gap-[20px]">
                <!--Filters-->
                @include ('shop::products.list.filters')

                <!-- Product Listing Container -->
                <div>
                    <!-- Product Listing Toolbar -->
                    @include ('shop::products.list.toolbar')

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

            data() {
                return {
                    filters: {
                        toolbar: {},

                        filter: []
                    },

                    products: [],

                    params: {}
                }
            },

            methods: {
                setFilters(sort, filters) {
                    // console.log(filters);
                    if (sort == 'filter') {
                        this.filters.toolbar = filters
                    } else if (sort == 'toolbar') {
                        filters = filters.sort.split('-');
                        this.filters.filter['sort'] = filters[0];
                        this.filters.filter['order'] = filters[1]
                    }

                    this.params = { ...this.filters.filter, ...this.filters.toolbar };

                    // For GET parameters in URL
                    window.history.pushState({}, "", "?" + this.jsonToString());

                    this.getProducts(this.params);
                },

                getProducts(params) {
                    params['category_id'] = @json($category->id);
                    // console.log(params)

                    this.$axios.get("{{ route('shop.category_products.get') }}", { params })
                        .then(response => {
                            this.products = response.data.data
                        }).catch(error => {
                            console.log(error);
                        })
                },

                jsonToString() { 
                    let parameters = new URLSearchParams();
                    for (const key in this.params) {
                        parameters.append(key, this.params[key]);
                    }

                    return parameters.toString();
                }
            },
        });
    </script>
@endPushOnce

</x-shop::layouts>
