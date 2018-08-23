@extends('shop::layouts.master')
@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="address-form-content">
            <div class="title">Add Address</div>

            <form method="post" action="{{ route('customer.address.create') }}">

                <div class="edit-form">
                    @csrf

                    <div class="control-group" :class="[errors.has('address1') ? 'has-error' : '']">
                        <label for="first_name">Address Line 1</label>
                        <input type="text" class="control" name="address1" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('address2') ? 'has-error' : '']">
                        <label for="address2">Address Line 2</label>
                        <input type="text" class="control" name="address2" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('address2')">@{{ errors.first('address2') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
                        <label for="country">Country</label>
                        <input type="text" class="control" name="country" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('country')">@{{ errors.first('country') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('state') ? 'has-error' : '']">
                        <label for="state">state</label>
                        <input type="text" class="control" name="state" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('state')">@{{ errors.first('state') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                        <label for="city">city</label>
                        <input type="text" class="control" name="city" v-validate="'required'">
                        <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                        <label for="postcode">Postcode</label>
                        <input type="text" class="control" name="postcode" v-validate="'required|digits:6'">
                        <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                    </div>

                    <div class="button-group">
                        <input class="btn btn-primary btn-lg" type="submit" value="Create Address">
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection