{!! view_render_event('bagisto.admin.catalog.product.edit.form.links.before', ['product' => $product]) !!}
    
<v-links></v-links>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.links.before', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-links-template">
        <div>
            <!-- Panel -->
            <div
                class="relative p-[16px] bg-white rounded-[4px] box-shadow"
            >
                <div class="flex flex-wrap gap-[10px] justify-between mb-[10px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @lang('admin::app.catalog.products.edit.links.title')
                        </p>

                        <p class="text-[12px] text-gray-500 font-medium">
                            @lang('admin::app.catalog.products.edit.links.info')
                        </p>
                    </div>
                    
                    <!-- Add Button -->
                    <div class="flex gap-x-[4px] items-center">
                        <div
                            class="px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                            @click="$refs.searchProductDrawer.open()"
                        >
                            @lang('admin::app.catalog.products.edit.links.add-btn')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Drawer -->
            <x-admin::drawer ref="searchProductDrawer">
                <!-- Drawer Header -->
                <x-slot:header>
                    <div class="grid gap-[12px]">
                        <div class="flex justify-between items-center">
                            <p class="text-[20px] font-medium">
                                @lang('admin::app.catalog.products.edit.links.search.title')
                            </p>

                            <div class="mr-[45px] px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                                @lang('admin::app.catalog.products.edit.links.search.add-btn')
                            </div>
                        </div>

                        <div class="relative w-full">
                            <input
                                type="text"
                                class="bg-white border border-gray-300 rounded-lg block w-full pl-[12px] pr-[40px] py-[5px] leading-6 text-gray-600 transition-all hover:border-gray-400"
                                placeholder="Search by name"
                                v-model="searchTerm"
                                @keyup="search"
                            />

                            <span class="icon-search text-[22px] absolute right-[12px] top-[6px] flex items-center pointer-events-none"></span>
                        </div>
                    </div>
                </x-slot:header>

                <!-- Drawer Content -->

                <x-slot:content class="!p-0">
                    <div
                        class="grid"
                        v-if="searchedProducts.length"
                    >
                        <div
                            class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300"
                            v-for="product in searchedProducts"
                        >
                            <!-- Information -->
                            <div class="flex gap-[10px]">
                                <!-- Checkbox -->
                                <div class="">
                                    <input
                                        type="checkbox"
                                        class="sr-only peer"
                                        id="product-1"
                                    />

                                    <label
                                        class="icon-uncheckbox text-[24px] peer-checked:icon-checked peer-checked:text-blue-600 cursor-pointer"
                                        for="product-1"
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
                                    <p class="text-[16x] text-gray-800 font-semibold">Variant 1 6</p>
                                    <p class="text-gray-600">SKU - configurable-1-variant-1-6</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid gap-[4px] place-content-start">
                                <p class="text-gray-800 font-semibold">$120.00</p>
                                <p class="text-green-600">25 Available</p>
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
                        'up_sells',
                        'cross_sells',
                        'related_products'
                    ],

                    searchTerm: '',

                    searchedProducts: [],

                    addedProducts: {
                        'up_sells': @json($product->up_sells()->get()),
                        'cross_sells': @json($product->cross_sells()->get()),
                        'related_products': @json($product->related_products()->get())
                    },
                }
            },

            methods: {
                search: function () {
                    if (this.searchTerm.length >= 1) {
                        this.searchedProducts = [];
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
                }
            }
        });
    </script>
@endPushOnce