@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.profile.edit-profile.page-title') }}
@endsection

@section('content-wrapper')
    <div class="account-content">

        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head mb-10">
                <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>

                <span class="account-heading">{{ __('shop::app.customer.account.profile.edit-profile.title') }}</span>

                <span></span>
            </div>

            <form method="post" action="{{ route('customer.profile.edit') }}">

                <div class="edit-form">
                    @csrf

                    <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                        <label for="first_name">{{ __('shop::app.customer.account.profile.fname') }}</label>
                        <input type="text" class="control" name="first_name" value="{{ $customer['first_name'] }}" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                        <label for="last_name">{{ __('shop::app.customer.account.profile.lname') }}</label>
                        <input type="text" class="control" name="last_name" value="{{ $customer['last_name'] }}" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('gender') ? 'has-error' : '']">
                        <label for="email">{{ __('shop::app.customer.account.profile.gender') }}</label>
                        <select name="gender" class="control" v-validate="'required'">
                            <option value="Male"  @if($customer['gender']=="Male") selected @endif>Male</option>
                            <option value="Female" @if($customer['gender']=="Female") selected @endif>Female</option>
                        </select>
                        <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                        <label for="date_of_birth">{{ __('shop::app.customer.account.profile.dob') }}</label>
                        <input type="date" class="control" name="date_of_birth" value="{{ $customer['date_of_birth'] }}" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="phone">{{ __('shop::app.customer.account.profile.phone') }}</label>
                        <input type="text" class="control" name="phone" value="{{ $customer['phone'] }}" v-validate="'required|digits:10'">
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email">{{ __('shop::app.customer.account.profile.email') }}</label>
                        <input type="email" class="control" name="email" value="{{ $customer['email'] }}" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('old_password') ? 'has-error' : '']">
                        <label for="password">{{ __('shop::app.customer.account.profile.opassword') }}</label>
                        <input type="oldpassword" class="control" name="oldpassword">
                        <span class="control-error" v-if="errors.has('oldpassword')">@{{ errors.first('oldpassword') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password">{{ __('shop::app.customer.account.profile.password') }}</label>
                        <input type="password" class="control" name="password">
                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="password">{{ __('shop::app.customer.account.profile.cpassword') }}</label>
                        <input type="password" class="control" name="password_confirmation">
                        <span>@{{ errors.first('password') }}</span>
                    </div>

                    <div class="button-group">
                        <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.account.profile.submit') }}">
                    </div>
                </div>

            </form>

        </div>

    </div>
@endsection
