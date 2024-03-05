<!-- Coupon Vue Component -->
<v-coupon 
    :is-coupon-applied="cart.coupon_code"
    :sub-total="cart.base_grand_total"
>
</v-coupon>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-coupon-template"
    >
        <div class="flex justify-between text-right">
            <p class="text-base max-sm:text-sm max-sm:font-normal">
                @{{ isCouponApplied ? "@lang('shop::app.checkout.cart.coupon.applied')" : "@lang('shop::app.checkout.cart.coupon.discount')" }}
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.coupon.before') !!}

            <p class="text-base font-medium max-sm:text-sm">
                <!-- Apply Coupon Form -->
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <!-- Apply coupon form -->
                    <form @submit="handleSubmit($event, applyCoupon)">
                        {!! view_render_event('bagisto.shop.checkout.cart.coupon.coupon_form_controls.before') !!}

                        <!-- Apply coupon modal -->
                        <x-shop::modal ref="couponModel">
                            <!-- Modal Toggler -->
                            <x-slot:toggle>
                                <span 
                                    class="text-[#0A49A7] cursor-pointer"
                                    role="button"
                                    tabindex="0"
                                    v-if="! isCouponApplied"
                                >
                                    @lang('shop::app.checkout.cart.coupon.apply')
                                </span>
                            </x-slot>

                            <!-- Modal Header -->
                            <x-slot:header>
                                <h2 class="text-2xl font-medium max-sm:text-xl">
                                    @lang('shop::app.checkout.cart.coupon.apply')
                                </h2>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <x-shop::form.control-group class="!mb-0">
                                    <x-shop::form.control-group.control
                                        type="text"
                                        class="py-5 px-6"
                                        name="code"
                                        v-model="code"
                                        rules="required"
                                        :placeholder="trans('shop::app.checkout.cart.coupon.enter-your-code')"
                                    />

                                    <x-shop::form.control-group.error
                                        class="flex"
                                        control-name="code"
                                    />
                                </x-shop::form.control-group>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Coupon Form Action Container -->
                                <div class="flex justify-between items-center gap-4 flex-wrap">
                                    <p class="text-sm font-medium text-[#6E6E6E]">
                                        @lang('shop::app.checkout.cart.coupon.subtotal')
                                    </p>

                                    <div class="flex gap-8 items-center flex-auto flex-wrap">
                                        <p 
                                            class="text-3xl font-semibold max-sm:text-xl"
                                            v-text="subTotal"
                                        >
                                        </p>

                                        <button
                                            class="block flex-auto w-max py-3 px-11 bg-navyBlue rounded-2xl text-white text-base font-medium text-center cursor-pointer max-sm:text-sm max-sm:px-6"
                                            type="submit"
                                        >
                                            @lang('shop::app.checkout.cart.coupon.button-title')
                                        </button>
                                    </div>
                                </div>
                            </x-slot>
                        </x-shop::modal>

                        {!! view_render_event('bagisto.shop.checkout.cart.coupon.coupon_form_controls.after') !!}
                    </form>
                </x-shop::form>

                <!-- Applied Coupon Information Container -->
                <div 
                    class="flex justify-between items-center text-xs font-small "
                    v-if="isCouponApplied"
                >
                    <p 
                        class="text-base font-medium cursor-pointer text-navyBlue"
                        title="@lang('shop::app.checkout.cart.coupon.applied')"
                    >
                        "@{{ isCouponApplied }}"
                    </p>

                    <span 
                        class="icon-cancel text-3xl cursor-pointer"
                        title="@lang('shop::app.checkout.cart.coupon.remove')"
                        @click="destroyCoupon"
                    >
                    </span>
                </div>
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.coupon.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-coupon', {
            template: '#v-coupon-template',
            
            props: ['isCouponApplied', 'subTotal'],

            data() {
                return {
                    isCartPage: Boolean("{{ request()->route()->getName() === 'shop.checkout.cart.index' }}"),

                    coupons: [],

                    code: '',
                }
            },

            methods: {
                applyCoupon(params, { resetForm }) {
                    this.$axios.post("{{ route('shop.api.checkout.cart.coupon.apply') }}", params)
                        .then((response) => {
                            if (this.isCartPage) {
                                this.$parent.$parent.$refs.vCart.get();
                            } else {
                                this.$parent.$parent.getOrderSummary();
                            }
                  
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$refs.couponModel.toggle();

                            resetForm();
                        })
                        .catch((error) => {
                            if ([400, 422].includes(error.response.request.status)) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });

                                this.$refs.couponModel.toggle();

                                resetForm();

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
                            if (this.isCartPage) {
                                this.$parent.$parent.$refs.vCart.get();
                            } else {
                                this.$parent.$parent.getOrderSummary();
                            }

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => console.log(error));
                },
            }
        })
    </script>
@endPushOnce