<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.wishlist.page-title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="wishlist" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto">
        <!-- Wishlist Vue Component -->
        <v-wishlist-products>
            <!-- Wishlist Shimmer Effect -->
            <x-shop::shimmer.customers.account.wishlist :count="4" />
        </v-wishlist-products>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-wishlist-products-template"
        >
            <div>
                <!-- Wishlist Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.wishlist :count="4" />
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before') !!}

                <!-- Wishlist Information -->
                <template v-else>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <!-- Back Button -->
                            <a
                                class="grid md:hidden"
                                href="{{ route('shop.customers.account.index') }}"
                            >
                                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>                                    
                            </a>

                            <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                                @lang('shop::app.customers.account.wishlist.page-title')
                            </h2>
                        </div>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.delete_all.before') !!}

                        <div
                            class="secondary-button border-zinc-200 px-5 py-3 font-normal max-md:rounded-lg max-md:py-2 max-sm:py-1.5 max-sm:text-sm"
                            @click="removeAll"
                            v-if="wishlistItems.length"
                        >
                            @lang('shop::app.customers.account.wishlist.delete-all')
                        </div>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.delete_all.after') !!}
                    </div>

                    <div 
                        v-if="wishlistItems.length" 
                        v-for="(item, index) in wishlistItems"
                        class="mt-8 flex flex-wrap gap-20 max-1060:flex-col max-md:my-5 max-md:last:mb-0"
                    >
                        <div class="grid flex-1 gap-8 max-md:flex-none">
                            <div class="grid gap-y-6 max-md:gap-y-0">
                                <!-- Wishlist item -->
                                <div class="flex justify-between gap-x-2.5 border-b border-zinc-200 pb-5">
                                    <div class="flex gap-x-5 max-md:w-full max-md:gap-x-5">
                                        <div>
                                            {!! view_render_event('bagisto.shop.customers.account.wishlist.image.before') !!}

                                            <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product.url_key}`">
                                                <!-- Wishlist Item Image -->
                                                <img 
                                                    class="h-28 max-h-28 w-28 max-w-28 rounded-xl max-md:h-20 max-md:max-h-20 max-md:w-20 max-md:max-w-20"
                                                    :src="item.product.base_image.small_image_url" 
                                                    alt="Product Image"
                                                /> 
                                            </a>

                                            {!! view_render_event('bagisto.shop.customers.account.wishlist.image.after') !!}
                                        </div>

                                        <div class="grid gap-y-2.5 max-md:w-full max-md:gap-y-0">
                                            <div class="flex justify-between">
                                                <p class="text-base font-medium max-md:text-sm">
                                                    @{{ item.product.name }}
                                                </p>

                                                <span
                                                    @click="remove(item.id)" 
                                                    class="icon-bin hidden text-2xl max-md:block"
                                                >
                                                </span>
                                            </div>

                                            <!--Wishlist Item attributes -->
                                            <div
                                                class="flex flex-wrap gap-x-2.5 gap-y-1.5"
                                                v-if="item.options?.attributes"
                                            >
                                                <div class="grid gap-2">
                                                    <div>
                                                        <p
                                                            class="flex cursor-pointer items-center gap-x-4 text-base"
                                                            @click="item.option_show = ! item.option_show"
                                                        >
                                                            @lang('shop::app.customers.account.wishlist.see-details')
        
                                                            <span
                                                                class="text-2xl"
                                                                :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                                            >
                                                            </span>
                                                        </p>
                                                    </div>

                                                    <div
                                                        class="grid gap-2" 
                                                        v-show="item.option_show"
                                                    >
                                                        <div v-for="option in item.options?.attributes">
                                                            <p class="text-sm font-medium">
                                                                @{{ option.attribute_name + ':' }}
                                                            </p>
        
                                                            <p class="text-sm">
                                                                @{{ option.option_label }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="max-md:block md:hidden">
                                                <p 
                                                    class="text-lg font-semibold max-md:text-sm" 
                                                    v-html="item.product.min_price"
                                                >
                                                </p>

                                                {!! view_render_event('bagisto.shop.customers.account.wishlist.remove_button.before') !!}

                                                <!--Wishlist Item removed button-->
                                                <a 
                                                    class="flex cursor-pointer justify-end text-base text-blue-700 max-md:hidden" 
                                                    @click="remove(item.id)"
                                                >
                                                    @lang('shop::app.customers.account.wishlist.remove')
                                                </a>

                                                {!! view_render_event('bagisto.shop.customers.account.wishlist.remove_button.after') !!}

                                            </div>

                                            {!! view_render_event('bagisto.shop.customers.account.wishlist.perform_actions.before') !!}

                                            <div class="flex gap-5 max-md:mt-2.5">
                                                <x-shop::quantity-changer
                                                    name="quantity"
                                                    ::value="item.options.quantity ?? 1"
                                                    class="flex max-h-10 items-center gap-x-2.5 rounded-[54px] border border-navyBlue px-3.5 py-1.5 max-md:gap-x-1 max-md:px-1.5 max-md:py-1"
                                                    @change="setItemQuantity($event, item)"
                                                />

                                                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                                    <!--Wishlist Item Move-to-cart-->
                                                    <x-shop::button
                                                        class="primary-button max-h-10 w-max rounded-2xl px-6 py-1.5 text-center text-base max-md:px-4 max-md:py-1.5 max-md:text-sm"
                                                        :title="trans('shop::app.customers.account.wishlist.move-to-cart')"
                                                        ::loading="isMovingToCart[item.id]"
                                                        ::disabled="isMovingToCart[item.id]"
                                                        @click="moveToCart(item.id,index)"
                                                    />
                                                @endif
                                            </div>

                                            {!! view_render_event('bagisto.shop.customers.account.wishlist.perform_actions.after') !!}
                                        </div>
                                    </div>

                                    <div class="max-md:hidden">
                                        <p 
                                            class="text-lg font-semibold" 
                                            v-html="item.product.min_price"
                                        >
                                        </p>

                                        <a 
                                            class="flex cursor-pointer justify-end text-base text-blue-700" 
                                            @click="remove(item.id)"
                                        >
                                            @lang('shop::app.customers.account.wishlist.remove')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Empty Wishlist-->
                    <div
                        class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center"
                        v-else
                    >
                        <img
                            class="max-md:h-[100px] max-md:w-[100px]"
                            src="{{ bagisto_asset('images/wishlist.png') }}"
                            alt="Empty wishlist"
                        >

                        <p
                            class="text-xl max-md:text-sm"
                            role="heading"
                        >
                            @lang('shop::app.customers.account.wishlist.empty')
                        </p>
                    </div>
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after') !!}

            </div>
        </script>

        <script type="module">
            app.component("v-wishlist-products", {
                template: '#v-wishlist-products-template',

                data() {
                    return {
                        isLoading: true,

                        isMovingToCart: {},

                        wishlistItems: [],
                    };
                },

                mounted() {
                    this.get();
                },

                methods: {
                    get() {
                        this.$axios.get("{{ route('shop.api.customers.account.wishlist.index') }}")
                            .then(response => {
                                this.isLoading = false;

                                this.wishlistItems = response.data.data;
                            })
                            .catch(error => {});
                    },

                    remove(id) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios.delete(`{{ route('shop.api.customers.account.wishlist.destroy', '') }}/${id}`)
                                    .then(response => {
                                        this.wishlistItems = this.wishlistItems.filter(item => item.id != id);

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                                }
                        });
                    },

                    removeAll() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {

                            this.$axios.post("{{ route('shop.api.customers.account.wishlist.destroy_all') }}", {
                                    '_method': 'DELETE',
                                })
                                .then(response => {
                                    this.wishlistItems = [];

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {});
                            }
                        });
                    },

                    moveToCart(id, index) {
                        this.isMovingToCart[id] = true;

                        let url = `{{ route('shop.api.customers.account.wishlist.move_to_cart', ':wishlist_id:') }}`.replace(':wishlist_id:', id);

                        let existingItem = this.wishlistItems.find(item => item.id == id);

                        if (! existingItem) {
                            return
                        }

                        this.$axios.post(url, {
                                quantity: existingItem.quantity ?? existingItem.options.quantity,
                                product_id: id
                            })
                            .then(response => {
                                if (response.data.redirect) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });

                                    window.location.href = response.data.data;
                                } else {
                                    this.wishlistItems = this.wishlistItems.filter(item => item.id != id);

                                    this.$emitter.emit('update-mini-cart', response.data.data.cart);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                }

                                this.isMovingToCart[id] = false;
                            })
                            .catch(error => {
                                this.isMovingToCart[id] = false;
                            });
                    },

                    setItemQuantity(quantity, requestedItem) {
                        let existingItem = this.wishlistItems.find((item) => item.id === requestedItem.id);

                        if (existingItem) {
                            existingItem.quantity = quantity;
                        }
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>