@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.title') }}
@stop

@inject('customer','Webkul\Admin\DataGrids\CustomerDataGrid')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.customers.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('admin.customer.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.customers.customers.add-title') }}
                </a>

                <form method="POST" action="{{ route('admin.datagrid.export') }}">
                    @csrf()
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.customers.export') }}
                    </button>
                    <input type="hidden" name="gridData" value="{{serialize($customer)}}">
                </form>
            </div>
        </div>


        <div class="page-content">
            {!! $customer->render() !!}
        </div>
    </div>

@stop

