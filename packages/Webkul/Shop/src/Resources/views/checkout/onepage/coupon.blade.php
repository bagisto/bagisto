<v-coupon 
    :is-coupon-applied="cart.coupon_code"
    :sub-total="cart.base_grand_total"
>
</v-coupon>

@pushOnce('scripts')
    <script type="text/x-template" id="v-coupon-template">
        <div class="flex text-right justify-between">
            <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">
                @lang('shop::app.checkout.onepage.coupon.discount')
            </p>
            
            <p class="text-[16px] max-sm:text-[14px] max-sm:font-medium font-medium">
                <span v-if="! isCouponApplied">
                    <!-- Apply Coupon modal -->
                    <x-shop::modal ref="couponModel">
                        <x-slot:toggle>
                            <span class="text-[#0A49A7] cursor-pointer">
                                @lang('shop::app.checkout.onepage.coupon.apply')
                            </span>
                        </x-slot:toggle>
    
                        <x-slot:header>
                            <h2 class="text-[25px] font-medium max-sm:text-[22px]">
                                @lang('shop::app.checkout.onepage.coupon.apply')
                            </h2>
                        </x-slot:header>
    
                        <x-slot:content>
                           
                            <x-shop::form
                                v-slot="{ meta, errors, handleSubmit }"
                                as="div"
                            >
                                <form @submit="handleSubmit($event, applyCoupon)">
                                    <x-shop::form.control-group>
                                        <div class="p-[30px] bg-white">
                                            <x-shop::form.control-group.control
                                                type="text"
                                                name="code"
                                                class="py-[20px] px-[25px]"
                                                rules="required"
                                                :placeholder="trans('shop::app.checkout.onepage.coupon.enter-your-code')"
                                                v-model="code"
                                            >
                                            </x-shop::form.control-group.control>
    
                                            <x-shop::form.control-group.error
                                                class="flex"
                                                control-name="code"
                                            >
                                            </x-shop::form.control-group.error>
                                        </div>
                                    </x-shop::form.control-group>
    
                                    <div class="p-[30px] bg-white mt-[20px]">
                                        <div class="flex justify-between items-center gap-[15px] flex-wrap">
                                            <p class="text-[14px] font-medium text-[#6E6E6E]">
                                                @lang('shop::app.checkout.onepage.coupon.sub-total')
                                            </p>

                                            <div class="flex gap-[30px] items-center flex-auto flex-wrap">
                                                <p 
                                                    class="text-[30px] font-semibold max-sm:text-[22px]"
                                                    v-text="subTotal"
                                                >
                                                </p>
    
                                                <button
                                                    class="block flex-auto w-max py-[11px] px-[43px] bg-navyBlue rounded-[18px] text-white text-base font-medium text-center cursor-pointer max-sm:text-[14px] max-sm:px-[25px]"
                                                    type="submit"
                                                >
                                                   @lang('shop::app.checkout.onepage.coupon.button-title')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </x-shop::form>
                        </x-slot:content>
                    </x-shop::modal>
                </span>

                <div 
                    class="flex justify-between items-center text-[12px] font-small"
                    v-else
                >
                    <p class="mr-2 text-[12px]">
                        @lang('shop::app.checkout.onepage.coupon.applied')
                    </p>
                    
                    <p 
                        class="text-[16px] font-medium cursor-pointer text-navyBlue"
                        title="@lang('shop::app.checkout.onepage.coupon.applied-coupon')"
                    >
                        "@{{ isCouponApplied }}"
                    </p>

                    <span 
                        class="icon-cancel text-[30px] cursor-pointer"
                        title="@lang('shop::app.checkout.onepage.coupon.remove')"
                        @click="destroyCoupon"
                    >
                    </span>
                </div>
            </p>
        </div>
    </script>

    <script type="module">
        app.component('v-coupon', {
            template: '#v-coupon-template',
            
            props: ['isCouponApplied', 'subTotal'],

            data() {
                return {
                    coupons: [],

                    code: '',
                }
            },

            methods: {
                applyCoupon(params, { resetForm }) {
                    this.$axios.post("{{ route('shop.api.checkout.cart.coupon.apply') }}", params)
                        .then((response) => {
                            this.$parent.$parent.getOrderSummary();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$refs.couponModel.toggle();

                            resetForm();
                        })
                        .catch((error) => {
                            if ([400, 422].includes(error.response.request.status)) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });

                                this.$refs.couponModel.toggle();

                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            this.$refs.couponModel.toggle();
                        });
                },

                destroyCoupon() {
                    this.$axios.delete("{{ route('shop.api.checkout.cart.coupon.remove') }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then((response) => {
                            this.$parent.$parent.getOrderSummary();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => console.log(error));
                },
            }
        })
    </script>
@endPushOnce