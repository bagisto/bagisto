@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.edit-title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.customer.index') }}'"></i>
                    {{ $customer->first_name . " " . $customer->last_name }}
                </h1>
            </div>

            <div class="page-action"></div>
        </div>

        <tabs>
            {!! view_render_event('bagisto.admin.customer.edit.before', ['customer' => $customer]) !!}

                <tab name="{{ __('admin::app.sales.orders.info') }}" :selected="true">
                    <div class="sale-container">
                        @include('admin::customers.general')
                    </div>
                </tab>

                <tab name="{{ __('admin::app.customers.customers.addresses') }}" :selected="false">                
                    <div class="page-content">
                        <div class="page-content-button">
                            <a href="{{ route('admin.customer.addresses.create', ['id' => $customer->id]) }}" class="btn btn-lg btn-primary">
                                {{ __('admin::app.customers.addresses.create-btn-title') }}
                            </a>
                        </div>

                        <div class="page-content-datagrid">
                            <datagrid-plus src="{{ route('admin.customer.addresses.index', $customer->id) }}"></datagrid-plus>
                        </div>
                    </div>
                </tab>

                <tab name="{{ __('admin::app.layouts.invoices') }}" :selected="false">
                    <div class="page-content">

                    {!! view_render_event('bagisto.admin.customer.invoices.list.before') !!}

                        <datagrid-plus src="{{ route('admin.customer.invoices.data', $customer->id) }}"></datagrid-plus>

                    {!! view_render_event('bagisto.admin.customer.invoices.list.after') !!}
                    </div>
                </tab>

                <tab name="{{ __('admin::app.customers.orders.title') }}" :selected="false">
                    <div class="page-content">

                    {!! view_render_event('bagisto.admin.customer.orders.list.before') !!}

                        <datagrid-plus src="{{ route('admin.customer.orders.data', $customer->id) }}"></datagrid-plus>

                    {!! view_render_event('bagisto.admin.customer.orders.list.after') !!}
                    </div>
                </tab>

            {!! view_render_event('bagisto.admin.customer.edit.after', ['customer' => $customer]) !!}
        </tabs>
    </div>
@stop