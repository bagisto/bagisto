{!! view_render_event('bagisto.admin.sales.order.create.cart.items.before') !!}

<!-- Vue JS Component -->
<v-cart-items
    :cart="cart"
    :is-adding-to-cart="isAddingToCart"
    @add-to-cart="configureAddToCart($event); stepReset()"
    @remove-from-cart="setCart($event); stepReset()"
    @cart-item-updated="setCart($event); stepReset()"
>
    <!-- Cart Items Shimmer Effect -->
    <x-admin::shimmer.sales.orders.create.cart.items />
</v-cart-items>

{!! view_render_event('bagisto.admin.sales.order.create.cart.items.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-cart-items-template">
        <div class="bg-white dark:bg-gray-900 rounded box-shadow">
            <div class="flex justify-between p-4">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('admin::app.sales.orders.create.cart.items.title')
                </p>

                <div class="flex gap-4 items-center">
                    <template v-if="isAddingToCart || isUpdating">
                        <img
                            class="animate-spin h-5 w-5"
                            src="{{ bagisto_asset('images/spinner.svg') }}"
                        />
                    </template>

                    <template v-else>
                        <p class="text-base text-gray-800 dark:text-white font-semibold">
                            @{{ "@lang('admin::app.sales.orders.create.cart.items.sub-total', ['sub_total' => 'replace'])".replace('replace', cart.formatted_sub_total) }}
                        </p>
                    </template>

                    <button
                        class="secondary-button"
                        @click="$refs.searchProductDrawer.open()"
                    >
                        @lang('admin::app.sales.orders.create.cart.items.add-product')
                    </button>
                </div>
            </div>

            <!-- Order items -->
            <div
                class="grid"
                v-if="cart.items.length"
            >
                <div
                    class="row grid gap-4 p-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="item in cart.items"
                >
                    <!-- Item Information -->
                    <div class="flex justify-between gap-2.5">
                        <div class="flex gap-2.5">
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

                            <div class="flex flex-col gap-1.5">
                                <!-- Item Name -->
                                <p class="text-base text-gray-800 whitespace-nowrap dark:text-white font-semibold">
                                    @{{ item.name }}
                                </p>

                                <!-- Item SKU -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ "@lang('admin::app.sales.orders.create.cart.items.sku', ['sku' => ':replace'])".replace(':replace', item.sku) }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ "@lang('admin::app.sales.orders.create.cart.items.amount-per-unit', ['amount' => ':replaceAmount', 'qty' => ':replaceQty'])".replace(':replaceAmount', item.formatted_price).replace(':replaceQty', item.quantity) }}
                                </p>

                                <!-- Item Options -->
                                <div
                                    class="grid gap-x-2.5 gap-y-1.5 select-none"
                                    v-if="item.options.length"
                                >
                                    <!-- Details Toggler -->
                                    <p
                                        class="flex gap-1 items-center text-sm cursor-pointer"
                                        @click="item.option_show = ! item.option_show"
                                    >
                                        @lang('admin::app.sales.orders.create.cart.items.see-details')

                                        <span
                                            class="text-2xl"
                                            :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                        ></span>
                                    </p>

                                    <div
                                        class="w-full grid gap-2"
                                        v-show="item.option_show"
                                    >
                                        <div v-for="option in item.options">
                                            <p class="text-sm text-gray-600">
                                                @{{ option.attribute_name + ':' }}
                                            </p>

                                            <p class="text-sm text-gray-800 font-medium">
                                                @{{ option.option_label }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <p class="text-base text-gray-800 text-right dark:text-white font-semibold">
                                @{{ item.formatted_total }}
                            </p>

                            <x-admin::quantity-changer
                                ::name="'qty[' + item.id + ']'"
                                ::value="item.quantity"
                                class="gap-x-4 w-max rounded-l py-1 px-4"
                                @change="updateItem(item, $event)"
                            />
                        </div>
                    </div>

                    <!-- Item Actions -->
                    <div class="flex justify-end gap-2.5">
                        <p
                            class="text-red-600 cursor-pointer transition-all hover:underline"
                            @click="removeItem(item)"
                        >
                            @lang('admin::app.sales.orders.create.cart.items.delete')
                        </p>
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
                        @lang('admin::app.sales.orders.create.cart.items.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('admin::app.sales.orders.create.cart.items.empty-description')
                    </p>
                </div>
            </div>

            <!-- Search Drawer -->
            <x-admin::drawer
                ref="searchProductDrawer"
                @close="searchTerm = ''; searchedProducts = [];"
            >
                <!-- Drawer Header -->
                <x-slot:header>
                    <div class="grid gap-5">
                        <p class="text-xl font-medium dark:text-white">
                            @lang('admin::app.sales.orders.create.cart.items.search.title')
                        </p>

                        <div class="relative w-full">
                            <input
                                type="text"
                                class="bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg block w-full ltr:pl-3 rtl:pr-3 ltr:pr-10 rtl:pl-10 py-1.5 leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                                placeholder="Search by name"
                                v-model.lazy="searchTerm"
                                v-debounce="500"
                            />

                            <template v-if="isSearching">
                                <img
                                    class="animate-spin h-5 w-5 absolute ltr:right-3 rtl:left-3 top-2.5"
                                    src="{{ bagisto_asset('images/spinner.svg') }}"
                                />
                            </template>

                            <template v-else>
                                <span class="icon-search text-2xl absolute ltr:right-3 rtl:left-3 top-1.5 flex items-center pointer-events-none"></span>
                            </template>
                        </div>
                    </div>
                </x-slot>

                <!-- Drawer Content -->
                <x-slot:content class="!p-0">
                    <div
                        class="grid"
                        v-if="searchedProducts.length"
                    >
                        <div
                            class="flex gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800"
                            v-for="product in searchedProducts"
                        >
                            <!-- Information -->
                            <div class="flex gap-2.5">
                                <!-- Image -->
                                <div
                                    class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded overflow-hidden"
                                    :class="{'border border-dashed border-gray-300 dark:border-gray-800 dark:invert dark:mix-blend-exclusion': ! product.images.length}"
                                >
                                    <template v-if="! product.images.length">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                    
                                        <p class="w-full absolute bottom-1.5 text-[6px] text-gray-400 text-center font-semibold">
                                            @lang('admin::app.sales.orders.create.cart.items.search.product-image')
                                        </p>
                                    </template>

                                    <template v-else>
                                        <img :src="product.images[0].url">
                                    </template>
                                </div>

                                <!-- Details -->
                                <div class="grid gap-1.5 place-content-start">
                                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                                        @{{ product.name }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.sales.orders.create.cart.items.search.sku')".replace(':sku', product.sku) }}
                                    </p>

                                    <p class="text-green-600">
                                        @{{ "@lang('admin::app.sales.orders.create.cart.items.search.available-qty')".replace(':qty', availbleQty(product)) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <x-admin::form
                                v-slot="{ meta, errors, handleSubmit }"
                                as="div"
                            >
                                <form @submit="handleSubmit($event, addToCart)">
                                    <div class="grid gap-2 place-content-start text-right">
                                        <p class="text-gray-800 font-semibold dark:text-white">
                                            @{{ product.formatted_price }}
                                        </p>

                                        <x-admin::form.control-group class="!mb-0">
                                            <x-admin::form.control-group.label class="required justify-end">
                                                @lang('admin::app.sales.orders.create.cart.items.search.qty')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="product_id"
                                                ::value="product.id"
                                            />

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="qty"
                                                class="!w-[80px] !py-1.5 !px-2"
                                                value="1"
                                                rules="required|numeric|min_value:1"
                                                :label="trans('admin::app.sales.orders.create.cart.items.search.qty')"
                                                :placeholder="trans('admin::app.sales.orders.create.cart.items.search.qty')"
                                            />

                                            <x-admin::form.control-group.error name="qty" />
                                        </x-admin::form.control-group>

                                        <button class="text-sm text-blue-600 cursor-pointer transition-all hover:underline">
                                            @lang('admin::app.sales.orders.create.cart.items.search.add-to-cart')
                                        </button>
                                    </div>
                                </form>
                            </x-admin::form>
                        </div>
                    </div>

                    <!-- For Empty Variations -->
                    <div
                        class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5"
                        v-else
                    >
                        <!-- Placeholder Image -->
                        <img
                            src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                            class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                        />

                        <!-- Add Variants Information -->
                        <div class="flex flex-col gap-1.5 items-center">
                            <p class="text-base text-gray-400 font-semibold">
                                @lang('admin::app.sales.orders.create.cart.items.search.empty-title')
                            </p>

                            <p class="text-gray-400">
                                @lang('admin::app.sales.orders.create.cart.items.search.empty-info')
                            </p>
                        </div>
                    </div>
                </x-slot>
            </x-admin::drawer>
        </div>
    </script>

    <script type="module">
        app.component('v-cart-items', {
            template: '#v-cart-items-template',

            props: ['cart', 'isAddingToCart'],

            emits: ['add-to-cart', 'remove-from-cart'],

            data() {
                return {
                    searchTerm: '',

                    searchedProducts: [],

                    isSearching: false,

                    isUpdating: false,
                };
            },

            watch: {
                searchTerm: function(newVal, oldVal) {
                    this.search();
                }
            },

            methods: {
                search() {
                    if (this.searchTerm.length <= 1) {
                        this.searchedProducts = [];

                        return;
                    }

                    this.isSearching = true;

                    let self = this;
                    
                    this.$axios.get("{{ route('admin.catalog.products.search') }}", {
                            params: {
                                query: this.searchTerm
                            }
                        })
                        .then(function(response) {
                            self.isSearching = false;

                            self.searchedProducts = response.data.data;
                        })
                        .catch(function (error) {
                        });
                },

                addToCart(params) {
                    this.$emit('add-to-cart', {
                        product: this.searchedProducts.find(product => product.id == params.product_id),
                        qty: params.qty
                    });

                    this.$refs.searchProductDrawer.close();
                },

                removeItem(item) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$axios.delete("{{ route('admin.sales.cart.items.destroy', $cart->id) }}", {
                                data: {
                                    cart_item_id: item.id
                                }
                            })
                                .then(response => {
                                    this.$emit('remove-from-cart', response.data.data);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch(error => {});
                        }
                    });
                },

                updateItem(item, qty) {
                    this.isUpdating = true;

                    let params = {
                        qty: {
                            [item.id]: qty
                        }
                    };

                    this.$axios.put("{{ route('admin.sales.cart.items.update', $cart->id) }}", params)
                        .then(response => {
                            this.$emit('cart-item-updated', response.data.data);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.isUpdating = false;

                        })
                        .catch(error => {
                            this.isUpdating = false;
                        });
                },

                availbleQty(product) {
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