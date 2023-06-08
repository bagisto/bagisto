<v-mini-cart></v-mini-cart>

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
                                src="{{ bagisto_asset('images/wishlist-user.png')}}" 
                                class="max-w-[110px] max-h-[110px] rounded-[12px]"
                                alt="" 
                                title=""
                            >
                        </div>
    
                        <div class="grid gap-y-[10px]" >
                            <p class="text-[16px] font-medium" v-text="item.name"></p>
    
                            <div class="flex gap-x-[10px] gap-y-[6px] flex-wrap">
                                <p class="text-[14px]">
                                    @lang('shop::app.checkout.cart.item.quantity')

                                    @{{ item.quantity }}
                                </p>
                            </div>
    
                            <div class="flex gap-[20px] items-center flex-wrap">
                                <v-quantity-changer 
                                    :default-quantity="item.quantity" 
                                    @change="updateItem(item.id, $event)"
                                >
                                </v-quantity-changer>
    
                                <button 
                                    type="button" 
                                    @click="removeItem(item.id)"
                                >
                                    @lang('shop::app.checkout.cart.remove')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-[30px]" v-else>
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
                
                        <div class="m-auto block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer">
                            @lang('shop::app.checkout.cart.return-to-shop')
                        </div>
                    </div>
                </div>
            </x-slot:content>

            <x-slot:footer>
                <div v-if="cart?.items?.length">
                    <div class="flex justify-between items-center mt-[60px] mb-[30px] pb-[8px] border-b-[1px] border-[#E9E9E9] px-[25px]">
                        <p class="text-[14px] font-medium text-[#7D7D7D]">
                            @lang('shop::app.checkout.cart.subtotal')
                        </p>
        
                        <p class="text-[30px] font-semibold">
                            {{-- Need to add formatted grand total. --}}
                            @{{ cart?.grand_total }}
                        </p>
                    </div>
        
                    <div class="px-[25px]">
                        <div class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-full font-medium py-[15px] px-[43px] rounded-[18px] text-center cursor-pointer max-sm:px-[20px]">
                            @lang('shop::app.checkout.cart.continue-to-checkout')
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
                    this.$axios.get('{{ route('shop.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
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
                    this.$axios.post('{{ route('shop.checkout.cart.destroy') }}', {
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

@pushOnce('scripts')
    <script type="text/x-template" id="v-quantity-changer-template">
        <div class="flex gap-x-[20px] border rounded-[54px] border-navyBlue py-[5px] px-[14px] items-center max-w-[108px] max-h-[36px]">
            <span 
                class="bg-[position:-5px_-69px] bs-main-sprite w-[14px] h-[14px] cursor-pointer"
                @click="increase"
            >
            </span>
            
            <p v-text="quantity"></p>

            <span 
                class="bg-[position:-172px_-44px] bs-main-sprite w-[14px] h-[14px] cursor-pointer" 
                @click="decrease"
            >
            </span>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:[
                'defaultQuantity',
            ],

            data() {
                return  {
                    quantity: this.defaultQuantity ?? 0,
                }
            },

            methods: {
                increase() {
                    this.quantity += 1;

                    this.$emit('change', this.quantity);
                },

                decrease() {
                    if (this.quantity > 1) this.quantity -= 1;

                    this.$emit('change', this.quantity);
                },
            }
        });
    </script>
@endpushOnce
