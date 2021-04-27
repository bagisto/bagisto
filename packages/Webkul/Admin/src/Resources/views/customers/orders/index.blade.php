@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.orders.list', ['customer_name' => $customer->first_name . ' ' . $customer->last_name]) }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                    {{ __('admin::app.customers.orders.list', ['customer_name' => $customer->first_name . ' ' . $customer->last_name]) }}
                </h1>
            </div>
        </div>

        <div class="page-content">
            @inject('customerOrderGrid','Webkul\Admin\DataGrids\CustomerOrderDataGrid')

            {!! $customerOrderGrid->render() !!}
        </div>
    </div>

@stop
