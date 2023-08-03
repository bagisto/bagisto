{!! view_render_event('bagisto.admin.catalog.product.edit.form.links.before', ['product' => $product]) !!}
    
<v-links></v-links>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.links.before', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-links-template">
        <div class="grid gap-[10px]">
            <!-- Panel -->
            <div
                class="relative bg-white rounded-[4px] box-shadow"
                v-for="type in types"
            >
                <div class="flex gap-[20px] justify-between mb-[10px] p-[16px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @{{ type.title }}
                        </p>

                        <p class="text-[12px] text-gray-500 font-medium">
                            @{{ type.info }}
                        </p>
                    </div>
                    
                    <!-- Add Button -->
                    <div class="flex gap-x-[4px] items-center">
                        <div
                            class="px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                            @click="selectedType = type.key; $refs.searchProductDrawer.open()"
                        >
                            @lang('admin::app.catalog.products.edit.links.add-btn')
                        </div>
                    </div>
                </div>

                <!-- Product Listing -->
                <div
                    class="grid"
                    v-if="addedProducts[type.key].length"
                >
                    <div
                        class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300"
                        v-for="product in addedProducts[type.key]"
                    >
                        <!-- Hidden Input -->
                        <input
                            type="hidden"
                            :name="type.key + '[]'"
                            :value="product.id"
                        />

                        <!-- Information -->
                        <div class="flex gap-[10px]">
                            <!-- Image -->
                            <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                <img src="http://localhost:5173/src/Resources/assets/images/product-placeholders/top-angle.svg" class="w-[20px]" />
                                <p class="text-[6px] text-gray-400 font-semibold">Product Image</p>
                            </div>

                            <!-- Details -->
                            <div class="grid gap-[6px] place-content-start">
                                <p class="text-[16x] text-gray-800 font-semibold">
                                    @{{ product.name }}
                                </p>

                                <p class="text-gray-600">
                                    @{{ "@lang('admin::app.catalog.products.edit.links.sku')".replace(':sku', product.sku) }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid gap-[4px] place-content-start text-right">
                            <p class="text-gray-800 font-semibold">
                                @{{ $admin.formatPrice(product.price) }}    
                            </p>

                            <p
                                class="text-red-600 cursor-pointer"
                                @click="remove(type.key, product)"
                            >
                                @lang('admin::app.catalog.products.edit.links.delete')
                            </p>
                        </div>
                    </div>
                </div>

                <!-- For Empty Variations -->
                <div
                    class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                    v-else
                >
                    <!-- Placeholder Image -->
                    <img
                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                        class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                    />

                    <!-- Add Variants Information -->
                    <div class="flex flex-col items-center">
                        <p class="text-[16px] text-gray-400 font-semibold">
                            @lang('admin::app.catalog.products.edit.links.empty-title')
                        </p>

                        <p class="text-gray-400">
                            @{{ type.empty_info }}
                        </p>
                    </div>

                    <div
                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-[14px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                        @click="selectedType = type.key; $refs.searchProductDrawer.open()"
                    >
                        @lang('admin::app.catalog.products.edit.links.add-btn')
                    </div>
                </div>
            </div>

            <!-- Search Drawer -->
            <x-admin::drawer
                ref="searchProductDrawer"
                @close="searchTerm = ''; searchedProducts = [];"
            >
                <!-- Drawer Header -->
                <x-slot:header>
                    <div class="grid gap-[12px]">
                        <div class="flex justify-between items-center">
                            <p class="text-[20px] font-medium">
                                @lang('admin::app.catalog.products.edit.links.search.title')
                            </p>

                            <div
                                class="mr-[45px] px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                @click="addSelected"
                            >
                                @lang('admin::app.catalog.products.edit.links.search.add-btn')
                            </div>
                        </div>

                        <div class="relative w-full">
                            <input
                                type="text"
                                class="bg-white border border-gray-300 rounded-lg block w-full pl-[12px] pr-[40px] py-[5px] leading-6 text-gray-600 transition-all hover:border-gray-400"
                                placeholder="Search by name"
                                v-model="searchTerm"
                                @input="search"
                            />

                            <span class="icon-search text-[22px] absolute right-[12px] top-[6px] flex items-center pointer-events-none"></span>
                        </div>
                    </div>
                </x-slot:header>

                <!-- Drawer Content -->
                <x-slot:content class="!p-0">
                    <div
                        class="grid"
                        v-if="filteredSearchedProducts.length"
                    >
                        <div
                            class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300"
                            v-for="product in filteredSearchedProducts"
                        >
                            <!-- Information -->
                            <div class="flex gap-[10px]">
                                <!-- Checkbox -->
                                <div class="">
                                    <input
                                        type="checkbox"
                                        class="sr-only peer"
                                        :id="'product' + product.id"
                                        v-model="product.selected"
                                    />

                                    <label
                                        class="icon-uncheckbox text-[24px] peer-checked:icon-checked peer-checked:text-blue-600 cursor-pointer"
                                        :for="'product' + product.id"
                                    >
                                    </label>
                                </div>

                                <!-- Image -->
                                <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                    <img src="http://localhost:5173/src/Resources/assets/images/product-placeholders/top-angle.svg" class="w-[20px]" />
                                    <p class="text-[6px] text-gray-400 font-semibold">Product Image</p>
                                </div>

                                <!-- Details -->
                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 font-semibold">
                                        @{{ product.name }}
                                    </p>

                                    <p class="text-gray-600">
                                        @{{ "@lang('admin::app.catalog.products.edit.links.search.sku')".replace(':sku', product.sku) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-gray-800 font-semibold">
                                    @{{ $admin.formatPrice(product.price) }}
                                </p>

                                <p class="text-green-600">
                                    @{{ "@lang('admin::app.catalog.products.edit.links.search.qty')".replace(':qty', totalQty(product)) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- For Empty Variations -->
                    <div
                        class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                        v-else
                    >
                        <!-- Placeholder Image -->
                        <img
                            src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                            class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                        />

                        <!-- Add Variants Information -->
                        <div class="flex flex-col items-center">
                            <p class="text-[16px] text-gray-400 font-semibold">
                                @lang('admin::app.catalog.products.edit.links.search.empty-title')
                            </p>

                            <p class="text-gray-400">
                                @lang('admin::app.catalog.products.edit.links.search.empty-info')
                            </p>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::drawer>
        </div>
    </script>

    <script type="module">
        app.component('v-links', {
            template: '#v-links-template',

            data() {
                return {
                    types: [
                        {
                            key: 'related_products',
                            title: "@lang('admin::app.catalog.products.edit.links.related-products.title')",
                            info: "@lang('admin::app.catalog.products.edit.links.related-products.info')",
                            empty_info: "@lang('admin::app.catalog.products.edit.links.related-products.empty-info')",
                        }, {
                            key: 'up_sells',
                            title: "@lang('admin::app.catalog.products.edit.links.up-sells.title')",
                            info: "@lang('admin::app.catalog.products.edit.links.up-sells.info')",
                            empty_info: "@lang('admin::app.catalog.products.edit.links.up-sells.empty-info')",
                        }, {
                            key: 'cross_sells',
                            title: "@lang('admin::app.catalog.products.edit.links.cross-sells.title')",
                            info: "@lang('admin::app.catalog.products.edit.links.cross-sells.info')",
                            empty_info: "@lang('admin::app.catalog.products.edit.links.cross-sells.empty-info')",
                        }
                    ],

                    currentProduct: @json($product),

                    selectedType: '',

                    searchTerm: '',

                    searchedProducts: [],

                    addedProducts: {
                        'up_sells': @json($product->up_sells()->get()),

                        'cross_sells': @json($product->cross_sells()->get()),

                        'related_products': @json($product->related_products()->get())
                    },
                }
            },

            computed: {
                filteredSearchedProducts() {
                    this.searchedProducts = this.searchedProducts.filter(item => item.id !== this.currentProduct.id);

                    if (this.addedProducts[this.selectedType].length) {
                        this.addedProducts[this.selectedType].forEach(addedProduct => {
                            this.searchedProducts = this.searchedProducts.filter(item => item.id !== addedProduct.id);
                        });
                    }

                    return this.searchedProducts;
                }
            },

            methods: {
                search() {
                    if (! this.searchTerm.length) {
                        this.searchedProducts = [];

                        return;
                    }

                    let self = this;
                    
                    this.$axios.get("{{ route('admin.catalog.products.product_link_search') }}", {
                            params: {
                                query: this.searchTerm
                            }
                        })
                        .then(function(response) {
                            self.searchedProducts = response.data;
                        })
                        .catch(function (error) {
                        })
                },

                addSelected() {
                    let selectedProducts = this.searchedProducts.filter(product => product.selected);

                    this.addedProducts[this.selectedType] = [...this.addedProducts[this.selectedType], ...selectedProducts];

                    this.$refs.searchProductDrawer.close();
                },

                remove(type, product) {
                    this.addedProducts[type] = this.addedProducts[type].filter(item => item.id!== product.id);
                },

                totalQty(product) {
                    let qty = 0;

                    product.inventories.forEach(function (inventory) {
                        qty += inventory.qty;
                    });

                    return qty;
                }
            }
        });
    </script>
@endPushOnce