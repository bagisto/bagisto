@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.locales.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.locales.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.settings.locales.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            {!! $datagrid->render() !!}
        </div>
    </div>
@stop