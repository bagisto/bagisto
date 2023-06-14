<x-shop::layouts>
    <div class="flex-auto">
        <div class="container px-[60px] max-lg:px-[30px]">
            <!-- Breadcrumb -->
            <div class="flex justify-start mt-[30px] max-lg:hidden">
                <div class="flex gap-x-[14px] items-center">
                    <p class="flex items-center gap-x-[14px] text-[16px] font-medium">
                        @lang('shop::app.checkout.cart.home') 

                        <span class="icon-arrow-right text-[24px]"></span>
                    </p>

                    <p class="text-[#7D7D7D] text-[12px] flex items-center gap-x-[16px] font-medium  after:content[' '] after:bg-[position:-7px_-41px] after:bs-main-sprite after:w-[9px] after:h-[20px] after:last:hidden">
                        @lang('shop::app.checkout.cart.cart-page')
                    </p>
                </div>
            </div>

            <v-cart></v-cart>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-cart-template">
            <div class="grid grid-cols-[1fr_auto] gap-[30px] mt-[30px]" v-if="cart?.items?.length">
                <div class="grid gap-y-[25px]">
                    <div class="grid gap-x-[10px] grid-cols-[380px_auto_auto_auto] border-b-[1px] border-[#E9E9E9] pb-[18px]">
                        <div 
                            class="w-[200px] h-[21px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <div 
                            class="text-[14px] font-medium"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                        >
                            @lang('shop::app.checkout.cart.product-name')
                        </div>

                        <div 
                            class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <div 
                            class="text-[14px] font-medium"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                        >
                            @lang('shop::app.checkout.cart.price')
                        </div>

                        <div 
                            class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <div 
                            class="text-[14px] font-medium"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                        >
                            @lang('shop::app.checkout.cart.quantity')
                        </div>

                        <div 
                            class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <div 
                            class="text-[14px] font-medium"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                        >
                            @lang('shop::app.checkout.cart.total')
                        </div>
                    </div>

                    <div 
                        class="grid gap-x-[10px] grid-cols-[380px_auto_auto_auto] border-b-[1px] border-[#E9E9E9] pb-[18px]" 
                        v-for="item in cart?.items"
                    >
                        <div class="flex gap-x-[20px]">
                            <div class="">
                                <div 
                                    class="w-[110px] h-[110px] shimmer bg-[#E9E9E9] rounded-[12px]"
                                    v-show="isPageLoading"
                                >
                                </div>

                                <img 
                                    src="{{ bagisto_asset('images/wishlist-user.png')}}"
                                    class="max-w-[110px] max-h-[110px] rounded-[12px]" 
                                    @load="onPageLoaded"
                                    v-show="! isPageLoading"
                                >
                            </div>

                            <div class="grid gap-y-[10px]">
                                <div 
                                    class="w-[200px] h-[21px] shimmer bg-[#E9E9E9]"
                                    v-show="isPageLoading"
                                >
                                </div>

                                <p 
                                    class="text-[16px] font-medium" 
                                    @load="onPageLoaded"
                                    v-show="! isPageLoading"
                                    v-text="item.name"
                                >
                                </p>

                                <div 
                                    class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"
                                    v-show="isPageLoading"
                                >
                                </div>

                                <button 
                                    type="button"
                                    @load="onPageLoaded"
                                    v-show="! isPageLoading"
                                    @click="removeItem(item.id)"
                                >
                                    @lang('shop::app.checkout.cart.remove')
                                </button>
                            </div>
                        </div>

                        <div 
                            class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <p 
                            class="text-[18px]"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                            v-text="item.formatted_price"
                        >
                        </p>

                        <div 
                            class="w-[110px] h-[36px] rounded-[54px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <x-shop::quantity-changer
                            class="flex gap-x-[20px] border rounded-[54px] border-navyBlue py-[5px] px-[14px] items-center max-w-[116px] max-h-[36px]"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                            @change="setItemQuantity(item.id, $event)"
                            name="quantity"
                            ::value="item?.quantity"
                        >
                        </x-shop::quantity-changer>

                        <div 
                            class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <p 
                            class="text-[18px] font-semibold" 
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                            v-text="item.formatted_total"
                        >
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-[30px] justify-end">
                        <div 
                            class="rounded-[18px]  w-[217px] h-[56px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>
					    
                        <div 
                            class="bs-secondary-button rounded-[18px]"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                        >
                            <a href="{{ route('shop.home.index') }}">
                                @lang('shop::app.checkout.cart.continue-shopping')
                            </a>
                        </div>

                        <div 
                            class="rounded-[18px]  w-[161px] h-[56px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <div 
                            class="bs-secondary-button rounded-[18px]"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                        >
                            <button 
                                type="button" 
                                @click="update()"
                            >
                                @lang('shop::app.checkout.cart.update-cart')
                            </button>
                        </div>
                    </div>
                </div>

                <div class="w-[418px] max-w-full">
                    <p 
                        class="w-[40%] h-[39px] bg-[#E9E9E9] shimmer"
                        v-show="isPageLoading"
                    >
                    </p>

                    <p 
                        class="text-[26px] font-medium"
                        @load="onPageLoaded"
                        v-show="! isPageLoading"
                    >
                        @lang('shop::app.checkout.cart.cart-summary')
                    </p>

                    <div class="grid gap-[15px] mt-[25px]">
                        <div class="flex text-right justify-between">
                            <p 
                                class="w-[30%] h-[24px] shimmer bg-[#E9E9E9]"
                                v-show="isPageLoading"
                            >
                            </p>

                            <p 
                                class="text-[16px]"
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                            >
                                @lang('shop::app.checkout.cart.subtotal')
                            </p>

						    <p 
                                class="w-[30%] h-[24px] shimmer bg-[#E9E9E9]"
                                v-show="isPageLoading"
                            >
                            </p>

                            <p 
                                class="text-[16px] font-medium"
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                                v-text="cart.formatted_sub_total"
                            >
                            </p>
                        </div>

                        <div class="flex text-right justify-between">
                            <p 
                                class="w-[40%] h-[24px] shimmer bg-[#E9E9E9]"
                                v-show="isPageLoading"
                            >
                            </p>
                            
                            <p 
                                class="text-[16px]"
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                            >
                                @lang('shop::app.checkout.cart.tax') 0 %
                            </p>

						    <p 
                                class="w-[36%] h-[24px] shimmer bg-[#E9E9E9]"
                                v-show="isPageLoading"
                            >
                            </p>

                            <p 
                                class="text-[16px] font-medium"
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                                v-text="cart.formatted_tax_total"
                            >
                            </p>
                        </div>

                        <div class="flex text-right justify-between">
                            <p 
                                class="w-[30%] h-[24px] shimmer bg-[#E9E9E9]"
                                v-show="isPageLoading"
                            >
                            </p>

                            <p 
                                class="text-[16px]"
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                            >
                                @lang('shop::app.checkout.cart.coupon.discount')
                            </p>

						    <p 
                                class="w-[31%] h-[24px] shimmer bg-[#E9E9E9]"
                                v-show="isPageLoading"
                            >
                            </p>

                            <p 
                                class="text-[16px] font-medium cursor-pointer" 
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                                v-if="cart.discount_amount == 0"
                            >
                                <x-shop::modal>
                                    <x-slot:toggle>
                                        <span class="text-[#4D7EA8]">
                                            @lang('shop::app.checkout.cart.coupon.apply')
                                        </span>
                                    </x-slot:toggle>

                                    <x-slot:header>
                                        @lang('shop::app.checkout.cart.coupon.apply')
                                    </x-slot:header>

                                    <x-slot:content>
                                        <x-form action="{{ route('shop.checkout.cart.coupon.apply') }}">
                                            <x-form.control-group>
                                                <x-form.control-group.label>
                                                    @lang('shop::app.checkout.cart.coupon.code')
                                                </x-form.control-group.label>

                                                <x-form.control-group.control
                                                    type="text"
                                                    name="code"
                                                    placeholder="Enter your code"
                                                />

                                                <x-form.control-group.error
                                                    control-name="code"
                                                >
                                                </x-form.control-group.error>
                                            </x-form.control-group>

                                            <button
                                                type="submit"
                                                class="m-0 block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                            >
                                                @lang('shop::app.customers.account.save')
                                            </button>
                                        </x-form>
                                    </x-slot:content>
                                </x-shop::modal>
                            </p>
                            
                            <p 
                                class="text-[16px] font-medium cursor-pointer" 
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                                v-else
                            >
                                <x-form
                                    method="DELETE"
                                    action="{{ route('shop.checkout.cart.coupon.remove') }}"
                                >
                                    <button 
                                        type="submit"
                                        v-text="cart.formatted_discount_amount"
                                    >
                                    </button>
                                </x-form>                            
                            </p>
                        </div>

                        <div class="flex text-right justify-between">
                            <p 
                                class="w-[33%] h-[24px] shimmer bg-[#E9E9E9]"
                                v-show="isPageLoading"
                            >
                            </p>

                            <p 
                                class="text-[16px]"
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                            >
                                @lang('shop::app.checkout.cart.grand-total')
                            </p>

						    <p 
                                class="w-[38%] h-[24px] shimmer bg-[#E9E9E9]"
                                v-show="isPageLoading"
                            >
                            </p>

                            <p 
                                class="text-[26px] font-medium" 
                                @load="onPageLoaded"
                                v-show="! isPageLoading"
                                v-text="cart.formatted_grand_total"
                            >
                            </p>
                        </div>

                        <div 
                            class="block place-self-end mt-[15px] rounded-[18px]  w-[60%] h-[46px] shimmer bg-[#E9E9E9]"
                            v-show="isPageLoading"
                        >
                        </div>

                        <div 
                            class="block place-self-end bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer mt-[15px]"
                            @load="onPageLoaded"
                            v-show="! isPageLoading"
                        >
                            @lang('shop::app.checkout.cart.proceed-to-checkout')
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-[1fr_auto] gap-[30px] mt-[30px]" v-else>
                <h1>Don't Have product in your cart</h1>
            </div>
        </script>

        <script type="module">
            app.component("v-cart", {
                template: '#v-cart-template',

                data() {
                    return  {
                        isPageLoading: true,

                        cart: [],

                        applied: {
                            quantity: {},
                        },
                    }
                },

                mounted() {
                    this.get();
                },

                methods: {
                    onPageLoaded() {
                        this.isPageLoading = false;
                    },

                    get() {
                        this.$axios.get('{{ route('shop.checkout.cart.index') }}')
                            .then(response => {
                                this.cart = response.data.data;
                            })
                            .catch(error => {});     
                    },

                    update() {
                        this.$axios.put('{{ route('shop.checkout.cart.destroy') }}', { qty: this.applied.quantity })
                            .then(response => {
                                this.cart = response.data.data;
                            })
                            .catch(error => {});
                    },

                    setItemQuantity(itemId, quantity) {
                        this.applied.quantity[itemId] = quantity;
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
</x-shop::layouts>
