@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.review.index.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-10">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>

        <span class="account-heading">
            {{ __('shop::app.customer.account.order.index.title') }}
        </span>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}
        <div class="input-group col-2">
            <input
                class="form-control py-2 border-right-0 border fs16i"
                type="search"
                placeholder="Search Here..." />

            <span class="input-group-append">
                <button class="btn border-left-0 border disable-box-shadow" type="button">
                    <i class="rango-search"></i>
                </button>
            </span>
        </div>

        <div class="account-items-list">
            <div class="account-table-content">

                {!! app('Webkul\Shop\DataGrids\OrderDataGrid')->render() !!}

            </div>
        </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
@endsection