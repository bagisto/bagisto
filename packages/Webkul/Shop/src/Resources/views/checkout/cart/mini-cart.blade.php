{{-- Mini Cart Vue Component --}}
<v-mini-cart>
    <span class="icon-cart text-[24px] cursor-pointer"></span>
</v-mini-cart>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mini-cart-template">
        <x-shop::drawer>
            <!-- Drawer Toggler -->
            <x-slot:toggle>
                <span class="relative">
                    <span class="icon-cart text-[24px] cursor-pointer"></span>

                    <span
                        class="absolute  px-[7px] top-[-15px] left-[18px] py-[5px] bg-[#060C3B] rounded-[44px] text-white text-[10px] font-semibold leading-[9px]"
                        v-if="cart?.items_qty"
                    >
                        @{{ cart.items_qty }}
                    </span>
                </span>
            </x-slot:toggle>

            <!-- Drawer Header -->
            <x-slot:header>
                <div class="flex justify-between items-center">
                    <p class="text-[26px] font-medium">
                        @lang('shop::app.checkout.cart.mini-cart.shopping-cart')
                    </p>
                </div>

                <p class="text-[16px]">
                    @lang('shop::app.checkout.cart.mini-cart.offer-on-orders')
                </p>
            </x-slot:header>

            <!-- Drawer Content -->
            <x-slot:content>
                <!-- Cart Item Listing -->
                <div 
                    class="grid gap-[50px] mt-[35px]" 
                    v-if="cart?.items?.length"
                >
                    <div 
                        class="flex gap-x-[20px]" 
                        v-for="item in cart?.items"
                    >
                        <!-- Cart Item Image -->
                        <div class="">
                            <img
                                :src="item.base_image.small_image_url"
                                class="max-w-[110px] max-h-[110px] rounded-[12px]"
                            />
                        </div>

                        <!-- Cart Item Information -->
                        <div class="grid flex-1 gap-y-[10px] place-content-start justify-stretch">
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

                            <!-- Cart Item Options Container -->
                            <div
                                class="grid gap-x-[10px] gap-y-[6px] select-none"
                                v-if="item.options.length"
                            >
                                <!-- Details Toggler -->
                                <div class="">
                                    <p
                                        class="flex gap-x-[15px] items-center text-[16px] cursor-pointer"
                                        @click="item.option_show = ! item.option_show"
                                    >
                                        @lang('shop::app.checkout.cart.mini-cart.see-details')

                                        <span
                                            class="text-[24px]"
                                            :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                        ></span>
                                    </p>
                                </div>

                                <!-- Option Details -->
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

                            <div class="flex gap-[20px] items-center flex-wrap">

                                <!-- Cart Item Quantity Changer -->
                                <x-shop::quantity-changer
                                    name="quantity"
                                    ::value="item?.quantity"
                                    class="gap-x-[10px] max-w-[150px] max-h-[36px] py-[5px] px-[14px] rounded-[54px]"
                                    @change="updateItem($event, item)"
                                >
                                </x-shop::quantity-changer>

                                <!-- Cart Item Remove Button -->
                                <button
                                    type="button"
                                    class="text-[#0A49A7]"
                                    @click="removeItem(item.id)"
                                >
                                    @lang('shop::app.checkout.cart.mini-cart.remove')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart Section -->
                <div
                    class="pb-[30px]"
                    v-else
                >
                    <div class="grid gap-y-[20px] b-0 place-items-center">
                        <img src="{{ bagisto_asset('images/thank-you.png') }}">

                        <p class="text-[20px]">
                            @lang('shop::app.checkout.cart.mini-cart.empty-cart')
                        </p>
                    </div>
                </div>
            </x-slot:content>

            <!-- Drawer Footer -->
            <x-slot:footer>
                <div v-if="cart?.items?.length">
                    <div class="flex justify-between items-center mt-[60px] mb-[30px] px-[25px] pb-[8px] border-b-[1px] border-[#E9E9E9]">
                        <p class="text-[14px] font-medium text-[#6E6E6E]">
                            @lang('shop::app.checkout.cart.mini-cart.subtotal')
                        </p>

                        <p
                            class="text-[30px] font-semibold"
                            v-text="cart.formatted_grand_total"
                        >
                        </p>
                    </div>

                    <!-- Cart Action Container -->
                    <div class="px-[25px]">
                        <a
                            href="{{ route('shop.checkout.onepage.index') }}"
                            class="block w-full mx-auto m-0 ml-[0px] py-[15px] px-[43px] bg-navyBlue rounded-[18px] text-white text-base font-medium text-center cursor-pointer max-sm:px-[20px]"
                        >
                            @lang('shop::app.checkout.cart.mini-cart.continue-to-checkout')
                        </a>

                        <div class="block m-0 ml-[0px] py-[15px] text-base text-center font-medium cursor-pointer">
                            <a href="{{ route('shop.checkout.cart.index') }}">
                                @lang('shop::app.checkout.cart.mini-cart.view-cart')
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

                /**
                 * To Do: Implement this.
                 *
                 * Action.
                 */
                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });
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
                            if (response.data.message) {
                                this.cart = response.data.data;
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }
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

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {});
                },
            }
        });
    </script>
@endpushOnce
