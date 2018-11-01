@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.address.edit.page-title') }}
@endsection

@section('content-wrapper')

    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head mb-15">
                <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
                <span class="account-heading">{{ __('shop::app.customer.account.address.edit.title') }}</span>
                <span></span>
            </div>

            <form method="post" action="{{ route('customer.address.edit', $address->id) }}" @submit.prevent="onSubmit">

                <div class="account-table-content">
                    @method('PUT')
                    @csrf

                    <div class="control-group" :class="[errors.has('address1') ? 'has-error' : '']">
                        <label for="first_name" class="required">{{ __('shop::app.customer.account.address.create.address1') }}</label>
                        <input type="text" class="control" name="address1" v-validate="'required'" value="{{ $address->address1 }}">
                        <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('address2') ? 'has-error' : '']">
                        <label for="address2">{{ __('shop::app.customer.account.address.create.address2') }}</label>
                        <input type="text" class="control" name="address2" value ="{{ $address->address2 }}">
                        <span class="control-error" v-if="errors.has('address2')">@{{ errors.first('address2') }}</span>
                    </div>

                    @include ('shop::customers.account.address.country-state', ['countryCode' => old('country') ?? $address->country, 'stateCode' => old('state') ?? $address->state])

                    <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                        <label for="city" class="required">{{ __('shop::app.customer.account.address.create.city') }}</label>
                        <input type="text" class="control" name="city" v-validate="'required|alpha_spaces'" value="{{ $address->city }}">
                        <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                        <label for="postcode" class="required">{{ __('shop::app.customer.account.address.create.postcode') }}</label>
                        <input type="text" class="control" name="postcode" v-validate="'required'" value="{{ $address->postcode }}">
                        <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="phone" class="required">{{ __('shop::app.customer.account.address.create.phone') }}</label>
                        <input type="text" class="control" name="phone" v-validate="'required'" value="{{ $address->phone }}">
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>

                    <div class="button-group">
                        <button class="btn btn-primary btn-lg" type="submit">
                            {{ __('shop::app.customer.account.address.create.submit') }}
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection