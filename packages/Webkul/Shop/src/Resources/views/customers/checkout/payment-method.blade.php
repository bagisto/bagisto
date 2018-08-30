
@extends('shop::layouts.master')

@section('content-wrapper')

@include('shop::customers.checkout.common')

<div class="payment-method">
    <div class="payment-info">
        <span class="payment-text">
            Payment Method
        </span>
    </div>

    <div class="payment-price">
        <div class="payment-checkbox">
            <img src="{{asset('themes/default/assets/images/unselected.svg')}}" />
            <span> Cash on Delivery</span>
        </div>
        <div class="payment-checkbox-text">
            <b>Fadex - </b>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.</span>
        </div>
    </div>

    <div class="payment-price">
        <div class="payment-checkbox">
            <img src="{{asset('themes/default/assets/images/selected.svg')}}" />
            <span> Net Banking</span>
        </div>
        <div class="payment-checkbox-text">
            <b>Fadex - </b>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.</span>
        </div>
    </div>

    <div class="payment-price">
        <div class="payment-checkbox">
            <img src="{{asset('themes/default/assets/images/unselected.svg')}}" />
            <span> Debit / Credit Card</span>
        </div>
        <div class="payment-checkbox-text">
            <b>Fadex - </b>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.</span>
        </div>
    </div>

   
    <div class="horizontal-rule">
    </div>

    <div class="countinue-button">
        <button>CONTINUE</button>
    </div>
</div>

@endsection