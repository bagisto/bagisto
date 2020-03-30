@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="account-content row no-margin velocity-divide-page">
        <div class="sidebar left">
            @include('shop::customers.account.partials.sidemenu')
        </div>

        <div class="account-layout right mt10">
            @yield('page-detail-wrapper')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let height = $('.customer-sidebar').css('height');

            $('.account-content').css('height', height);
        });
    </script>
@endpush