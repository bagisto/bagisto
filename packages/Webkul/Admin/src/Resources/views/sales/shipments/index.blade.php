@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.shipments.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.sales.shipments.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">
            @inject('orderShipmentsGrid', 'Webkul\Admin\DataGrids\OrderShipmentsDataGrid')
            {!! $orderShipmentsGrid->render() !!}
        </div>
    </div>
@stop