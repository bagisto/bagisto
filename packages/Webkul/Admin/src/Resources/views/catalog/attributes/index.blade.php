@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.attributes.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.catalog.attributes.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog.attributes.create') }}" class="btn btn-lg btn-primary">
                    {{ __('Add Attribute') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('attributes','Webkul\Admin\DataGrids\AttributeDataGrid')
            {!! $attributes->render() !!}
        </div>
    </div>
@stop