
@extends('shop::layouts.master')

@section('content-wrapper')

@include('shop::customers.checkout.common')

<div class="ship-method">
    <div class="ship-info">
        <span class="ship-text">
            Shipment Method
        </span>
    </div>

    <div class="ship-price">
        <div class="price-checkbox">
            <img src="{{asset('themes/default/assets/images/selected.svg')}}" />
            <span> $ 25.00 </span>
        </div>
        <div class="price-checkbox-text">
            <b>Fadex - </b>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.</span>
        </div>
    </div>

    <div class="ship-price">
        <div class="price-checkbox">
            <img src="{{asset('themes/default/assets/images/unselected.svg')}}" />
            <span> $ 25.00 </span>
        </div>
        <div class="price-checkbox-text">
            <b>Fadex - </b>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.</span>
        </div>
    </div>

    <div class="ship-price">
        <div class="price-checkbox">
            <img src="{{asset('themes/default/assets/images/unselected.svg')}}" />
            <span> $ 25.00 </span>
        </div>
        <div class="price-checkbox-text">
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