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
                    <li class="active">
                        <div class="decorator">
                            <img src="{{ bagisto_asset('images/address.svg') }}" />
                        </div>
                        
                        <span>{{ __('shop::app.checkout.onepage.information') }}</span>
                    </li>
                    
                    <li>
                        <div class="decorator">
                            <img src="{{ bagisto_asset('images/shipping.svg') }}" />
                        </div>
                        
                        <span>{{ __('shop::app.checkout.onepage.shipping') }}</span>
                    </li>
                    
                    <li>
                        <div class="decorator">
                            <img src="{{ bagisto_asset('images/payment.svg') }}" />
                        </div>
                        
                        <span>{{ __('shop::app.checkout.onepage.payment') }}</span>
                    </li>
                    
                    <li>
                        <div class="decorator">
                            <img src="{{ bagisto_asset('images/finish.svg') }}" />
                        </div>
                        
                        <span>{{ __('shop::app.checkout.onepage.complete') }}</span>
                    </li>
                </ul>

                <div class="step-content information">

                    @include('shop::checkout.onepage.customer-info')

                    <div class="button-group">

                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('address-form')">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>

                    </div>

                </div>

            </div>

            <div class="step-content shipping">
            </div>

            <div class="step-content payment">
            </div>

            <div class="step-content review">
            </div>

            @include('shop::checkout.onepage.summary')

        </div>
    </script>

    <script>
        Vue.component('checkout', {

            template: '#checkout-template',

            inject: ['$validator'],

            data: () => ({
                billing: {
                    use_for_shipping: true
                },
                shipping: {},
            }),

            created () {

            },

            methods: {
                validateForm: function (scope) {
                    this.$validator.validateAll(scope).then((result) => {
                        if(result) {
                            this.saveAddress()
                        }
                    });
                },

                saveAddress () {
                    // this.$http.get('https://api.coindesk.com/v1/bpi/currentprice.json')
                    //     .then(function(response) {
                    //         console.log(response)
                    //     })
                }
            }
        })
    </script>

@endpush