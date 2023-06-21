<v-mini-cart>
    <span class="icon-cart text-[24px] cursor-pointer"></span>
</v-mini-cart>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mini-cart-template">
        <x-shop::drawer>
            <x-slot:toggle>
                <span class="icon-cart text-[24px] cursor-pointer"></span>
            </x-slot:toggle>

            <x-slot:header>
                <div class="flex justify-between items-center">
                    <p class="text-[26px] font-medium">
                        @lang('shop::app.checkout.cart.shopping-cart')
                    </p>
                </div>

                <p class="text-[16px]">
                    @lang('shop::app.checkout.cart.offer-on-orders')
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid gap-[50px] mt-[35px]" v-if="cart?.items?.length">
                    <div class="flex gap-x-[20px]" v-for="item in cart?.items">
                        <div class="">
                            <img 
                                :src="item.images.small_image_url ?? item.base_image.small_image_url"
                                class="max-w-[110px] max-h-[110px] rounded-[12px]"
                                alt="" 
                                title=""
                            >
                        </div>
    
                        <div class="grid gap-y-[10px] flex-1">
                            <div class="flex flex-wrap justify-between">
                                <p 
                                    class="text-[16px] font-medium max-w-[80%]" 
                                    v-text="item.name"
                                >
                                </p>

                                <p 
                                    class="text-[18px]" 
                                    v-text="item.formatted_price"
                                >
                                </p>
                            </div>
    
                            <div class="flex gap-[20px] items-center flex-wrap">
                                <x-shop::quantity-changer
                                    name="quantity"
                                    ::value="item?.quantity"
                                    class="gap-x-[20px] rounded-[54px] py-[5px] px-[14px] max-w-[150px] max-h-[36px]"
                                    @change="updateItem($event, item)"
                                >
                                </x-shop::quantity-changer>
                                
                                <button 
                                    type="button"
                                    class="text-[#4D7EA8]"
                                    @click="removeItem(item.id)"
                                >
                                    @lang('shop::app.checkout.cart.remove')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div 
                    class="pb-[30px]" 
                    v-else
                >
                    <div class="grid gap-y-[20px] b-0 place-items-center">
                        <img 
                            src="{{ bagisto_asset('images/thank-you.png') }}" 
                            class="" 
                            alt="" 
                            title=""
                        >
                
                        <p class="text-[20px]">
                            @lang('shop::app.checkout.cart.empty-cart')
                        </p>
                    </div>
                </div>
            </x-slot:content>

            <x-slot:footer>
                <div v-if="cart?.items?.length">
                    <div class="flex justify-between items-center mt-[60px] mb-[30px] pb-[8px] border-b-[1px] border-[#E9E9E9] px-[25px]">
                        <p class="text-[14px] font-medium text-[#7D7D7D]">
                            @lang('shop::app.checkout.cart.subtotal')
                        </p>

                        <p 
                            class="text-[30px] font-semibold"
                            v-text="cart.formatted_grand_total"
                        >
                        </p>
                    </div>
        
                    <div class="px-[25px]">
                        <a 
                            href="{{ route('shop.checkout.onepage.index') }}" 
                            class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-full font-medium py-[15px] px-[43px] rounded-[18px] text-center cursor-pointer max-sm:px-[20px]"
                        >
                            @lang('shop::app.checkout.cart.continue-to-checkout')
                        </div>

                        <div class="m-0 ml-[0px] block text-base py-[15px] text-center font-medium cursor-pointer">
                            <a href="{{ route('shop.checkout.cart.index') }}">
                                @lang('shop::app.checkout.cart.view-cart')
                            </a>
                        </div>
                    </div>
                </div>
            </x-slot:footer>
        </x-shop::drawer>
    </script>

    <script type="module">
        app.component("v-mini-cart", {
            template: '#v-mini-cart-template',

            data() {
                return  {
                    cart: null,
                }
            },

           mounted() {
                this.getCart();
           },

           methods: {
                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
                },

                updateItem(quantity, item) {
                    let qty = {};

                    qty[item.id] = quantity;

                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
                },

                removeItem(itemId) {
                    this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                            '_method': 'DELETE',
                            'cart_item_id': itemId,
                        })
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
                },
            }
        });
    </script>
@endpushOnce
