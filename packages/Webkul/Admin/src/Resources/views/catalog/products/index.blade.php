@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.products.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.catalog.products.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.catalog.products.add-product-btn-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('product','Webkul\Admin\DataGrids\ProductDataGrid')
            {!! $product->render() !!}
        </div>
    </div>
@stop