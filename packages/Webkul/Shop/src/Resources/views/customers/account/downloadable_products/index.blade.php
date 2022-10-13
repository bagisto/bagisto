@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.downloadable_products.title') }}
@endsection

@section('account-content')
    <div class="account-layout">
        <div class="account-head mb-10">
            <span class="back-icon"><a href="{{ route('shop.customer.profile.index') }}"><i class="icon icon-menu-back"></i></a></span>

            <span class="account-heading">
                {{ __('shop::app.customer.account.downloadable_products.title') }}
            </span>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.before') !!}

        <div class="account-items-list">
            <div class="account-table-content">

                <datagrid-plus src="{{ route('shop.customer.downloadable_products.index') }}"></datagrid-plus>
                
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.after') !!}
    </div>
@endsection