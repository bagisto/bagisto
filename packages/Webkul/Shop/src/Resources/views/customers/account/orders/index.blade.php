@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('account-content')
    <div class="account-layout">
        <div class="account-head mb-10">
            <span class="back-icon"><a href="{{ route('customer.profile.index') }}"><i class="icon icon-menu-back"></i></a></span>

            <span class="account-heading">
                {{ __('shop::app.customer.account.order.index.title') }}
            </span>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

        <div class="account-items-list">
            <div class="account-table-content">
                
                <datagrid-plus src="{{ route('customer.orders.index') }}"></datagrid-plus>
                
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
    </div>
@endsection