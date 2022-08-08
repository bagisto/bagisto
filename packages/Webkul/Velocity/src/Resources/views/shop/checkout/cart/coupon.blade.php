@if ($cart)
    <script type="text/x-template" id="coupon-component-template">
        <div class="coupon-container">
            <div class="discount-control">
                <form class="custom-form" method="post" @submit.prevent="applyCoupon">
                    <div class="control-group" :class="[errorMessage ? 'has-error' : '']">
                        <input
                            type="text"
                            name="code"
                            class="control"
                            v-model="couponCode"
                            placeholder="{{ __('shop::app.checkout.onepage.enter-coupon-code') }}" />

                        <div class="control-error">@{{ errorMessage }}</div>
                    </div>

                    <button class="theme-btn light" :disabled="disableButton">{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                </form>
            </div>

            <div class="applied-coupon-details" v-if="appliedCoupon">
                <label>{{ __('shop::app.checkout.total.coupon-applied') }}</label>

                <label class="right" style="display: inline-flex; align-items: center;">
                    <b>@{{ appliedCoupon }}</b>

                    <i class="rango-close fs18" title="{{ __('shop::app.checkout.total.remove-coupon') }}" v-on:click="removeCoupon"></i>
                </label>
            </div>
        </div>
    </script>

    <script>
        Vue.component('coupon-component', {
            template: '#coupon-component-template',

            inject: ['$validator'],

            data: function() {
                return {
                    couponCode: '',
                    errorMessage: '',
                    appliedCoupon: "{{ $cart->coupon_code }}",
                    routeName: "{{ request()->route()->getName() }}",
                    disableButton: false,
                    removeIconEnabled: true
                }
            },

            watch: {
                couponCode: function (value) {
                    if (value != '') {
                        this.errorMessage = '';
                    }
                }
            },

            methods: {
                applyCoupon: function() {
                    if (! this.couponCode.length) {
                        this.errorMessage = '{{ __('shop::app.checkout.total.invalid-coupon') }}';

                        return;
                    }

                    this.errorMessage = null;

                    this.disableButton = true;

                    let code = this.couponCode;

                    axios
                        .post(
                            '{{ route('shop.checkout.cart.coupon.apply') }}', {code}
                        ).then(response => {
                            if (response.data.success) {
                                this.$emit('onApplyCoupon');

                                this.appliedCoupon = this.couponCode;
                                this.couponCode = '';

                                window.showAlert(
                                    'alert-success',
                                    response.data.label,
                                    response.data.message
                                 );

                                this.redirectIfCartPage();
                            } else {
                                this.errorMessage = response.data.message;
                            }

                            this.disableButton = false;
                        }).catch(error => {
                            this.errorMessage = error.response.data.message;

                            this.disableButton = false;
                        });
                },

                removeCoupon: function () {
                    let self = this;

                    if (self.removeIconEnabled) {
                        self.removeIconEnabled = false;

                        axios
                        .delete('{{ route('shop.checkout.coupon.remove.coupon') }}')
                        .then(function(response) {
                            self.$emit('onRemoveCoupon')

                            self.appliedCoupon = '';

                            self.disableButton = false;

                            self.removeIconEnabled = true;

                            window.showAlert(
                                'alert-success',
                                response.data.label,
                                response.data.message
                            );

                            self.redirectIfCartPage();
                        })
                        .catch(function(error) {
                            window.flashMessages = [{'type': 'alert-error', 'message': error.response.data.message}];

                            self.$root.addFlashMessages();

                            self.disableButton = false;

                            self.removeIconEnabled = true;
                        });
                    }
                },

                redirectIfCartPage: function() {
                    if (this.routeName != 'shop.checkout.cart.index') return;

                    setTimeout(function() {
                        window.location.reload();
                    }, 700);
                }
            }
        });
    </script>
@endif