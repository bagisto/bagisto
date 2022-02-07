@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotions.catalog-rules.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.promotions.catalog-rules.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog-rules.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.promotions.catalog-rules.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.catalog-rules.index') }}"></datagrid-plus>
        </div>
    </div>
@endsection
