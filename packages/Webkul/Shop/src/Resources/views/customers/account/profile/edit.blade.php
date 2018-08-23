@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="account-content">

        @include('shop::customers.account.partials.sidemenu')

        <div class="edit-form-content">

            <div class="title">Edit Profile</div>

            <form method="post" action="{{ route('customer.profile.edit') }}">

                <div class="edit-form">
                    @csrf

                    <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                        <label for="first_name">First Name</label>
                        <input type="text" class="control" name="first_name" value="{{ $customer['first_name'] }}" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="control" name="last_name" value="{{ $customer['last_name'] }}" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('gender') ? 'has-error' : '']">
                        <label for="email">Gender</label>
                        <select name="gender" class="control" v-validate="'required'">
                            <option value="Male"  @if($customer['gender']=="Male") selected @endif>Male</option>
                            <option value="Female" @if($customer['gender']=="Female") selected @endif>Female</option>
                        </select>
                        <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" class="control" name="date_of_birth" value="{{ $customer['date_of_birth'] }}" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="phone">Phone</label>
                        <input type="text" class="control" name="phone" value="{{ $customer['phone'] }}" v-validate="'required|digits:10'">
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email">Email</label>
                        <input type="email" class="control" name="email" value="{{ $customer['email'] }}" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('old_password') ? 'has-error' : '']">
                        <label for="password">Old Password</label>
                        <input type="oldpassword" class="control" name="oldpassword">
                        <span class="control-error" v-if="errors.has('oldpassword')">@{{ errors.first('oldpassword') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password">Password</label>
                        <input type="password" class="control" name="password">
                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="password">Confirm Password</label>
                        <input type="password" class="control" name="password_confirmation">
                        <span>@{{ errors.first('password') }}</span>
                    </div>

                    <div class="button-group">
                        <input class="btn btn-primary btn-lg" type="submit" value="Update Profile">
                    </div>
                </div>

            </form>

        </div>

    </div>
@endsection
