@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-10">
        <span class="account-heading">
            {{ __('shop::app.customer.account.order.index.title') }}
        </span>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

        <div class="account-items-list">
            <div class="account-table-content">

                <datagrid-plus src="{{ route('customer.orders.index') }}"></datagrid-plus>

            </div>
        </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
@endsection