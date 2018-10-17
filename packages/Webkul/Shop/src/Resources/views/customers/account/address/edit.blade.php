@extends('shop::layouts.master')
@section('page_title')
    {{ __('shop::app.customer.account.address.edit.page-title') }}
@endsection
@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head mb-15">
                <div class="account-heading">{{ __('shop::app.customer.account.address.edit.title') }}</div>
            </div>

            <form method="post" action="{{ route('customer.address.edit') }}">

                <div class="account-table-content">
                    @csrf

                    <div class="control-group" :class="[errors.has('address1') ? 'has-error' : '']">
                        <label for="first_name">{{ __('shop::app.customer.account.address.create.address1') }}</label>
                        <input type="text" class="control" name="address1" v-validate="'required'" value ="{{ $address['address1'] }}">
                        <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('address2') ? 'has-error' : '']">
                        <label for="address2">{{ __('shop::app.customer.account.address.create.address2') }}</label>
                        <input type="text" class="control" name="address2" v-validate="'required'" value ="{{ $address['address2'] }}">
                        <span class="control-error" v-if="errors.has('address2')">@{{ errors.first('address2') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
                        <label for="country">{{ __('shop::app.customer.account.address.create.country') }}</label>
                        <input type="text" class="control" name="country" v-validate="'required'" value ="{{ $address['country'] }}">
                        <span class="control-error" v-if="errors.has('country')">@{{ errors.first('country') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('state') ? 'has-error' : '']">
                        <label for="state">{{ __('shop::app.customer.account.address.create.state') }}</label>
                        <input type="text" class="control" name="state" v-validate="'required'" value ="{{ $address['state'] }}">
                        <span class="control-error" v-if="errors.has('state')">@{{ errors.first('state') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                        <label for="city">{{ __('shop::app.customer.account.address.create.city') }}</label>
                        <input type="text" class="control" name="city" v-validate="'required|alpha_spaces'" value ="{{ $address['city'] }}">
                        <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                        <label for="postcode">{{ __('shop::app.customer.account.address.create.postcode') }}</label>
                        <input type="text" class="control" name="postcode" v-validate="'required'" value ="{{ $address['postcode'] }}">
                        <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                    </div>

                    <div class="button-group">
                        <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.account.address.edit.submit') }}">
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection