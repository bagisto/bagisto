@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('content-wrapper')
    @push('scripts')
        <script>
            window.location = '{{ route('customer.wishlist.index') }}';
        </script>
    @endpush
@endsection