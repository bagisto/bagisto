@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="account-content row no-margin velocity-divide-page">
        <div class="sidebar left">
            @include('shop::customers.account.partials.sidemenu')
        </div>

        <div class="account-layout right mt10">
            @if (request()->route()->getName() !== 'shop.customer.profile.index')
                @if (Breadcrumbs::exists())
                    {{ Breadcrumbs::render() }}
                @endif
            @endif

            @yield('page-detail-wrapper')
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>
@endpush