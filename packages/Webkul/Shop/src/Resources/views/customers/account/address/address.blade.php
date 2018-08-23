@extends('shop::layouts.master')

@section('content-wrapper')

<div class="account-content">

    @include('shop::customers.account.partials.sidemenu')

    <div class="profile">

        <div class="section-head">
            <span class="profile-heading">Address</span>
            <span class="profile-edit"><a href="{{ route('customer.address.edit') }}">Edit</a></span>
            <div class="horizontal-rule"></div>
        </div>

        <div class="profile-content">

            @if(gettype($address) == "string")
                <div>You don't have any saved addresses here, please create a new one by clicking the link below.</div>
                <br/>
                <a href="{{ route('customer.address.create') }}">Create Address</a>
            @else
                <table>
                    <thead>
                        <tr>
                            <td>Address 1</td>

                            <td>Address 2</td>

                            <td>Country</td>

                            <td>State</td>

                            <td>City</td>

                            <td>Postcode</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>One</td>

                            <td>One</td>

                            <td>One</td>

                            <td>One</td>

                            <td>One</td>

                            <td>One</td>
                        </tr>
                    </tbody>
                </table>
            @endif

        </div>

    </div>

</div>
@endsection
