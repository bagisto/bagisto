@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="account-content">

        @include('shop::customers.account.partials.sidemenu')

        <div class="edit-form-content">

            <div class="edit-text">Edit Profile</div>

            <form method="post" action="{{ route('customer.register.create') }}">

                <div class="edit-form">

                    {{ csrf_field() }}

                    <div class="control-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="control" name="first_name" value="{{ $customer['first_name'] }}" v-validate="'required'">
                        <span>@{{ errors.first('first_name') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="control" name="last_name" value="{{ $customer['last_name'] }}" v-validate="'required'">
                        <span>@{{ errors.first('last_name') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="email">Email</label>
                        <input type="email" class="control" name="email" value="{{ $customer['email'] }}" v-validate="'required'">
                        <span>@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="email">Gender</label>
                        <select name="gender" class="control" value="{{ $customer['gender'] }}" v-validate="'required'">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <span>@{{ errors.first('gender') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="control" name="dob" value="{{ $customer['date_of_birth'] }}" v-validate="'required'">
                        <span>@{{ errors.first('first_name') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="control" name="phone" value="{{ $customer['phone'] }}" v-validate="'required'">
                        <span>@{{ errors.first('phone') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="password">Password</label>
                        <input type="password" class="control" name="password">
                        <span>@{{ errors.first('password') }}</span>
                    </div>


                    <div class="control-group">
                        <label for="password">Confirm Password</label>
                        <input type="password" class="control" name="password">
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
