@extends('admin::layouts.content')

@section('page_title')
    {{ __('velocity::app.admin.contents.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('velocity::app.admin.contents.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('velocity.admin.content.create') }}" class="btn btn-lg btn-primary">
                    {{ __('velocity::app.admin.contents.btn-add-content') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('velocity_contents', 'Webkul\Velocity\DataGrids\ContentDataGrid')
            {!! $velocity_contents->render() !!}
        </div>
    </div>
@stop