@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.catalog.categories.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog.categories.create') }}" class="btn btn-lg btn-primary">
                    {{ __('Add Category') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('categories','Webkul\Admin\DataGrids\CategoryDataGrid')
            {!! $categories->render() !!}
        </div>
    </div>
@stop