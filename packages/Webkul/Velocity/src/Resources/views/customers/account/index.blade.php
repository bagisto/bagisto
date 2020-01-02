@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="account-content row no-margin">
        <div class="sidebar col-2">
            @include('shop::customers.account.partials.sidemenu')
        </div>

        <div class="account-layout col-10 mt10">
            @yield('page-detail-wrapper')
        </div>
    </div>
@endsection