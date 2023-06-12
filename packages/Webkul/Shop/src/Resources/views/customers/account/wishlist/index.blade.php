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

                <div v-if="wishlist.length">
                    <div v-for="item in wishlist" >
                        <div class="flex gap-[65px] p-[25px] items-center border-b-[1px] border-[#E9E9E9]">
                            <div class="flex gap-x-[20px]">
                                <div>
                                    <img 
                                        class="max-w-[110px] max-h-[110px] rounded-[12px]" 
                                        :src='item.base_image_url'
                                        alt=""
                                    />
                                </div>
                            </div>
    
                            <div class="grid gap-y-[10px]">
                                <p 
                                    class="text-[16px]" 
                                    v-text="item.name">
                                </p>
                        
                                <p class="text-[16px]">
                                    @lang('shop::app.customers.account.wishlist.color') 
                                    @{{ item.color }}
                                </p>
                                
                                <button 
                                    type="button"
                                    class="text-[16px] text-[#4D7EA8]" 
                                    @click="removeItem(item.product.id)"
                                >
                                    @lang('shop::app.customers.account.wishlist.remove')
                                </button>
                            </div>
                            
                            <p class="text-[18px]" v-text="item.product.price">
                            
                            </p>

                            <x-shop::quantity-changer
                                name="quantity"
                                ::value="'item.quantity'"
                                class="flex gap-x-[25px] border rounded-[54px] border-navyBlue py-[10px] px-[20px] items-center"
                                @change="updateItem($event, item)"
                            >
                            </x-shop::quantity-changer>
    
                            <x-shop::form
                                {{-- :action="route('shop.customers.account.wishlist.move_to_cart', $item->id)" --}}
                            >
                                <button class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[54px] text-center">
                                    @lang('shop::app.customers.account.wishlist.move-to-cart')
                                </button>
                            </x-shop::form>
    
                        </div>   
                    </div>
                </div>

                <div 
                    class="grid items-center justify-items-center w-max m-auto h-[476px] place-content-center" 
                    v-else
                >
                    <img 
                        class="" 
                        src="{{ bagisto_asset('images/wishlist.png') }}" 
                        alt="" 
                        title=""
                    >
                    
                    <p class="text-[20px]">
                        @lang('shop::app.customers.account.wishlist.empty')
                    </p>
                </div>   
            </div>    
        </script>
    
        <script type="module">
            app.component("v-wishlist-products", {
                template: '#v-wishlist-products-template',
    
                data() {
                    return  {
                        wishlist: [],
                    }
                },
    
               mounted() {
                    this.getWishlist();
               },
    
               methods: {
                    getWishlist() {
                        this.$axios.get("{{ route('shop.customers.wishlist.index') }}")
                            .then(response => {
                                this.wishlist = response.data.data;
                            })
                            .catch(error => {
                            });
                    },
    
                    updateItem(itemId, $event) {
                        let qty = {};
    
                        qty[itemId] = $event;
    
                        this.$axios.put('{{ route('shop.checkout.cart.update') }}', { qty })
                            .then(response => {
                                this.cart = response.data.data;
                            })
                            .catch(error => {});
                    },
    
                    removeItem(itemId) {
                        this.$axios.post("{{ route('shop.customers.account.wishlist.destroy') }}", {
                                '_method': 'DELETE',
                                'product_id': itemId,
                            })
                            .then(response => {
                                this.wishlist = response.data.data;
                            })
                            .catch(error => {});
                    },
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>