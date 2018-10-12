@extends('shop::layouts.master')
@section('page_title')
    {{ __('shop::app.customer.account.profile.index.page-title') }}
@endsection
@section('content-wrapper')

<div class="account-content">

    @include('shop::customers.account.partials.sidemenu')

    <div class="account profile">

        <div class="section-head">
            <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
            <span class="profile-heading">{{ __('shop::app.customer.account.profile.index.title') }}</span>
            <span class="profile-edit"><a href="{{ route('customer.profile.edit') }}">{{ __('shop::app.customer.account.profile.index.edit') }}</a></span>
            <div class="horizontal-rule"></div>
        </div>

        <div class="profile-content">
            <table>
                <tbody>
                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.fname') }}</td>
                        <td>{{ $customer['first_name'] }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.lname') }}</td>
                        <td>{{ $customer['last_name'] }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.gender') }}</td>
                        <td>{{ $customer['gender'] }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.dob') }}</td>
                        <td>{{ $customer['date_of_birth'] }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.email') }}</td>
                        <td>{{ $customer['email'] }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.phone') }}</td>
                        <td>{{ $customer['phone'] }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>

</div>
@endsection
