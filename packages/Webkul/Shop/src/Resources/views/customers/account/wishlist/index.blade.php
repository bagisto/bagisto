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

                    <!-- Wishlist Items -->
                    <template v-if="wishlistItems.length">
                        <v-wishlist-products-item
                            v-for="(wishlist, index) in wishlistItems"
                            :wishlist="wishlist"
                            :key="wishlist.id"
                            @wishlist-items="(items) => wishlistItems = items"
                        >
                            <x-shop::shimmer.customers.account.wishlist />
                        </v-wishlist-products-item>
                    </template>

                    <!-- Empty Wishlist -->
                    <template v-else>
                        <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
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
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after') !!}
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-wishlist-products-item-template"
        >
            <div class="mt-8 flex flex-wrap gap-20 max-1060:flex-col max-md:my-5 max-md:last:mb-0">
                <div class="grid flex-1 gap-8 max-md:flex-none">
                    <div class="grid gap-y-6 max-md:gap-y-0">
                        <!-- Wishlist item -->
                        <div class="flex justify-between gap-x-2.5 border-b border-zinc-200 pb-5">
                            <div class="flex gap-x-5 max-md:w-full max-md:gap-x-5">
                                <div>
                                    {!! view_render_event('bagisto.shop.customers.account.wishlist.image.before') !!}

                                    <a :href="`{{ route('shop.product_or_category.index', '') }}/${wishlist.product.url_key}`">
                                        <!-- Wishlist Item Image -->
                                        <img
                                            class="h-28 max-h-28 w-28 max-w-28 rounded-xl max-md:h-20 max-md:max-h-20 max-md:w-20 max-md:max-w-20"
                                            :src="wishlist.product.base_image.small_image_url"
                                            alt="Product Image"
                                        />
                                    </a>

                                    {!! view_render_event('bagisto.shop.customers.account.wishlist.image.after') !!}
                                </div>

                                <div class="grid gap-y-2.5 max-md:w-full max-md:gap-y-0">
                                    <div class="flex justify-between">
                                        <p class="text-base font-medium max-md:text-sm">
                                            @{{ wishlist.product.name }}
                                        </p>

                                        <span
                                            @click="remove"
                                            class="icon-bin hidden text-2xl max-md:block"
                                        ></span>
                                    </div>

                                    <!--Wishlist Item attributes -->
                                    <div
                                        class="flex flex-wrap gap-x-2.5 gap-y-1.5"
                                        v-if="wishlist.options?.attributes"
                                    >
                                        <div class="grid gap-2">
                                            <div>
                                                <p
                                                    class="flex cursor-pointer items-center gap-x-4 text-base"
                                                    @click="wishlist.option_show = ! wishlist.option_show"
                                                >
                                                    @lang('shop::app.customers.account.wishlist.see-details')

                                                    <span
                                                        class="text-2xl"
                                                        :class="{
                                                            'icon-arrow-up': wishlist.option_show,
                                                            'icon-arrow-down': ! wishlist.option_show
                                                        }"
                                                    ></span>
                                                </p>
                                            </div>

                                            <div
                                                class="grid gap-2"
                                                v-show="wishlist.option_show"
                                            >
                                                <div v-for="option in wishlist.options?.attributes">
                                                    <p class="text-sm font-medium">
                                                        @{{ option.attribute_name + ':' }}
                                                    </p>

                                                    <p class="text-sm">
                                                        <template v-if="option?.attribute_type === 'file'">
                                                            <a
                                                                :href="option.file_url"
                                                                class="text-blue-700"
                                                                target="_blank"
                                                                :download="option.file_name"
                                                            >
                                                                @{{ option.file_name }}
                                                            </a>
                                                        </template>

                                                        <template v-else>
                                                            @{{ option.option_label }}
                                                        </template>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="max-md:block md:hidden">
                                        <p
                                            class="text-lg font-semibold max-md:text-sm"
                                            v-html="wishlist.product.price_html"
                                        ></p>

                                        {!! view_render_event('bagisto.shop.customers.account.wishlist.remove_button.before') !!}

                                        <!--Wishlist Item removed button-->
                                        <a
                                            class="flex cursor-pointer justify-end text-base text-blue-700 max-md:hidden"
                                            @click="remove"
                                        >
                                            @lang('shop::app.customers.account.wishlist.remove')
                                        </a>

                                        {!! view_render_event('bagisto.shop.customers.account.wishlist.remove_button.after') !!}
                                    </div>

                                    {!! view_render_event('bagisto.shop.customers.account.wishlist.perform_actions.before') !!}

                                    <div class="flex gap-5 max-md:mt-2.5">
                                        <x-shop::quantity-changer
                                            name="quantity"
                                            ::value="wishlist.options.quantity ?? 1"
                                            class="flex max-h-10 items-center gap-x-2.5 rounded-[54px] border border-navyBlue px-3.5 py-1.5 max-md:gap-x-1 max-md:px-1.5 max-md:py-1"
                                            @change="(qty) => wishlist.quantity = qty"
                                        />

                                        @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                            <!--Wishlist Item Move-to-cart-->
                                            <x-shop::button
                                                class="primary-button max-h-10 w-max rounded-2xl px-6 py-1.5 text-center text-base max-md:px-4 max-md:py-1.5 max-md:text-sm"
                                                :title="trans('shop::app.customers.account.wishlist.move-to-cart')"
                                                ::loading="movingToCart"
                                                ::disabled="movingToCart"
                                                @click="moveToCart"
                                            />
                                        @endif
                                    </div>

                                    {!! view_render_event('bagisto.shop.customers.account.wishlist.perform_actions.after') !!}
                                </div>
                            </div>

                            <div class="max-md:hidden">
                                <p
                                    class="text-lg font-semibold"
                                    v-html="wishlist.product.price_html"
                                >
                                </p>

                                <a
                                    class="flex cursor-pointer justify-end text-base text-blue-700"
                                    @click="remove"
                                >
                                    @lang('shop::app.customers.account.wishlist.remove')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component("v-wishlist-products", {
                template: '#v-wishlist-products-template',

                data() {
                    return {
                        isLoading: true,

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
                            },
                        });
                    },
                },
            });

            app.component('v-wishlist-products-item', {
                template: '#v-wishlist-products-item-template',

                props: ['wishlist'],

                emits: ['wishlist-items'],

                data() {
                    return {
                        movingToCart: false,
                    };
                },

                methods: {
                    remove() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios
                                    .delete(`{{ route('shop.api.customers.account.wishlist.destroy', '') }}/${this.wishlist.id}`)
                                    .then(response => {
                                        this.$emit('wishlist-items', response.data.data);

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            },
                        });
                    },

                    moveToCart() {
                        this.movingToCart = true;

                        const endpoint = `{{ route('shop.api.customers.account.wishlist.move_to_cart', ':wishlistId:') }}`.replace(':wishlistId:', this.wishlist.id);

                        this.$axios.post(endpoint, {
                                quantity: (this.wishlist.quantity ?? this.wishlist.options.quantity) ?? 1,
                                product_id: this.wishlist.product.id,
                            })
                            .then(response => {
                                if (response.data?.redirect) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });

                                    window.location.href = response.data.data;

                                    return;
                                }

                                this.$emit('wishlist-items', response.data.data?.wishlist);

                                this.$emitter.emit('update-mini-cart', response.data.data.cart);

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.movingToCart = false;
                            })
                            .catch(error => {
                                this.movingToCart = false;

                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            });
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
