@extends('shop::layouts.master')

@section('page_title')
Customer - Address
@endsection

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
            @if($address->isEmpty())
                <div>You don't have any saved addresses here, please create a new one by clicking the link below.</div>
                <br/>
                <a href="{{ route('customer.address.create') }}">Create Address</a>
            @else
                <table>
                    <tbody>
                        <tr>
                            <td>Address 1</td>
                            <td>{{ $address['address1'] }}</td>
                        </tr>

                        <tr>
                            <td>Address 2</td>
                            <td>{{ $address['address2'] }}</td>
                        </tr>

                        <tr>
                            <td>Country</td>
                            <td>{{ $address['country'] }}</td>
                        </tr>

                        <tr>
                            <td>State</td>
                            <td>{{ $address['state'] }}</td>
                        </tr>

                        <tr>
                            <td>City</td>
                            <td>{{ $address['city'] }}</td>
                        </tr>

                        <tr>
                            <td>Postcode</td>
                            <td>{{ $address['postcode'] }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

        </div>

    </div>

</div>
@endsection
