@extends('shop::layouts.master')

@include('shop::guest.compare.compare-products')

@section('page_title')
    {{ __('velocity::app.customer.compare.compare_similar_items') }}
@endsection

@section('content-wrapper')

    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">
            {!! view_render_event('bagisto.shop.customers.account.comparison.list.before') !!}

            <div class="account-items-list">
                <div class="account-table-content">
                    <compare-product></compare-product>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.comparison.list.after') !!}

        </div>

    </div>

@endsection
