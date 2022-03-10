@extends('admin::layouts.content')

@section('page_title')
    {{ __('bookingproduct::app.admin.sales.bookings.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('bookingproduct::app.admin.sales.bookings.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.sales.bookings.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
