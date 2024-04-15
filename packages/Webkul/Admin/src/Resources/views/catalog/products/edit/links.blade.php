{!! view_render_event('bagisto.admin.catalog.product.edit.form.links.before', ['product' => $product]) !!}
    
<v-product-links></v-product-links>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.links.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-links-template"
    >
        <div class="grid gap-2.5">
            <!-- Panel -->
            <div
                class="box-shadow relative rounded bg-white dark:bg-gray-900"
                v-for="type in types"
            >
                <div class="mb-2.5 flex justify-between gap-5 p-4">
                    <div class="flex flex-col gap-2">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @{{ type.title }}
                        </p>

                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @{{ type.info }}
                        </p>
                    </div>
                    
                    <!-- Add Button -->
                    <div class="flex items-center gap-x-1">
                        <div
                            class="secondary-button"
                            @click="selectedType = type.key; $refs.productSearch.openDrawer()"
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
                        class="flex justify-between gap-2.5 border-b border-slate-300 p-4 dark:border-gray-800"
                        v-for="product in addedProducts[type.key]"
                    >
                        <!-- Hidden Input -->
                        <input
                            type="hidden"
                            :name="type.key + '[]'"
                            :value="product.id"
                        />

                        <!-- Information -->
                        <div class="flex gap-2.5">
                            <!-- Image -->
                            <div
                                class="relative h-[60px] max-h-[60px] w-full max-w-[60px] overflow-hidden rounded"
                                :class="{'border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert': ! product.images.length}"
                            >
                                <template v-if="! product.images.length">
                                    <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                
                                    <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                        @lang('admin::app.catalog.products.edit.links.image-placeholder')
                                    </p>
                                </template>
            
                                <template v-else>
                                    <img :src="product.images[0].url">
                                </template>
                            </div>

                            <!-- Details -->
                            <div class="grid place-content-start gap-1.5">
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @{{ product.name }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ "@lang('admin::app.catalog.products.edit.links.sku')".replace(':sku', product.sku) }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid place-content-start gap-1 text-right">
                            <p class="font-semibold text-gray-800 dark:text-white">
                                @{{ $admin.formatPrice(product.price) }}
                            </p>

                            <p
                                class="cursor-pointer text-red-600 transition-all hover:underline"
                                @click="remove(type.key, product)"
                            >
                                @lang('admin::app.catalog.products.edit.links.delete')
                            </p>
                        </div>
                    </div>
                </div>

                <!-- For Empty Variations -->
                <div
                    class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                    v-else
                >
                    <!-- Placeholder Image -->
                    <img
                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                        class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                    />

                    <!-- Add Variants Information -->
                    <div class="flex flex-col items-center gap-1.5">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.catalog.products.edit.links.empty-title')
                        </p>

                        <p class="text-gray-400">
                            @{{ type.empty_info }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Product Search Blade Component -->
            <x-admin::products.search
                ref="productSearch"
                ::added-product-ids="addedProductIds"
                @onProductAdded="addSelected($event)"
            />
        </div>
    </script>

    <script type="module">
        app.component('v-product-links', {
            template: '#v-product-links-template',

            data() {
                return {
                    currentProduct: @json($product),

                    selectedType: 'related_products',

                    types: [
                        {
                            key: 'related_products',
                            title: `@lang('admin::app.catalog.products.edit.links.related-products.title')`,
                            info: `@lang('admin::app.catalog.products.edit.links.related-products.info')`,
                            empty_info: `@lang('admin::app.catalog.products.edit.links.related-products.empty-info')`,
                        }, {
                            key: 'up_sells',
                            title: `@lang('admin::app.catalog.products.edit.links.up-sells.title')`,
                            info: `@lang('admin::app.catalog.products.edit.links.up-sells.info')`,
                            empty_info: `@lang('admin::app.catalog.products.edit.links.up-sells.empty-info')`,
                        }, {
                            key: 'cross_sells',
                            title: `@lang('admin::app.catalog.products.edit.links.cross-sells.title')`,
                            info: `@lang('admin::app.catalog.products.edit.links.cross-sells.info')`,
                            empty_info: `@lang('admin::app.catalog.products.edit.links.cross-sells.empty-info')`,
                        }
                    ],

                    addedProducts: {
                        'up_sells': @json($product->up_sells()->with('images')->get()),

                        'cross_sells': @json($product->cross_sells()->with('images')->get()),

                        'related_products': @json($product->related_products()->with('images')->get())
                    },
                }
            },

            computed: {
                addedProductIds() {
                    let productIds = this.addedProducts[this.selectedType].map(product => product.id);

                    productIds.push(this.currentProduct.id);

                    return productIds;
                }
            },

            methods: {
                addSelected(selectedProducts) {
                    this.addedProducts[this.selectedType] = [...this.addedProducts[this.selectedType], ...selectedProducts];
                },

                remove(type, product) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.addedProducts[type] = this.addedProducts[type].filter(item => item.id !== product.id);
                        },
                    });
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