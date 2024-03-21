{!! view_render_event('bagisto.admin.sales.order.create.wishlist_items.before') !!}

<!-- Vue JS Component -->
<v-wishlist-items
    :cart="cart"
    @add-to-cart="configureAddToCart($event); stepReset()"
>
    <!-- Items Shimmer Effect -->
    <x-admin::shimmer.sales.orders.create.items />
</v-wishlist-items>

{!! view_render_event('bagisto.admin.sales.order.create.wishlist_items.after') !!}


@pushOnce('scripts')
    <script type="text/x-template" id="v-wishlist-items-template">
        <template v-if="isLoading">
            <!-- Items Shimmer Effect -->
            <x-admin::shimmer.sales.orders.create.items />
        </template>

        <template v-else>
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                <div class="flex justify-between items-center p-4">
                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.sales.orders.create.wishlist-items.title')
                    </p>
                </div>

                <!-- wishlist items -->
                <div
                    class="grid"
                    v-if="items.length"
                >
                    <div
                        class="row flex gap-2.5 p-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        v-for="item in items"
                    >
                        <!-- Image -->
                        <div
                            class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded overflow-hidden"
                            :class="{'border border-dashed border-gray-300 dark:border-gray-800 rounded dark:invert dark:mix-blend-exclusion overflow-hidden': ! item.product.images.length}"
                        >
                            <template v-if="! item.product.images.length">
                                <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                            
                                <p class="w-full absolute bottom-1.5 text-[6px] text-gray-400 text-center font-semibold">
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
                            <p class="text-base text-gray-800 dark:text-white font-semibold">
                                @{{ item.product.name }}
                            </p>

                            <!-- Item SKU -->
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.sales.orders.create.wishlist-items.sku', ['sku' => ':replace'])".replace(':replace', item.product.sku) }}
                            </p>

                            <!-- Price -->
                            <p class="text-base text-gray-800 dark:text-white font-semibold">
                                @{{ item.product.formatted_price }}
                            </p>

                            <!-- Item Options -->
                            <div
                                class="grid gap-x-2.5 gap-y-1.5 select-none"
                                v-if="item.additional?.attributes && item.additional.attributes.length"
                            >
                                <!-- Details Toggler -->
                                <p
                                    class="flex gap-1 items-center text-sm cursor-pointer"
                                    @click="item.option_show = ! item.option_show"
                                >
                                    @lang('admin::app.sales.orders.create.wishlist-items.see-details')

                                    <span
                                        class="text-2xl"
                                        :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                    ></span>
                                </p>

                                <div
                                    class="w-full grid gap-2"
                                    v-show="item.option_show"
                                >
                                    <div v-for="option in item.additional.attributes">
                                        <p class="text-sm text-gray-600">
                                            @{{ option.attribute_name + ':' }}
                                        </p>

                                        <p class="text-sm text-gray-800 font-medium">
                                            @{{ option.option_label }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item Actions -->
                            <div class="flex gap-2.5 mt-2">
                                <p
                                    class="text-red-600 cursor-pointer transition-all hover:underline"
                                    @click="removeItem(item)"
                                >
                                    @lang('admin::app.sales.orders.create.wishlist-items.delete')
                                </p>

                                <p
                                    class="text-emerald-600 cursor-pointer transition-all hover:underline"
                                    @click="moveToCart(item)"
                                >
                                    @lang('admin::app.sales.orders.create.wishlist-items.add-to-cart')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Items Box -->
                <div
                    class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5"
                    v-else
                >
                    <img src="{{ bagisto_asset('images/icon-add-product.svg') }}" class="w-20 h-20 dark:invert dark:mix-blend-exclusion">
                    
                    <div class="flex flex-col gap-1.5 items-center">
                        <p class="text-base text-gray-400 font-semibold">
                            @lang('admin::app.sales.orders.create.recent-order-items.empty-title')
                        </p>
    
                        <p class="text-gray-400">
                            @lang('admin::app.sales.orders.create.recent-order-items.empty-description')
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-wishlist-items', {
            template: '#v-wishlist-items-template',

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

                    this.$axios.get("{{ route('admin.customers.customers.wishlist.items', $cart->customer_id) }}")
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
                                qty: item.additional.quantity || 1,
                                additional: item.additional,
                            });
                        }
                    });
                },

                removeItem(item) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$axios.delete("{{ route('admin.customers.customers.wishlist.items.delete', $cart->customer_id) }}", {
                                data: {
                                    item_id: item.id
                                }
                            })
                                .then(response => {
                                    let index = this.items.findIndex(wishlistItem => wishlistItem.id === item.id);

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