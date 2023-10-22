<x-shop::layouts.account>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.account.wishlist.page-title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="wishlist"></x-shop::breadcrumbs>
    @endSection

    <!-- Wishlist Vue Component -->
    <v-wishlist-products>
        <!-- Wishlist Shimmer Effect -->
        <x-shop::shimmer.customers.account.wishlist :count="4"></x-shop::shimmer.customers.account.wishlist>
    </v-wishlist-products>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-wishlist-products-template">
            <div>
                <!-- Wishlist Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.wishlist :count="4"></x-shop::shimmer.customers.account.wishlist>
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before') !!}

                <!-- Wishlist Information -->
                <template v-else>
                    <div class="flex justify-between items-center overflow-auto journal-scroll">
                        <h2 class="text-[26px] font-medium">
                            @lang('shop::app.customers.account.wishlist.page-title')
                        </h2>

                        <div
                            class="secondary-button flex gap-x-[10px] items-center py-[12px] px-[20px] border-[#E9E9E9]"
                            @click="removeAll"
                            v-if="wishlist.length"
                        >
                            <span class="icon-bin text-[24px]"></span>
                            @lang('shop::app.customers.account.wishlist.delete-all')
                        </div>
                    </div>

                    <div 
                        v-if="wishlist.length" 
                        v-for="item in wishlist"
                        class="flex gap-[75px] flex-wrap mt-[30px] max-1060:flex-col"
                    >
                        <div class="grid gap-[30px] flex-1">
                            <div class="grid gap-y-[25px]">
                                <!-- Wishlist item -->
                                <div class="flex gap-x-[10px] justify-between pb-[18px] border-b-[1px] border-[#E9E9E9]">
                                    <div class="flex gap-x-[20px]">
                                        <div class="">
                                            <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product.url_key}`">
                                                <!-- Wishlist Item Image -->
                                                <x-shop::media.images.lazy
                                                    class="max-w-[110px] max-h-[110px] min-w-[110px] w-[110px] h-[110px] rounded-[12px]" 
                                                    ::src="item.product.base_image.small_image_url"                                         
                                                >
                                                </x-shop::media.images.lazy>
                                            </a>
                                        </div>

                                        <div class="grid gap-y-[10px]">
                                            <p 
                                                class="text-[16px] font-medium" 
                                                v-text="item.product.name"
                                            >
                                            </p>

                                            <!--Wishlist Item attributes -->
                                            <div 
                                                class="flex gap-x-[10px] gap-y-[6px] flex-wrap"
                                                v-if="item.options.length"
                                            >
                                                <div class="grid gap-[8px]">
                                                    <div class="">
                                                        <p
                                                            class="flex gap-x-[15px] text-[16px] items-center cursor-pointer"
                                                            @click="item.option_show = ! item.option_show"
                                                        >
                                                            @lang('shop::app.customers.account.wishlist.see-details')
        
                                                            <span
                                                                class="text-[24px]"
                                                                :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                                            >
                                                            </span>
                                                        </p>
                                                    </div>
        
                                                    <div 
                                                        class="grid gap-[8px]" 
                                                        v-show="item.option_show"
                                                    >
                                                        <div v-for="option in item.options">
                                                            <p class="text-[14px] font-medium">
                                                                @{{ option.attribute_name + ':' }}
                                                            </p>
        
                                                            <p class="text-[14px]">
                                                                @{{ option.option_label }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="sm:hidden">
                                                <p 
                                                    class="text-[18px] font-semibold" 
                                                    v-html="item.product.min_price"
                                                >
                                                </p>

                                                <!--Wishlist Item removed button-->
                                                <a 
                                                    class="flex justify-end text-[16px] text-[#0A49A7] cursor-pointer" 
                                                    @click="remove(item.id)"
                                                >
                                                    @lang('shop::app.customers.account.wishlist.remove')
                                                </a>
                                            </div>

                                            <div class="flex gap-[20px] flex-wrap">
                                                <x-shop::quantity-changer
                                                    name="quantity"
                                                    class="flex gap-x-[10px] items-center max-h-[40px] py-[5px] px-[14px] border border-navyBlue  rounded-[54px]"
                                                    @change="setItemQuantity($event, item)"
                                                >
                                                </x-shop::quantity-changer>

                                                <!--Wishlist Item Move-to-cart-->
                                                <button
                                                    type="button"
                                                    class="primary-button block w-max max-h-[40px] py-[6px] px-[25px] rounded-[18px] text-base text-center"
                                                    @click="moveToCart(item.id)"
                                                >
                                                    @lang('shop::app.customers.account.wishlist.move-to-cart')
                                                </button>   
                                            </div>
                                        </div>
                                    </div>

                                    <div class="max-sm:hidden">
                                        <p 
                                            class="text-[18px] font-semibold" 
                                            v-html="item.product.min_price"
                                        >
                                        </p>

                                        <a 
                                            class="flex justify-end text-[16px] text-[#0A49A7] cursor-pointer" 
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
                        class="grid items-center justify-items-center w-[100%] m-auto h-[476px] place-content-center text-center"
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

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after') !!}

            </div>
        </script>

        <script type="module">
            app.component("v-wishlist-products", {
                template: '#v-wishlist-products-template',

                data() {
                    return {
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
                            .catch(error => {});
                    },

                    remove(id) {
                        this.$axios.delete(`{{ route('shop.api.customers.account.wishlist.destroy', '') }}/${id}`)
                            .then(response => {
                                this.wishlist = this.wishlist.filter(wishlist => wishlist.id != id);

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {});
                    },

                    removeAll() {
                        this.$axios.delete("{{ route('shop.api.customers.account.wishlist.destroy_all') }}")
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
                                    product_id: id
                                })
                                .then(response => {
                                    if (response.data.redirect) {
                                        this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });

                                        window.location.href = response.data.data;
                                    } else {
                                        this.wishlist = this.wishlist.filter(wishlist => wishlist.id != id);

                                        this.$emitter.emit('update-mini-cart', response.data.data.cart);

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    }
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
