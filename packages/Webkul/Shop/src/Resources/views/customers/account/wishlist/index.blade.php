<x-shop::layouts.account>
    @section('breadcrumb')
        <x-shop::breadcrumbs name="wishlist"></x-shop::breadcrumbs>
    @endSection

    <v-wishlist-products></v-wishlist-products>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-wishlist-products-template">
            <div>
                <div class="max-lg:hidden">
                    <h2 class="text-[26px] font-medium">
                        @lang('shop::app.customers.account.wishlist.page-title')
                    </h2>

                    <div
                        class="flex items-center gap-x-[10px] border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] mr-[60px] cursor-pointer"
                        @click="removeAll"
                        v-if="wishlist.length"
                    >
                        <span class="icon-bin text-[24px]"></span>
                        @lang('shop::app.customers.account.wishlist.delete-all')
                    </div>
                </div>

                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.wishlist.index :count="4"></x-shop::shimmer.customers.account.wishlist.index>
                </template>

                <template v-else>
                    <div v-if="wishlist.length">
                        <div v-for="item in wishlist">
                            <div class="flex gap-[65px] p-[25px] items-center border-b-[1px] border-[#E9E9E9]">
                                <div class="flex gap-x-[20px]">
                                    <div class="">
                                        <div
                                            class="relative overflow-hidden rounded-[12px]  min-w-[110px] min-h-[110px] bg-[#E9E9E9] shimmer"
                                            v-show="isImageLoading"
                                        >
                                            <img class="rounded-[12px] bg-[#F5F5F5]" src="">
                                        </div>

                                        <a :href="`{{ route('shop.productOrCategory.index', '') }}/${item.item.url_key}`">
                                            <img 
                                                class="max-w-[110px] max-h-[110px] rounded-[12px]" 
                                                :src='item.item.base_image.small_image_url'
                                                alt="" 
                                                title=""
                                                @load="onImageLoad()"
                                                v-show="! isImageLoading"
                                            >
                                        </a>
                                    </div>
                                    
                                    <div class="grid gap-y-[10px]">
                                        <p 
                                            class="text-[16px]" 
                                            v-text="item.item.name"
                                        >
                                        </p>

                                        <p 
                                            class="text-[16px]" 
                                            v-text="item.item.color"
                                        >
                                        </p>

                                        <a
                                            class="text-[16px] text-[#0A49A7] cursor-pointer"
                                            @click="remove(item.id)"
                                        >
                                            @lang('shop::app.customers.account.wishlist.remove')
                                        </a>
                                    </div>
                                </div>

                                <p 
                                    class="text-[18px]" 
                                    v-html="item.item.min_price" 
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
                                    class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[54px] text-center whitespace-nowrap"
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

                        isImageLoading: true,
                        
                        wishlist: [],
                    };
                },

                mounted() {
                    this.get();
                },

               methods: {
                    onImageLoad() {
                        this.isImageLoading = false;
                    },

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
                            })
                            .catch(error => {});
                    },

                    removeAll() {
                        this.$axios.delete("{{ route('shop.api.customers.account.wishlist.destroy_all') }}", {
                                
                            })
                            .then(response => {
                                this.wishlist = [];
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
