@extends('velocity::admin.layouts.content')

@section('page_title')
    {{ __('velocity::app.admin.category.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('velocity::app.admin.category.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('velocity.admin.category.create') }}" class="btn btn-lg btn-primary">
                    {{ __('velocity::app.admin.category.btn-add-category') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('velocity_category', 'Webkul\Velocity\DataGrids\CategoryDataGrid')
            {!! $velocity_category->render() !!}
        </div>
    </div>
@stop