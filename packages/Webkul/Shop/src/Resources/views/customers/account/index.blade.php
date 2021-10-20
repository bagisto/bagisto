@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        @yield('account-content')
    </div>
@endsection
