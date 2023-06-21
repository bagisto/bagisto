<x-shop::layouts
    :has-feature="false"
    :has-footer="false"
>
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

            <v-cart>
                <x-shop::shimmer.checkout.cart :count="3"></x-shop::shimmer.checkout.cart>
            </v-cart>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-cart-template">
            <template v-if="isLoading">
                <x-shop::shimmer.checkout.cart :count="3"></x-shop::shimmer.checkout.cart>
            </template>

            <template v-else>
                <div class="grid grid-cols-[1fr_auto] gap-[30px] mt-[30px]" v-if="cart?.items?.length">
                    <div class="grid gap-y-[25px]">
                        <div class="grid gap-x-[10px] grid-cols-[380px_auto_auto_auto] border-b-[1px] border-[#E9E9E9] pb-[18px]">
                            <div class="text-[14px] font-medium">
                                @lang('shop::app.checkout.cart.product-name')
                            </div>
    
                            <div class="text-[14px] font-medium">
                                @lang('shop::app.checkout.cart.price')
                            </div>
    
                            <div class="text-[14px] font-medium">
                                @lang('shop::app.checkout.cart.quantity')
                            </div>
    
                            <div class="text-[14px] font-medium">
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
                                        class="overflow-hidden rounded-[12px] w-[110px] h-[110px] bg-[#E9E9E9] shimmer"
                                        v-show="isImageLoading"
                                    >
                                        <img 
                                            class="rounded-sm bg-[#F5F5F5]" 
                                            src=""
                                        >
                                    </div>

                                    <img 
                                        class="w-[110px] h-[110px] rounded-[12px]" 
                                        :src="item.base_image.small_image_url"
                                        @load="onImageLoad"
                                        v-show="! isImageLoading"
                                        alt="" 
                                        title=""
                                    >
                                </div>
    
                                <div class="grid place-content-start gap-y-[10px]">
                                    <p 
                                        class="text-[16px] font-medium" 
                                        v-text="item.name"
                                    >
                                    </p>
    
                                    <span
                                        class="text-[#0A49A7] cursor-pointer" 
                                        @click="removeItem(item.id)"
                                    >
                                        @lang('shop::app.checkout.cart.remove')
                                </span>
                                </div>
                            </div>
    
                            <p 
                                class="text-[18px]"
                                v-text="item.formatted_price"
                            >
                            </p>
    
                            <x-shop::quantity-changer
                                name="quantity"
                                ::value="item?.quantity"
                                class="flex gap-x-[20px] border rounded-[54px] border-navyBlue py-[5px] px-[14px] items-center max-w-[116px] max-h-[36px]"
                                @change="setItemQuantity(item.id, $event)"
                            >
                            </x-shop::quantity-changer>
    
                            <p 
                                class="text-[18px] font-semibold" 
                                v-text="item.formatted_total"
                            >
                            </p>
                        </div>
    
                        <div class="flex flex-wrap gap-[30px] justify-end">
                            <div class="bs-secondary-button rounded-[18px]">
                                <a href="{{ route('shop.home.index') }}">
                                    @lang('shop::app.checkout.cart.continue-shopping')
                                </a>
                            </div>
    
                            <div class="bs-secondary-button rounded-[18px]">
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
                        <p class="text-[26px] font-medium">
                            @lang('shop::app.checkout.cart.cart-summary')
                        </p>
    
                        <div class="grid gap-[15px] mt-[25px]">
                            <div class="flex text-right justify-between">
                                <p class="text-[16px]">
                                    @lang('shop::app.checkout.cart.subtotal')
                                </p>
    
                                <p 
                                    class="text-[16px] font-medium"
                                    v-text="cart.formatted_sub_total"
                                >
                                </p>
                            </div>
    
                            <div class="flex text-right justify-between">
                                <p class="text-[16px]">
                                    @lang('shop::app.checkout.cart.tax') 0 %
                                </p>
    
                                <p 
                                    class="text-[16px] font-medium"
                                    v-text="cart.formatted_tax_total"
                                >
                                </p>
                            </div>
    
                            <div class="flex text-right justify-between">
                                <p class="text-[16px]">
                                    @lang('shop::app.checkout.cart.coupon.discount')
                                </p>
    
                                <p 
                                    class="text-[16px] font-medium cursor-pointer" 
                                    v-if="cart.discount_amount == 0"
                                >
                                    <x-shop::modal>
                                        <x-slot:toggle>
                                            <span class="text-[#0A49A7]">
                                                @lang('shop::app.checkout.cart.coupon.apply')
                                            </span>
                                        </x-slot:toggle>
    
                                        <x-slot:header>
                                            @lang('shop::app.checkout.cart.coupon.apply')
                                        </x-slot:header>
    
                                        <x-slot:content>
                                            <x-form
                                                action="{{ route('shop.checkout.cart.coupon.apply') }}"
                                            >
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
                                
                                <p class="text-[16px] font-medium cursor-pointer" v-else>
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
                                <p class="text-[16px]">
                                    @lang('shop::app.checkout.cart.grand-total')
                                </p>
    
                                <p 
                                    class="text-[26px] font-medium" 
                                    v-text="cart.formatted_grand_total"
                                >
                                </p>
                            </div>
    
                            <a 
                                href="{{ route('shop.checkout.onepage.index') }}" 
                                class="block place-self-end bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer mt-[15px]"
                            >
                                    @lang('shop::app.checkout.cart.proceed-to-checkout')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-[1fr_auto] gap-[30px] mt-[30px]" v-else>
                    <h1>Don't Have product in your cart</h1>
                </div>
            </template>
        </script>

        <script type="module">
            app.component("v-cart", {
                template: '#v-cart-template',

                data() {
                    return  {
                        cart: [],

                        applied: {
                            quantity: {},
                        },

                        isImageLoading: true,

                        isLoading: true,
                    }
                },

                mounted() {
                    this.get();
                },

                methods: {
                    onImageLoad() {
                        this.isImageLoading = false;
                    },

                    get() {
                        this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                            .then(response => {
                                this.isLoading = false;

                                this.cart = response.data.data;
                            })
                            .catch(error => {});     
                    },

                    update() {
                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty: this.applied.quantity })
                            .then(response => {
                                this.cart = response.data.data;
                            })
                            .catch(error => {});
                    },

                    setItemQuantity(itemId, quantity) {
                        this.applied.quantity[itemId] = quantity;
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
</x-shop::layouts>
