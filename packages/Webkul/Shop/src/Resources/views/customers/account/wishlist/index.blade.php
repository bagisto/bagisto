<x-shop::layouts.account>
    <v-wishlist-products></v-wishlist-products>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-wishlist-products-template">
            <div>
                <div class="max-lg:hidden">
                    <div class="flex gap-x-[4px] items-center mb-[10px]">
                        <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/']">
                            @lang('shop::app.customers.account.wishlist.profile')
                        </p>

                        <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                            @lang('shop::app.customers.account.wishlist.title')
                        </p>
                    </div>

                    <h2 class="text-[26px] font-medium">
                        @lang('shop::app.customers.account.wishlist.page-title')
                    </h2>
                </div>

                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.wishlist.index :count="4"></x-shop::shimmer.customers.account.wishlist.index>
                </template>

                <template v-else>
                    <div v-if="wishlist.length">
                        <div v-for="item in wishlist">
                            <div class="flex gap-[65px] p-[25px] items-center border-b-[1px] border-[#E9E9E9]">
                                <div class="flex gap-x-[20px]">
                                    <div>
                                        <a :href="`{{ route('shop.productOrCategory.index', '') }}/${item.item.url_key}`">
                                            <img
                                                :src='item.item.base_image.small_image_url'
                                                class="max-w-[110px] max-h-[110px] rounded-[12px]"
                                                alt=""
                                            />
                                        </a>
                                    </div>
                                </div>

                                <div class="grid gap-y-[10px]">
                                    <p
                                        class="text-[16px]"
                                        v-text="item.item.name">
                                    </p>

                                    <p class="text-[16px]">
                                        @lang('shop::app.customers.account.wishlist.color')

                                        @{{ item.item.color }}
                                    </p>

                                    <button
                                        type="button"
                                        class="text-[16px] text-[#4D7EA8]"
                                        @click="remove(item.id)"
                                    >
                                        @lang('shop::app.customers.account.wishlist.remove')
                                    </button>
                                </div>

                                <p
                                    class="text-[18px]"
                                    v-html="item.item.price_html"
                                >
                                </p>

                                <x-shop::quantity-changer
                                    name="quantity"
                                    value="1"
                                    class="flex gap-x-[25px] border rounded-[54px] border-navyBlue py-[10px] px-[20px] items-center"
                                    @change="updateQuantity($event, item)"
                                >
                                </x-shop::quantity-changer>

                                <button
                                    class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[54px] text-center"
                                    @click="moveToCart(item.id)"
                                >
                                    @lang('shop::app.customers.account.wishlist.move-to-cart')
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        class="grid items-center justify-items-center w-max m-auto h-[476px] place-content-center"
                        v-else
                    >
                        <img
                            src="{{ bagisto_asset('images/wishlist.png') }}"
                            class=""
                            alt=""
                            title=""
                        >

                        <p class="text-[20px]">
                            @lang('shop::app.customers.account.wishlist.empty')
                        </p>
                    </div>
                </template>
            </div>
        </script>

        <script type="module">
            app.component("v-wishlist-products", {
                template: '#v-wishlist-products-template',

                data() {
                    return  {
                        wishlist: [],

                        quantity: 1,

                        isLoading: true,
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

                                this.wishlist = response.data.data;
                            })
                            .catch(error => {
                            });
                    },

                    remove(id) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.destroy', '') }}/${id}`, {
                                '_method': 'DELETE',
                            })
                            .then(response => {
                                this.wishlist = response.data.data;
                            })
                            .catch(error => {});
                    },

                    moveToCart(id) {
                        /**
                         *  To Do (@devansh-webkul):
                         *
                         * - Need global helper method to convert laravel named route to js url.
                         */
                        let url = `{{ route('shop.api.customers.account.wishlist.move_to_cart', ':wishlist_id:') }}`;                        
                        url = url.replace(':wishlist_id:', id);

                        this.$axios.post(url, {
                                quantity: this.quantity,
                                product_id: id,
                            })
                            .then(response => {
                                if (response.data.redirect) {
                                    window.location.href = response.data.data;
                                }

                                this.wishlist = response.data.data;
                            })
                            .catch(error => {});
                    },

                    updateQuantity(value) {
                        this.quantity = value;
                    }
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
