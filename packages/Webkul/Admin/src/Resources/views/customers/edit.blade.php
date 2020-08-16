@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.edit-title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ route('admin.dashboard.index') }}';"></i>
                    {{ $customer->first_name . " " . $customer->last_name }}
                </h1>
            </div>
        </div>

        <tabs>
        {!! view_render_event('bagisto.admin.customer.edit.before', ['customer' => $customer]) !!}
            <tab name="{{ __('admin::app.sales.orders.info') }}" :selected="true">
                <div class="sale-container">
                    @include('admin::customers.general')
                </div>
            </tab>

            <tab name="{{ __('admin::app.customers.customers.addresses') }}" :selected="false">
                <div class="style:overflow: auto;">&nbsp;</div>
                    {!! view_render_event('bagisto.admin.customer.addresses.list.before') !!}
                    {!! app('Webkul\Admin\DataGrids\AddressDataGrid')->render() !!}
                    {!! view_render_event('bagisto.admin.customer.addresses.list.after') !!}
            </tab>

            <tab name="{{ __('admin::app.layouts.orders') }}" :selected="false">
                <div class="style:overflow: auto;">&nbsp;</div>
                <div class="table">
                    {!! view_render_event('bagisto.admin.customer.orders.list.before') !!}
                    <table>
                        <thead>
                            <tr>
                                <th>{{ trans('admin::app.datagrid.order_number') }}</th>
                                <th>{{ trans('admin::app.datagrid.order-date') }}</th>
                                <th>{{ trans('admin::app.datagrid.channel-name') }}</th>
                                <th>{{ trans('admin::app.datagrid.grand-total') }}</th>
                                <th>{{ trans('admin::app.datagrid.status') }}</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->all_orders as $order)
                            <tr>
                                <td>{{ $order->increment_id }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>{{ $order->channel->name }}</td>
                                <td>{{ core()->formatBasePrice($order->grand_total) }}</td>
                                <td>
                                    @if($order->status == 'processing')
                                        <span class="badge badge-md badge-success">{{ trans('shop::app.customer.account.order.index.processing')}}</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge badge-md badge-success">{{trans('shop::app.customer.account.order.index.completed') }}</span>
                                    @elseif ($order->status == "canceled")
                                        <span class="badge badge-md badge-danger">{{trans('shop::app.customer.account.order.index.canceled') }}</span>
                                    @elseif ($order->status == "closed")
                                        <span class="badge badge-md badge-info">
                                            {{ trans('shop::app.customer.account.order.index.closed') }}
                                        </span>
                                    @elseif ($order->status == "pending")
                                        <span class="badge badge-md badge-warning">{{ trans('shop::app.customer.account.order.index.pending') }} </span>
                                    @elseif ($order->status == "pending_payment") {
                                        <span class="badge badge-md badge-warning">{{ trans('shop::app.customer.account.order.index.pending-payment') }} </span>';
                                    @elseif ($order->status == "fraud") {
                                        <span class="badge badge-md badge-danger">{{ trans('shop::app.customer.account.order.index.fraud') }}</span>
                                    @endif
                                </td>
                                <td><a href="{{ route('admin.sales.orders.view', $order->id) }}"><span class="icon eye-icon"></span></a></td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    {!! view_render_event('bagisto.admin.customer.orders.list.after') !!}
                </div>
            </tab>
            
        {!! view_render_event('bagisto.admin.customer.edit.after', ['customer' => $customer]) !!}
        </tabs>
    </div>
@stop