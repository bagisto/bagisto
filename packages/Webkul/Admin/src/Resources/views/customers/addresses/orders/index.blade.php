@extends('address::admin.layouts.content')

@section('page_title')
    {{ __('address::app.admin.addresses.title-orders', ['customer_name' => $customer->first_name . ' ' . $customer->last_name]) }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                    {{ __('address::app.admin.addresses.title-orders', ['customer_name' => $customer->first_name . ' ' . $customer->last_name]) }}
                </h1>
            </div>
        </div>

        <div class="page-content">

            <div class="tabs">
                <ul>
                    <li><a href="{{ route('admin.address.addresses.index', ['id' => $customer->id]) }}">{{ __('address::app.admin.addresses.address-list') }}</a></li>

                    <li class="active"><a href="{{ route('admin.address.orders.index', ['id' => $customer->id]) }}">{{ __('address::app.admin.addresses.order-list') }}</a></li>
                </ul>
            </div>

            {!! app('Webkul\Address\DataGrids\Admin\OrderDataGrid')->render() !!}

        </div>
    </div>

@stop
