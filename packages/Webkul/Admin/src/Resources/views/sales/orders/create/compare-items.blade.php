{!! view_render_event('bagisto.admin.sales.order.create.compare_items.before') !!}

<!-- Vue JS Component -->
<v-compare-items
    :cart="cart"
    @add-to-cart="configureAddToCart($event); stepReset()"
>
    <!-- Items Shimmer Effect -->
    <x-admin::shimmer.sales.orders.create.items />
</v-compare-items>

{!! view_render_event('bagisto.admin.sales.order.create.compare_items.after') !!}


@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-compare-items-template"
    >
        <template v-if="isLoading">
            <!-- Items Shimmer Effect -->
            <x-admin::shimmer.sales.orders.create.items />
        </template>

        <template v-else>
            <div class="box-shadow rounded bg-white dark:bg-gray-900">
                <div class="flex items-center justify-between p-4">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.sales.orders.create.compare-items.title')
                    </p>
                </div>

                <!-- Compare items -->
                <div
                    class="grid"
                    v-if="items.length"
                >
                    <div
                        class="row flex gap-2.5 border-b bg-white p-4 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950"
                        v-for="item in items"
                    >
                        <!-- Image -->
                        <div
                            class="relative h-[60px] max-h-[60px] w-full max-w-[60px] overflow-hidden rounded"
                            :class="{'overflow-hidden rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert': ! item.product.images.length}"
                        >
                            <template v-if="! item.product.images.length">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                            
                                <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                    @lang('admin::app.catalog.products.edit.types.grouped.image-placeholder')
                                </p>
                            </template>

                            <template v-else>
                                <img :src="item.product.images[0].url">
                            </template>
                        </div>

                        <!-- Item Information -->
                        <div class="grid gap-1.5">
                            <!-- Item Name -->
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @{{ item.product.name }}
                            </p>

                            <!-- Item SKU -->
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.sales.orders.create.compare-items.sku', ['sku' => ':replace'])".replace(':replace', item.product.sku) }}
                            </p>
                            
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @{{ item.product.formatted_price }}
                            </p>

                            <!-- Item Actions -->
                            <div class="mt-2 flex gap-2.5">
                                <button
                                    class="cursor-pointer text-sm text-red-600 transition-all hover:underline"
                                    @click="removeItem(item)"
                                >
                                    @lang('admin::app.sales.orders.create.compare-items.delete')
                                </button>

                                <button
                                    class="cursor-pointer text-sm text-emerald-600 transition-all hover:underline"
                                    :disabled="! item.product.is_saleable"
                                    @click="moveToCart(item)"
                                >
                                    @lang('admin::app.sales.orders.create.compare-items.add-to-cart')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Items Box -->
                <div
                    class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                    v-else
                >
                    <img src="{{ bagisto_asset('images/icon-add-product.svg') }}" class="h-20 w-20 dark:mix-blend-exclusion dark:invert">
                    
                    <div class="flex flex-col items-center gap-1.5">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.sales.orders.create.compare-items.empty-title')
                        </p>
    
                        <p class="text-gray-400">
                            @lang('admin::app.sales.orders.create.compare-items.empty-description')
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-compare-items', {
            template: '#v-compare-items-template',

            props: ['cart'],

            emits: ['add-to-cart'],

            data() {
                return {
                    isLoading: false,

                    items: [],
                };
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('admin.customers.customers.compare.items', $cart->customer_id) }}")
                        .then(response => {
                            this.items = response.data.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },

                moveToCart(item) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$emit('add-to-cart', {
                                product: item.product,
                                qty: 1,
                            });
                        }
                    });
                },

                removeItem(item) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$axios.delete("{{ route('admin.customers.customers.compare.items.delete', $cart->customer_id) }}", {
                                data: {
                                    item_id: item.id
                                }
                            })
                                .then(response => {
                                    let index = this.items.findIndex(compareItem => compareItem.id === item.id);

                                    if (index !== -1) {
                                        this.items.splice(index, 1);
                                    }

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {});
                        }
                    });
                },
            }
        });
    </script>
@endPushOnce