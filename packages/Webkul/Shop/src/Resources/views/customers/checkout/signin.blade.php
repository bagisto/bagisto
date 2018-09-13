
@extends('shop::layouts.master')

@section('content-wrapper')

@include('shop::customers.checkout.common')

<div class="signin-form">
    <div class="signin-guest">
        <span class="signin-text">
            Sign In
        </span>
        <button class="order-button">
            ORDER AS GUEST
        </button>
    </div>

    <div class="control-group">
        <label for="first_name">Email </label>
        <input type="text" class="control" name="email_address">
    </div>

    <div class="control-group">
        <label for="first_name">Password </label>
        <input type="text" class="control" name="first_name">
    </div>

    <div class="forgot-pass">
        <span>Forgot Password</span>
    </div>

    <div class="horizontal-rule">
    </div>

    <div class="countinue-button">
        <button>CONTINUE</button>
    </div>
</div>

@endsection