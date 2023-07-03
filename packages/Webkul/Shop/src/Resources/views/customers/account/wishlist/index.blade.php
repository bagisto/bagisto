<x-shop::layouts.account>
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="wishlist"></x-shop::breadcrumbs>
    @endSection

    <v-wishlist-products>
        <x-shop::shimmer.customers.account.wishlist :count="4"></x-shop::shimmer.customers.account.wishlist>
    </v-wishlist-products>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-wishlist-products-template">
            <div>
                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.wishlist :count="4"></x-shop::shimmer.customers.account.wishlist>
                </template>

                <template v-else>
                    <div class="flex justify-between items-center overflow-auto journal-scroll">
                        <h2 class="text-[26px] font-medium">
                            @lang('shop::app.customers.account.wishlist.page-title')
                        </h2>

                        <div
                            class="flex items-center gap-x-[10px] border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
                            @click="removeAll"
                            v-if="wishlist.length"
                        >
                            <span class="icon-bin text-[24px]"></span>
                            @lang('shop::app.customers.account.wishlist.delete-all')
                        </div>
                    </div>

                    <div v-if="wishlist.length" class="overflow-auto journal-scroll">
                        <div v-for="item in wishlist">
                            <div class="flex gap-[40px] py-[25px] items-center border-b-[1px] border-[#E9E9E9] justify-between">
                                <div class="flex gap-x-[15px] max-w-[276px] min-w-[276px]">
                                    <div class="">
                                        <a :href="`{{ route('shop.productOrCategory.index', '') }}/${item.product.url_key}`">
                                            <x-shop::shimmer.image
                                                class="min-w-[80px] w-[80px] h-[80px] rounded-[12px]"
                                                ::src="item.product.base_image.small_image_url"                                         
                                            >
                                            </x-shop::shimmer.image>
                                        </a>
                                    </div>
                                    
                                    <div class="grid gap-y-[10px] place-content-start">
                                        <p 
                                            class="text-[16px] break-word-custom" 
                                            v-text="item.product.name"
                                        >
                                        </p>

                                        <div
                                            class="grid gap-x-[10px] gap-y-[6px] select-none"
                                            v-if="item.options.length"
                                        >
                                            <div class="">
                                                <p
                                                    class="flex gap-x-[15px] text-[16px] items-center cursor-pointer"
                                                    @click="item.option_show = ! item.option_show"
                                                >
                                                    @lang('shop::app.customers.account.wishlist.see-details')

                                                    <span
                                                        class="text-[24px]"
                                                        :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                                    ></span>
                                                </p>
                                            </div>

                                            <div class="grid gap-[8px]" v-show="item.option_show">
                                                <div class="" v-for="option in item.options">
                                                    <p class="text-[14px] font-medium">
                                                        @{{ option.attribute_name + ':' }}
                                                    </p>

                                                    <p class="text-[14px]">
                                                        @{{ option.option_label }}
                                                    </p>
                                                </div>

                                            </div>
                                        </div>

                                        <a
                                            class="text-[16px] text-[#0A49A7] cursor-pointer"
                                            @click="remove(item.id)"
                                        >
                                            @lang('shop::app.customers.account.wishlist.remove')
                                        </a>
                                    </div>
                                </div>

                                <p 
                                    class="text-[18px] max-w-[100px] min-w-[100px]" 
                                    v-html="item.product.min_price" 
                                >
                                </p>

                                <x-shop::quantity-changer
                                    name="quantity"
                                    class="flex gap-x-[25px] border rounded-[54px] border-navyBlue py-[10px] px-[20px] items-center w-[140px] max-w-full"
                                    @change="setItemQuantity($event, item)"
                                >
                                </x-shop::quantity-changer>
                                
                                <button
                                    type="button"
                                    class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[25px] rounded-[54px] text-center whitespace-nowrap"
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
                        isLoading: true,

                        wishlist: [],
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

                                this.wishlist = response.data.data
                                    .map((wishlist) => ({ ...wishlist, quantity: 1 }));
                            })
                            .catch(error => {
                            });
                    },

                    remove(id) {
                        this.$axios.delete(`{{ route('shop.api.customers.account.wishlist.destroy', '') }}/${id}`, {
                            })
                            .then(response => {
                                this.wishlist = this.wishlist.filter(wishlist => wishlist.id != id);

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {});
                    },

                    removeAll() {
                        this.$axios.delete("{{ route('shop.api.customers.account.wishlist.destroy_all') }}", {
                                
                            })
                            .then(response => {
                                this.wishlist = [];

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {});
                    },

                    moveToCart(id) {
                        let url = `{{ route('shop.api.customers.account.wishlist.move_to_cart', ':wishlist_id:') }}`;
                            url = url.replace(':wishlist_id:', id);

                        let existingItem = this.wishlist.find(item => item.id == id);

                        if (existingItem) {
                            this.$axios.post(url, {
                                    quantity: existingItem.quantity,
                                    product_id: id,
                                })
                                .then(response => {
                                    if (response.data.redirect) {
                                        window.location.href = response.data.data;

                                        this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });
                                    } else{
                                        this.$emitter.emit('update-mini-cart', response.data.data.cart);
                                        
                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    }

                                    this.wishlist = this.wishlist.filter(wishlist => wishlist.id != id);
                                })
                                .catch(error => {});
                        }
                    },

                    setItemQuantity(quantity, requestedItem) {
                        let existingItem = this.wishlist.find((item) => item.id === requestedItem.id);
                        
                        if (existingItem) {
                            existingItem.quantity = quantity;
                        }
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
