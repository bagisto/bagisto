@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.addresses.title', ['customer_name' => $customer->first_name . ' ' . $customer->last_name]) }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.addresses.title', ['customer_name' => $customer->first_name . ' ' . $customer->last_name]) }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.customer.addresses.create', ['id' => $customer->id]) }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.customers.addresses.create-btn-title') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.customer.addresses.list.before') !!}

        <div class="page-content">

            {!! app('Webkul\Admin\DataGrids\AddressDataGrid')->render() !!}
            
        </div>

        {!! view_render_event('bagisto.admin.customer.addresses.list.after') !!}
    </div>
@stop
