<!-- SEO Meta Content -->
@push('meta')
    <meta
        name="description"
        content="{{ trim($category->meta_description) != "" ? $category->meta_description : \Illuminate\Support\Str::limit(strip_tags($category->description), 120, '') }}"
    />

    <meta
        name="keywords"
        content="{{ $category->meta_keywords }}"
    />

    @if (core()->getConfigData('catalog.rich_snippets.categories.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getCategoryJsonLd($category) !!}
        </script>
    @endif
@endPush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($category->meta_title) != "" ? $category->meta_title : $category->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.categories.view.banner_path.before') !!}

    <!-- Hero Image -->
    @if ($category->banner_path)
        <div class="container mt-8 px-[60px] max-lg:px-8 max-md:mt-4 max-md:px-4">
            <x-shop::media.images.lazy
                class="aspect-[4/1] max-h-full max-w-full rounded-xl"
                src="{{ $category->banner_url }}"
                alt="{{ $category->name }}"
                width="1320"
                height="300"
            />
        </div>
    @endif

    {!! view_render_event('bagisto.shop.categories.view.banner_path.after') !!}


    <!-- Category Header -->
    <div class="container px-4 sm:px-6 lg:px-8 mt-8 md:mt-12 text-center">
        {!! view_render_event('bagisto.shop.categories.view.description.before') !!}

        <h1 class="text-3xl md:text-4xl font-fraunces text-zylver-olive-green">
            {{ $category->name }}
        </h1>

        @if (in_array($category->display_mode, [null, 'description_only', 'products_and_description']))
            @if ($category->description)
                <div class="prose max-w-3xl mx-auto mt-4 font-lato text-zylver-olive-green/80">
                    {!! $category->description !!}
                </div>
            @endif
        @endif

        {!! view_render_event('bagisto.shop.categories.view.description.after') !!}
    </div>

    @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
        <!-- Category Vue Component -->
        <v-category>
            <!-- Category Shimmer Effect -->
            <x-shop::shimmer.categories.view />
        </v-category>
    @endif

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-category-template"
        >
            <div class="container px-4 sm:px-6 lg:px-8">
                <div class="flex items-start gap-x-8 md:mt-12">
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
                            class="mt-8 grid grid-cols-1 gap-6"
                            v-if="(filters.toolbar.applied.mode ?? filters.toolbar.default.mode) === 'list'"
                        >
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <x-shop::shimmer.products.cards.list count="12" />
                            </template>

                            <!-- Product Card Listing -->
                            {!! view_render_event('bagisto.shop.categories.view.list.product_card.before') !!}

                            <template v-else>
                                <template v-if="products.length">
                                    <x-shop::products.card
                                        ::mode="'list'"
                                        v-for="product in products"
                                    />
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="flex flex-col items-center justify-center w-full py-20 sm:py-24 text-center border border-dashed border-zylver-border-grey rounded-lg bg-zylver-cream/50">
                                        <span class="icon-jewelry-box text-6xl text-zylver-olive-green/30 mb-4" aria-hidden="true"></span> <!-- Placeholder icon, e.g., a jewelry box or magnifying glass -->
                                        <h3 class="font-fraunces text-2xl text-zylver-olive-green mb-2">
                                            No Treasures Found
                                        </h3>
                                        <p class="font-lato text-sm text-zylver-olive-green/80 mb-6 max-w-xs mx-auto">
                                            We couldn't find any products matching your current selection. Try adjusting your filters or explore other categories.
                                        </p>
                                        <a
                                            href="{{ route('shop.home.index') }}"
                                            class="rounded-md bg-zylver-gold py-2.5 px-6 font-lato text-sm font-semibold uppercase tracking-wider text-zylver-olive-green shadow-sm transition-all duration-300 ease-in-out hover:bg-zylver-olive-green hover:text-zylver-cream hover:shadow-md focus:outline-none focus:ring-2 focus:ring-zylver-gold focus:ring-opacity-50"
                                        >
                                            Explore Our Collections
                                        </a>
                                    </div>
                                </template>
                            </template>

                            {!! view_render_event('bagisto.shop.categories.view.list.product_card.after') !!}
                        </div>

                        <!-- Product Grid Card Container -->
                        <div v-else class="mt-8 max-md:mt-5">
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <div class="grid grid-cols-3 gap-8 max-1060:grid-cols-2 max-md:justify-items-center max-md:gap-x-4">
                                    <x-shop::shimmer.products.cards.grid count="12" />
                                </div>
                            </template>

                            {!! view_render_event('bagisto.shop.categories.view.grid.product_card.before') !!}

                            <!-- Product Card Listing -->
                            <template v-else>
                                <template v-if="products.length">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
                                        <x-shop::products.card
                                            ::mode="'grid'"
                                            v-for="product in products"
                                        />
                                    </div>
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="flex flex-col items-center justify-center w-full py-20 sm:py-24 text-center border border-dashed border-zylver-border-grey rounded-lg bg-zylver-cream/50">
                                        <span class="icon-jewelry-box text-6xl text-zylver-olive-green/30 mb-4" aria-hidden="true"></span> <!-- Placeholder icon, e.g., a jewelry box or magnifying glass -->
                                        <h3 class="font-fraunces text-2xl text-zylver-olive-green mb-2">
                                            No Treasures Found
                                        </h3>
                                        <p class="font-lato text-sm text-zylver-olive-green/80 mb-6 max-w-xs mx-auto">
                                            We couldn't find any products matching your current selection. Try adjusting your filters or explore other categories.
                                        </p>
                                        <a
                                            href="{{ route('shop.home.index') }}"
                                            class="rounded-md bg-zylver-gold py-2.5 px-6 font-lato text-sm font-semibold uppercase tracking-wider text-zylver-olive-green shadow-sm transition-all duration-300 ease-in-out hover:bg-zylver-olive-green hover:text-zylver-cream hover:shadow-md focus:outline-none focus:ring-2 focus:ring-zylver-gold focus:ring-opacity-50"
                                        >
                                            Explore Our Collections
                                        </a>
                                    </div>
                                </template>
                            </template>

                            {!! view_render_event('bagisto.shop.categories.view.grid.product_card.after') !!}
                        </div>

                        <!-- Pagination -->
                        <template v-if="!isLoading && products.length">
                            {!! view_render_event('bagisto.shop.categories.view.pagination.before') !!}

                            <x-shop::components.pagination
                                ::meta="meta"
                                on-page-change-method-name="handlePageChange"
                            />

                            {!! view_render_event('bagisto.shop.categories.view.pagination.after') !!}
                        </template>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-category', {
                template: '#v-category-template',

                data() {
                    return {
                        isMobile: window.innerWidth <= 767,

                        isLoading: true,

                        isDrawerActive: {
                            toolbar: false,

                            filter: false,
                        },

                        filters: {
                            toolbar: {
                                default: {},

                                applied: {
                                    page: 1,
                                },
                            },

                            filter: {},
                        },

                        products: [],

                        links: {},

                        meta: {},

                        loader: false,
                    }
                },

                computed: {
                    queryParams() {
                        let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar.applied);

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

                        document.body.style.overflow ='scroll';

                        this.$axios.get("{{ route('shop.api.products.index', ['category_id' => $category->id]) }}", {
                            params: this.queryParams
                        })
                            .then(response => {
                                this.isLoading = false;

                                this.products = response.data.data;

                                this.links = response.data.links;

                                this.meta = response.data.meta;
                            }).catch(error => {
                                console.log(error);
                            });
                    },

                    loadMoreProducts() {
                        if (! this.links.next) {
                            return;
                        }

                        this.loader = true;

                        this.$axios.get(this.links.next)
                            .then(response => {
                                this.loader = false;

                                this.products = [...this.products, ...response.data.data];

                                this.links = response.data.links;
                            }).catch(error => {
                                console.log(error);
                            });
                    },

                    handlePageChange(page) {
                        if (page === this.filters.toolbar.applied.page) {
                            return; // Do nothing if already on the same page
                        }
                        this.isLoading = true; // Indicate loading for the new page
                        this.filters.toolbar.applied.page = page;
                        // The watcher on queryParams will automatically call getProducts()

                        // Optional: Scroll to top after products are loaded.
                        // This can be done in the getProducts().then() callback or by watching products.
                        // For now, let's add a basic scroll to the top of the category container.
                        this.$nextTick(() => {
                            const categoryContainer = this.$el; // The root element of v-category
                            if (categoryContainer) {
                                window.scrollTo({
                                    top: categoryContainer.offsetTop - 80, // Adjust offset as needed (e.g., for sticky header)
                                    behavior: 'smooth'
                                });
                            }
                        });
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
