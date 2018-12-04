@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.shipments.title') }}
@stop

@inject('orderShipmentsGrid', 'Webkul\Admin\DataGrids\OrderShipmentsDataGrid')

@section('content')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.sales.shipments.title') }}</h1>
            </div>

            <div class="page-action">
                <form method="POST" action="{{ route('admin.datagrid.export') }}">
                    @csrf()
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.customers.export') }}
                    </button>
                    <input type="hidden" name="gridData" value="{{serialize($orderShipmentsGrid)}}">
                </form>
            </div>
        </div>

        <div class="page-content">
            {!! $orderShipmentsGrid->render() !!}
        </div>
    </div>
@stop