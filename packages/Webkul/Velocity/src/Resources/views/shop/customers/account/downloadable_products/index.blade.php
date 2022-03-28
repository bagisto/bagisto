@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.downloadable_products.title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-10">
        <span class="account-heading">
            {{ __('shop::app.customer.account.downloadable_products.title') }}
        </span>

        <div class="horizontal-rule"></div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.before') !!}

        <div class="account-items-list">
            <div class="account-table-content">

                <datagrid-plus src="{{ route('customer.downloadable_products.index') }}"></datagrid-plus>

            </div>
        </div>

    {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.after') !!}
@endsection