@if ($cart)
    <script type="text/x-template" id="coupon-component-template">
        <div class="coupon-container">
            <div class="discount-control">
                <form class="coupon-form" method="post" @submit.prevent="applyCoupon">
                    <div class="control-group" :class="[error_message ? 'has-error' : '']">
                        <input type="text" class="control" v-model="coupon_code" name="code" placeholder="{{ __('shop::app.checkout.onepage.enter-coupon-code') }}">

                        <div class="control-error">@{{ error_message }}</div>
                    </div>

                    <button class="btn btn-lg btn-black" :disabled="disable_button">{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                </form>
            </div>

            <div class="applied-coupon-details" v-if="applied_coupon">
                <label>{{ __('shop::app.checkout.total.coupon-applied') }}</label>

                <label class="right" style="display: inline-flex; align-items: center;">
                    <b>@{{ applied_coupon }}</b>

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
                    coupon_code: '',

                    applied_coupon: "{{ $cart->coupon_code }}",

                    error_message: '',

                    route_name: "{{ request()->route()->getName() }}",

                    disable_button: false
                }
            },

            watch: {
                coupon_code: function (value) {
                    if (value != '') {
                        this.error_message = '';
                    }
                }
            },

            methods: {
                applyCoupon: function() {
                    let self = this;

                    if (! this.coupon_code.length) {
                        this.error_message = '{{ __('shop::app.checkout.total.invalid-coupon') }}';

                        return;
                    }

                    self.error_message = null;

                    self.disable_button = true;

                    axios.post('{{ route('shop.checkout.cart.coupon.apply') }}', {code: self.coupon_code})
                        .then(function(response) {
                            if (response.data.success) {
                                self.$emit('onApplyCoupon');

                                self.applied_coupon = self.coupon_code;

                                self.coupon_code = '';

                                window.flashMessages = [{'type': 'alert-success', 'message': response.data.message}];

                                self.$root.addFlashMessages();

                                self.redirectIfCartPage();
                            } else {
                                self.error_message = response.data.message;
                            }

                            self.disable_button = false;
                        })
                        .catch(function(error) {
                            self.error_message = error.response.data.message;

                            self.disable_button = false;
                        });
                },

                removeCoupon: function () {
                    let self = this;

                    axios.delete('{{ route('shop.checkout.coupon.remove.coupon') }}')
                        .then(function(response) {
                            self.$emit('onRemoveCoupon')

                            self.applied_coupon = '';

                            window.flashMessages = [{'type': 'alert-success', 'message': response.data.message}];

                            self.$root.addFlashMessages();

                            self.redirectIfCartPage();
                        })
                        .catch(function(error) {
                            window.flashMessages = [{'type': 'alert-error', 'message': error.response.data.message}];

                            self.$root.addFlashMessages();
                        });
                },

                redirectIfCartPage: function() {
                    if (this.route_name != 'shop.checkout.cart.index') return;

                    setTimeout(function() {
                        window.location.reload();
                    }, 700);
                }
            }
        });
    </script>
@endif