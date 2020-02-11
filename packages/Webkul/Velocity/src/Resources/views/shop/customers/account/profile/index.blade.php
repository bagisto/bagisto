@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@push('css')
    <style type="text/css">
        .account-head {
            height: 50px;
        }
        .table {
            width: 70%;
            padding: 10px;
        }
        .table > table {
            color: #5E5E5E;
            width:100%;
            border: 1px solid rgba(0,0,0,.125);
        }
        .table td {
            padding: 5px;
            border: unset;
        }
        .remove-icon {
            right: 15px;
            font-size: 22px;
            height: 24px;
            text-align: center;
            position: absolute;
            border-radius: 50%;
            color: #333;
            width: 24px;
            padding: 0px;
            top: 10px;
        }
        .remove-icon:before {
            content: "x";
        }
    </style>
@endpush


@section('page-detail-wrapper')
    <div class="account-head">
        <a href="{{ route('customer.session.destroy') }}" class="theme-btn light unset pull-right">
            {{ __('shop::app.header.logout') }}
        </a>
<<<<<<< HEAD
        
        <span class="account-heading">
            {{ __('shop::app.customer.account.profile.index.title') }}
        </span>

        <span class="account-action">
            <a href="{{ route('customer.profile.edit') }}" class="theme-btn light unset pull-right">
                {{ __('shop::app.customer.account.profile.index.edit') }}
            </a>
        </span>

        <div class="horizontal-rule"></div>
=======

        <h1 class="account-heading">
            {{ __('shop::app.customer.account.profile.index.title') }}
        </h1>
>>>>>>> upstream/master
    </div>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

<<<<<<< HEAD
    <div class="account-table-content">
        <div class="table">
            <table>
                <tbody>
                    {!! view_render_event(
                    'bagisto.shop.customers.account.profile.view.table.before', ['customer' => $customer])
                    !!}

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.fname') }}</td>
                        <td>{{ $customer->first_name }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.lname') }}</td>
                        <td>{{ $customer->last_name }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.gender') }}</td>
                        <td>{{ $customer->gender ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.dob') }}</td>
                        <td>{{ $customer->date_of_birth ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.email') }}</td>
                        <td>{{ $customer->email }}</td>
                    </tr>

                    {!! view_render_event(
                    'bagisto.shop.customers.account.profile.view.table.after', ['customer' => $customer])
                    !!}
                </tbody>
            </table>
        </div>

        <button
            type="submit"
            class="theme-btn mb20" @click="showModal('deleteProfile')" >
            {{ __('shop::app.customer.account.address.index.delete') }}
        </button>

        <form method="POST" action="{{ route('customer.profile.destroy') }}" @submit.prevent="onSubmit">
            @csrf

            <modal id="deleteProfile" :is-open="modalIds.deleteProfile">
                <h3 slot="header">{{ __('shop::app.customer.account.address.index.enter-password') }}
                </h3>
                <i class="rango-close"></i>

                <div slot="body">
                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password" class="required">{{ __('admin::app.users.users.password') }}</label>
                        <input type="password" v-validate="'required|min:6|max:18'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.users.password') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
=======
        <div class="profile-update-form">
            <form
                method="POST"
                @submit.prevent="onSubmit"
                class="account-table-content"
                action="{{ route('customer.profile.edit') }}">

                @csrf

                <div :class="`row ${errors.has('first_name') ? 'has-error' : ''}`">
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
>>>>>>> upstream/master
                    </div>
                </div>

<<<<<<< HEAD
                    <div class="page-action">
                        <button type="submit"  class="theme-btn mb20">
                        {{ __('shop::app.customer.account.address.index.delete') }}
                        </button>
                    </div>
                </div>
            </modal>
        </form>        
    </div>
=======
                <div :class="`row ${errors.has('gender') ? 'has-error' : ''}`">
                    <label class="col-12 mandatory">
                        {{ __('shop::app.customer.account.profile.gender') }}
                    </label>

                    <div class="col-12">
                        <select
                            name="gender"
                            v-validate="'required'"
                            class="control styled-select"
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

                        <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                    </div>
                </div>

                <div :class="`row ${errors.has('date_of_birth') ? 'has-error' : ''}`">
                    <label class="col-12">
                        {{ __('shop::app.customer.account.profile.dob') }}
                    </label>

                    <div class="col-12">
                        <input
                            type="date"
                            name="date_of_birth"
                            placeholder="dd/mm/yyyy"
                            value="{{ old('date_of_birth') ?? $customer->date_of_birth }}"
                            v-validate="" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.dob') }}&quot;" />

                            <span class="control-error" v-if="errors.has('date_of_birth')">
                                @{{ errors.first('date_of_birth') }}
                            </span>
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

                    <div :class="`col-12 ${errors.has('oldpassword') ? 'has-error' : ''}`">
                        <input value="" name="oldpassword" type="password" />
                    </div>
                </div>

                <div class="row">
                    <label class="col-12">
                        {{ __('velocity::app.shop.general.new-password') }}
                    </label>

                    <div :class="`col-12 ${errors.has('password') ? 'has-error' : ''}`">
                        <input
                            value=""
                            name="password"
                            type="password"
                            v-validate="'min:6|max:18'" />

                        <span class="control-error" v-if="errors.has('password')">
                            @{{ errors.first('password') }}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <label class="col-12">
                        {{ __('velocity::app.shop.general.confirm-new-password') }}
                    </label>

                    <div :class="`col-12 ${errors.has('password_confirmation') ? 'has-error' : ''}`">
                        <input value="" name="password_confirmation" type="password"
                        v-validate="'min:6|confirmed:password'" data-vv-as="confirm password" />

                        <span class="control-error" v-if="errors.has('password_confirmation')">
                            @{{ errors.first('password_confirmation') }}
                        </span>
                    </div>
                </div>

                <button
                    type="submit"
                    class="theme-btn mb20">
                    {{ __('velocity::app.shop.general.update') }}
                </button>
            </form>
        </div>
>>>>>>> upstream/master

    {!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}
@endsection