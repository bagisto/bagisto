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
                </div>
            </tab>
        {!! view_render_event('bagisto.admin.customer.edit.after', ['customer' => $customer]) !!}
        </tabs>
    </div>
@stop