@extends('shop::store.layouts.master')

@section('content-wrapper')
    <div class="content">
        <div class="sign-up-text">
            Already have an account - <a href="{{ route('customer.session.index') }}">Sign In</a>
        </div>

        <form method="post" action="{{ route('customer.register.create') }}">
            {{ csrf_field() }}

            <div class="login-form">
                <div class="login-text">Sign Up</div>

                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <label for="email">First Name</label>
                    <input type="text" v-validate="'required'" class="control" name="first_name">
                    <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                </div>

                <div class="control-group">
                    <label for="email">Last Name</label>
                    <input type="text" class="control" name="last_name">
                </div>

                <div class="control-group">
                    <label for="email">Email</label>
                    <input type="email" class="control" name="email">
                </div>

                <div class="control-group">
                    <label for="email">Password</label>
                    <input type="password" class="control" name="password">
                </div>

                <div class="control-group">
                    <label for="email">Confirm Password</label>
                    <input type="password" class="control" name="confirm_password">
                </div>

                <div class="signup-confirm">
                    <span class="checkbox">
                        <input type="checkbox" id="checkbox2" name="agreement" required>
                        <label class="checkbox-view" for="checkbox2"></label>
                        <span>Agree <a href="">Terms</a> & <a href="">Conditions</a> by using this website.</span>
                    </span>
                </div>
                
                <input class="btn btn-primary btn-lg" type="submit" value="sign in">
            </div>

        </form>

    </div>
@endsection
