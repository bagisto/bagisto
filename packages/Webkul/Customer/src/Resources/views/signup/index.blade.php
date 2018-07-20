@extends('customer::layouts.master')
@section('content-wrapper')
<div class="content">
    <div class="sign-up-text">
        Already have an account - <a href="{{ route('customer.login') }}">Sign In</a>
    </div>
    <form>
        <div class="login-form">
            <div class="login-text">Sign Up</div>
            <div class="control-group">
                <label for="email">First Name</label>
                <input type="text" class="control" name="email">
            </div>
            <div class="control-group">
                <label for="email">Last Name</label>
                <input type="text" class="control" name="email">
            </div>
            <div class="control-group">
                <label for="email">Email</label>
                <input type="email" class="control" name="email">
            </div>
            <div class="control-group">
                <label for="email">Password</label>
                <input type="password" class="control" name="email">
            </div>
            <div class="control-group">
                <label for="email">Confirm Password</label>
                <input type="password" class="control" name="email">
            </div>
            <div class="signup-confirm">
                <span class="checkbox">
                        <input type="checkbox" id="checkbox2" name="checkbox[]">
                        <label class="checkbox-view" for="checkbox2"></label>
                        <span>Agree <a href="">Terms</a> & <a href="">Conditions</a> by using this website.</span>
                </span>

            </div>

            <input class="btn btn-primary btn-lg" type="submit" value="sign in">
        </div>
    </form>

</div>
@endsection
