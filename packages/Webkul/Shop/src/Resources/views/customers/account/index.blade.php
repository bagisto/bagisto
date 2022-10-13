@extends('shop::layouts.master')

@section('content-wrapper')
    <div>
        @if (request()->route()->getName() !== 'shop.customer.profile.index')
            @if (Breadcrumbs::exists())
                {{ Breadcrumbs::render() }}
            @endif
        @endif
    </div>

    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        @yield('account-content')
    </div>
@endsection
