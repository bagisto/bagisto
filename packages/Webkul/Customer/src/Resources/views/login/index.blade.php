@extends('customer::layouts.master')
@section('content-wrapper')
    <div class="content">
        <div class="sign-up-text">
            Don't have account - <a href="{{ route('customer.register') }}">Sign Up</a>
        </div>
        <form>
            <div class="login-form">
                <div class="login-text">Sign In</div>
                <div class="control-group">
                    <label for="email">E-Mail</label>
                    <input type="text" class="control" name="email">
                </div>
                <div class="control-group">
                    <label for="email">Password</label>
                    <input type="password" class="control" name="email">
                </div>
                <div class="forgot-password-link">
                    <a href="">Forgot Password?</a>
                </div>

                <input class="btn btn-primary btn-lg" type="submit" value="sign in">
            </div>
        </form>
    </div>

@endsection
