<v-coupon :is-coupon-applied="cart.coupon_code"></v-coupon>

@pushOnce('scripts')
    <script type="text/x-template" id="v-coupon-template">
        <div class="flex text-right justify-between">
            <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">@lang('shop::app.checkout.cart.coupon.discount')</p>
            
            <p class="text-[16px] max-sm:text-[14px] max-sm:font-medium font-medium cursor-pointer">
                <x-shop::modal>
                    <x-slot:toggle>
                        <span 
                            class="text-blue-600"
                            v-if="! isCouponApplied"
                        >
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

                <div 
                    class="text-[12px] font-small flex justify-between items-center"
                    v-if="isCouponApplied"
                >
                    <p class="text-[12px] mr-2">@lang('Coupon applied')</p>
                    
                    <p 
                        class="text-[16px] font-medium cursor-pointer text-navyBlue"
                        title="@lang('Applied coupon')"
                    >
                        "@{{ isCouponApplied }}"
                    </p>

                    <span 
                        class="icon-cancel text-[30px] cursor-pointer"
                        title="@lang('Remove coupon')"
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
            
            props: ['isCouponApplied'],

            methods: {
                applyCoupon(params) {
                    this.$axios.post("{{ route('shop.checkout.cart.coupon.apply') }}", params)
                        .then((response) => {
                            alert(response.data.data.message)
                            this.$parent.$parent.getOrderSummary();
                        })
                        .catch((error) => {console.log(error);})

                },

                destroyCoupon() {
                    this.$axios.delete("{{ route('shop.checkout.cart.coupon.remove') }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then((response) => {

                            this.$emit('updateOrderSummary')

                            this.$parent.$parent.getOrderSummary();
                        })
                        .catch(error => console.log(error));
                }
            }
        })

    </script>
@endPushOnce