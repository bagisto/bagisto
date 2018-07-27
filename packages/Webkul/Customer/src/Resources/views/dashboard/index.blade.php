@extends('customer::layouts.master')
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
        <div class="section-heading">
            Profile
        </div>
        <div class="profile-content">
            <table>
                <tbody>
                    <tr>
                        <td>First Name</td>
                        <td>Prashant</td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td>Singh</td>
                    </tr>
                    <tr>
                        <td>Gender Name</td>
                        <td>Male</td>
                    </tr>
                    <tr>
                        <td>Date of Birth</td>
                        <td>1/1/1993</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td>Prashant@webkul.com</td>
                    </tr>
                    <tr>
                        <td>Mobile</td>
                        <td>+91-9988887744</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
