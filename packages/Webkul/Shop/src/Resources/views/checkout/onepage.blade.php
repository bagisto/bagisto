@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.onepage.title') }}
@stop

@section('content-wrapper')

    <checkout></checkout>

@endsection

@push('scripts')

    <script type="text/x-template" id="checkout-template">
        <div id="checkout" class="checkout-process">

            <div class="col-main">

                <ul class="checkout-steps">
                    <li class="active" :class="[completedStep >= 0 ? 'active' : '', completedStep > 0 ? 'completed' : '']" @click="navigateToStep(1)">
                        <div class="decorator address-info"></div>
                        <span>{{ __('shop::app.checkout.onepage.information') }}</span>
                    </li>
                    
                    <li :class="[currentStep == 2 || completedStep > 1 ? 'active' : '', completedStep > 1 ? 'completed' : '']" @click="navigateToStep(2)">
                        <div class="decorator shipping"></div>
                        <span>{{ __('shop::app.checkout.onepage.shipping') }}</span>
                    </li>
                    
                    <li :class="[currentStep == 3 || completedStep > 2 ? 'active' : '', completedStep > 2 ? 'completed' : '']" @click="navigateToStep(3)">
                        <div class="decorator payment"></div>
                        <span>{{ __('shop::app.checkout.onepage.payment') }}</span>
                    </li>
                    
                    <li :class="[currentStep == 4 ? 'active' : '']">
                        <div class="decorator review"></div>
                        <span>{{ __('shop::app.checkout.onepage.complete') }}</span>
                    </li>
                </ul>

                <div class="step-content information" v-show="currentStep == 1">

                    @include('shop::checkout.onepage.customer-info')

                    <div class="button-group">

                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('address-form')">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>

                    </div>

                </div>

                <div class="step-content shipping" v-show="currentStep == 2">
                    
                    @include('shop::checkout.onepage.shipping')

                    <div class="button-group">

                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('shipping-form')">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>

                    </div>

                </div>

                <div class="step-content payment" v-show="currentStep == 3">

                    @include('shop::checkout.onepage.payment')

                </div>

                <div class="step-content review" v-show="currentStep == 4">

                    @include('shop::checkout.onepage.review')

                </div>

            </div>

            @include('shop::checkout.onepage.summary')

        </div>
    </script>

    <script>
        Vue.component('checkout', {

            template: '#checkout-template',

            inject: ['$validator'],

            data: () => ({
                currentStep: 1,

                completedStep: 0,

                shipping_methods: [],

                payment_methods: [],

                address: {
                    billing: {
                        use_for_shipping: true
                    },

                    shipping: {},
                },

                selected_shipping_method: '',

                selected_payment: {
                    method: ''
                }
            }),

            created () {

            },

            methods: {
                navigateToStep (step) {
                    if(step <= this.completedStep) {
                        this.currentStep = step
                        this.completedStep = step - 1;
                    }
                },

                validateForm: function (scope) {
                    this.$validator.validateAll(scope).then((result) => {
                        if(result) {
                            if(scope == 'address-form') {
                                this.saveAddress()
                            } else if(scope == 'shipping-form') {
                                this.saveShipping()
                            }
                        }
                    });
                },

                saveAddress () {
                    var this_this = this;

                    this.$http.post("{{ route('shop.checkout.save-address') }}", this.address)
                        .then(function(response) {
                            if(response.data.shipping) {
                                this_this.shipping_methods = response.data.shipping
                                this_this.completedStep = 1;
                                this_this.currentStep = 2;
                            }
                        })
                },

                saveShipping () {
                    // this.$http.get('https://api.coindesk.com/v1/bpi/currentprice.json')
                    //     .then(function(response) {
                            this.completedStep = 2;
                            this.currentStep = 3;
                        // })
                },

                savePayment () {
                    // this.$http.get('https://api.coindesk.com/v1/bpi/currentprice.json')
                    //     .then(function(response) {
                            this.completedStep = 1;
                            this.currentStep = 2;
                        // })
                }
            }
        })
    </script>

@endpush