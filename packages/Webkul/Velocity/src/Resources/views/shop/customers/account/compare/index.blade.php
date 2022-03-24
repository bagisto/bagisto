@extends('shop::customers.account.index')

@include('velocity::guest.compare.compare-products')

@section('page_title')
    {{ __('velocity::app.customer.compare.compare_similar_items') }}
@endsection

@section('page-detail-wrapper')
    <div class="compare-container">
        <compare-product></compare-product>
    </div>
@endsection
