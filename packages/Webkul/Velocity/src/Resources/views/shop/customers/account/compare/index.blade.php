@extends('shop::customers.account.index')

@include('velocity::guest.compare.compare-products')

@section('page_title')
    {{ __('velocity::app.customer.compare.compare_similar_items') }}
@endsection

@push('css')
    <style>
        .compare-products .col, .compare-products .col-2 {
            max-width: 25%;
        }
    </style>
@endpush

@section('page-detail-wrapper')
    <compare-product></compare-product>
@endsection
