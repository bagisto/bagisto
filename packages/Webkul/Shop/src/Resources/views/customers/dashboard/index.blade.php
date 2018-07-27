@extends('shop::layouts.master')
@section('content-wrapper')
<div class="dashboard-content">
    <ul class="dashboard-side-menu">
        <li>Profile</li>
        <li>Orders</li>
        <li>Address</li>
        <li>Reviews</li>
        <li>Wishlist</li>
    </ul>
    <div class="profile">
        <div class="section-head">
            <span class="profile-heading">Profile</span>
            <span class="profile-edit">Edit</span>
            <div class="horizontal-rule"></div>
        </div>
        <div class="profile-content">
            <table>
                <tbody>
                    <tr>
                        <td>First Name</td>
                        <td>{{ $customer['first_name'] }}</td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td>{{ $customer['last_name'] }}</td>
                    </tr>
                    <tr>
                        <td>Gender Name</td>
                        <td>{{ $customer['gender'] }}</td>
                    </tr>
                    <tr>
                        <td>Date of Birth</td>
                        <td>{{ $customer['date_of_birth'] }}</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td>{{ $customer['email'] }}</td>
                    </tr>
                    <tr>
                        <td>Mobile</td>
                        <td>{{ $customer['phone'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
