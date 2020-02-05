@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>

        <h1 class="account-heading">
            {{ __('shop::app.customer.account.profile.index.title') }}
        </h1>

        <div class="horizontal-rule"></div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

        <form
            method="POST"
            @submit.prevent="onSubmit"
            class="account-table-content"
            action="{{ route('customer.profile.edit') }}">

            @csrf

            <div class="row">
                <label class="col-12 mandatory">
                    {{ __('shop::app.customer.account.profile.fname') }}
                </label>

                <div class="col-12">
                    <input value="{{ $customer->first_name }}" name="first_name" type="text" v-validate="'required'" />
                    <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                </div>
            </div>

            <div class="row">
                <label class="col-12">
                    {{ __('shop::app.customer.account.profile.lname') }}
                </label>

                <div class="col-12">
                    <input value="{{ $customer->last_name }}" name="last_name" type="text" />
                </div>
            </div>

            <div class="row">
                <label class="col-12">
                    {{ __('shop::app.customer.account.profile.gender') }}
                </label>

                <div class="col-12">
                    <select
                        name="gender"
                        class="control styled-select"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">

                        <option value=""  @if ($customer->gender == "") selected @endif></option>
                        <option
                            value="Other"
                            @if ($customer->gender == "Other")
                                selected="selected"
                            @endif>
                            {{ __('velocity::app.shop.gender.other') }}
                        </option>

                        <option
                            value="Male"
                            @if ($customer->gender == "Male")
                                selected="selected"
                            @endif>
                            {{ __('velocity::app.shop.gender.male') }}
                        </option>

                        <option
                            value="Female"
                            @if ($customer->gender == "Female")
                                selected="selected"
                            @endif>
                            {{ __('velocity::app.shop.gender.female') }}
                        </option>
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>
                </div>

            </div>

            <div class="row">
                <label class="col-12">
                    {{ __('shop::app.customer.account.profile.dob') }}
                </label>

                <div class="col-12">
                    <input
                        type="date"
                        name="date_of_birth"
                        placeholder="dd/mm/yyyy"
                        value="{{ $customer->date_of_birth }}" />
                </div>
            </div>

            <div class="row">
                <label class="col-12 mandatory">
                    {{ __('shop::app.customer.account.profile.email') }}
                </label>

                <div class="col-12">
                    <input value="{{ $customer->email }}" name="email" type="text" v-validate="'required'" />
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>
            </div>

            <div class="row">
                <label class="col-12">
                    {{ __('velocity::app.shop.general.enter-current-password') }}
                </label>

                <div class="col-12">
                    <input value="" name="oldpassword" type="password" />
                </div>
            </div>

            <div class="row">
                <label class="col-12">
                    {{ __('velocity::app.shop.general.new-password') }}
                </label>

                <div class="col-12">
                    <input value="" name="password" type="password" />
                </div>
            </div>

            <div class="row">
                <label class="col-12">
                    {{ __('velocity::app.shop.general.confirm-new-password') }}
                </label>

                <div class="col-12">
                    <input value="" name="password_confirmation" type="password" />
                </div>
            </div>

            <button
                type="submit"
                class="theme-btn mb20">
                {{ __('velocity::app.shop.general.update') }}
            </button>
        </form>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}
@endsection
