@extends('shop::layouts.master')

@section('content-wrapper')

    <checkout customer="{{$customer_id}}"> </checkout>

@endsection

@push('scripts')

    <script type="text/x-template" id="checkout-template">

        <div>
            @include('shop::customers.checkout.common.common')

            <div v-if="customer">
                @include('shop::customers.checkout.ship-method')
            </div>
            <div v-if="!customer">
                @include('shop::customers.checkout.guest')
            </div>
        </div>

    </script>

    <script>

        Vue.component('checkout', {

            props: ['customer'],

            data: () => ({
                isGuest:true,
                isShip:false,
                disabled: 0,
                isShipMethod:false
            }),

            template: '#checkout-template',

            mounted () {

                if(this.customer){
                    this.isShip=true;
                }else{
                    this.isGuest=true;
                    this.disabled=1;
                }
            } ,

            methods: {
                count () {
                    this.isShipMethod=true;
                }
            }

        })

    </script>



@endpush



