<x-shop::layouts
    :has-feature="false"
    :has-footer="false"
>
    <div class="flex-auto">
        <div class="container px-[60px] max-lg:px-[30px]">

            <x-shop::breadcrumbs name="cart"></x-shop::breadcrumbs>

            <v-cart>
                <x-shop::shimmer.checkout.cart :count="3"></x-shop::shimmer.checkout.cart>
            </v-cart>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-cart-template">
            <div>
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

                                        <div
                                            class="grid gap-x-[10px] gap-y-[6px] select-none"
                                            v-if="item.options.length"
                                        >
                                            <div class="grid gap-[8px]">
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
                                        <x-shop::modal ref="coupon_modal">
                                            <x-slot:toggle>
                                                <span class="text-[#0A49A7]">
                                                    @lang('shop::app.checkout.cart.coupon.apply')
                                                </span>
                                            </x-slot:toggle>
        
                                            <x-slot:header>
                                                @lang('shop::app.checkout.cart.coupon.apply')
                                            </x-slot:header>
        
                                            <x-slot:content>

                                                <x-shop::form
                                                    v-slot="{ meta, errors, handleSubmit }"
                                                    as="div"
                                                >
                                                    <form @submit="handleSubmit($event, applyCoupon)">
                                                        <x-shop::form.control-group>
                                                            <x-shop::form.control-group.label>
                                                                @lang('shop::app.checkout.cart.coupon.code')
                                                            </x-shop::form.control-group.label>

                                                            <x-shop::form.control-group.control
                                                                type="text"
                                                                name="code"
                                                                placeholder="Enter your code"
                                                                rules="required"
                                                            >
                                                            </x-shop::form.control-group.control>

                                                            <x-shop::form.control-group.error
                                                                control-name="code"
                                                            >
                                                            </x-shop::form.control-group.error>
                                                        </x-shop::form.control-group>

                                                        <button
                                                            type="submit"
                                                            class="m-0 block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                                                        >
                                                            @lang('shop::app.customers.account.save')
                                                        </button>
                                                    </form>
                                                </x-shop::form>
                                            </x-slot:content>
                                        </x-shop::modal>
                                    </p>
                                    
                                    <p class="text-[16px] font-medium cursor-pointer" v-else>
                                        <button 
                                            type="submit"
                                            @click="removeCoupon"
                                            v-text="cart.formatted_discount_amount"
                                        >
                                        </button>
                                    </p>
                                </div>
        
                                <div class="flex text-right justify-between">
                                    <p class="text-[18px] font-semibold">
                                        @lang('shop::app.checkout.cart.grand-total')
                                    </p>
        
                                    <p 
                                        class="text-[18px] font-semibold" 
                                        v-text="cart.formatted_grand_total"
                                    >
                                    </p>
                                </div>
        
                                <a 
                                    href="{{ route('shop.checkout.onepage.index') }}" 
                                    class="block place-self-end bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer mt-[15px]"
                                >
                                    @lang('shop::app.checkout.cart.proceed-to-checkout')
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-[1fr_auto] gap-[30px] mt-[30px]" v-else>
                        <h1>Don't Have product in your cart</h1>
                    </div>
                </template>
            </div>
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

                    applyCoupon(params) {
                        this.$axios.post('{{ route('shop.api.checkout.cart.coupon.apply') }}', params)
                            .then(response => {
                                this.$refs.coupon_modal.toggle;

                                alert(response.data.message);

                                this.cart = response.data.data;
                            })
                            .catch(error => {
                                console.log('error');
                            });
                    },

                    removeCoupon() {
                        this.$axios.post('{{ route('shop.api.checkout.cart.coupon.remove') }}', {
                                '_method': 'DELETE',
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
