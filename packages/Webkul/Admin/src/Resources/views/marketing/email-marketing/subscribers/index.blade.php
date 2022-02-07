@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.subscribers.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.subscribers.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.customers.subscribers.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
