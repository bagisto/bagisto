@if ($cart)
    <script type="text/x-template" id="coupon-component-template">
        <div class="coupon-container">
            <div class="discount-control">
                <form class="coupon-form" method="post" @submit.prevent="applyCoupon">
                    <div class="control-group" :class="[errorMessage ? 'has-error' : '']">
                        <input type="text" class="control" v-model="couponCode" name="code" placeholder="{{ __('shop::app.checkout.onepage.enter-coupon-code') }}">

                        <div class="control-error">@{{ errorMessage }}</div>
                    </div>

                    <button class="btn btn-lg btn-black" :disabled="disableButton">{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                </form>
            </div>

            <div class="applied-coupon-details" v-if="appliedCoupon">
                <label>{{ __('shop::app.checkout.total.coupon-applied') }}</label>

                <label class="right" style="display: inline-flex; align-items: center;">
                    <b>@{{ appliedCoupon }}</b>

                    <span class="icon cross-icon" title="{{ __('shop::app.checkout.total.remove-coupon') }}" v-on:click="removeCoupon"></span>
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

                    appliedCoupon: "{{ $cart->coupon_code }}",

                    errorMessage: '',

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
                    let self = this;

                    if (! this.couponCode.length) {
                        this.errorMessage = '{{ __('shop::app.checkout.total.invalid-coupon') }}';

                        return;
                    }

                    self.errorMessage = null;

                    self.disableButton = true;

                    axios.post('{{ route('shop.checkout.cart.coupon.apply') }}', {code: self.couponCode})
                        .then(function(response) {
                            if (response.data.success) {
                                self.$emit('onApplyCoupon');

                                self.appliedCoupon = self.couponCode;

                                self.couponCode = '';

                                window.flashMessages = [{'type': 'alert-success', 'message': response.data.message}];

                                self.$root.addFlashMessages();

                                self.redirectIfCartPage();
                            } else {
                                self.errorMessage = response.data.message;
                            }

                            self.disableButton = false;
                        })
                        .catch(function(error) {
                            self.errorMessage = error.response.data.message;

                            self.disableButton = false;
                        });
                },

                removeCoupon: function () {
                    let self = this;
                    
                    if (self.removeIconEnabled) { 
                        self.removeIconEnabled = false;

                        axios.delete('{{ route('shop.checkout.coupon.remove.coupon') }}')
                        .then(function(response) {
                            self.$emit('onRemoveCoupon')

                            self.appliedCoupon = '';

                            self.removeIconEnabled = true;

                            window.flashMessages = [{'type': 'alert-success', 'message': response.data.message}];

                            self.$root.addFlashMessages();

                            self.redirectIfCartPage();
                        })
                        .catch(function(error) {
                            window.flashMessages = [{'type': 'alert-error', 'message': error.response.data.message}];

                            self.$root.addFlashMessages();

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