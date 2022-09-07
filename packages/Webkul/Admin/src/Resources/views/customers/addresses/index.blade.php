@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.addresses.title', ['customer_name' => $customer->first_name . ' ' . $customer->last_name]) }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.customer.edit', ['id' => $customer->id]) }}'"></i>

                    {{ __('admin::app.customers.addresses.title', ['customer_name' => $customer->first_name . ' ' . $customer->last_name]) }}
                </h1>
            </div>

            <div class="page-action">
            @if (bouncer()->hasPermission('customers.addresses.create '))
                <a href="{{ route('admin.customer.addresses.create', ['id' => $customer->id]) }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.customers.addresses.create-btn-title') }}
                </a>
            @endif
            </div>
        </div>

        {!! view_render_event('bagisto.admin.customer.addresses.list.before') !!}

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.customer.addresses.index', $customer->id) }}"></datagrid-plus>
        </div>

        {!! view_render_event('bagisto.admin.customer.addresses.list.after') !!}
    </div>
@stop
