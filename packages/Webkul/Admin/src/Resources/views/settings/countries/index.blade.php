@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.countries.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.countries.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.settings.countries.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            {!! $datagrid->render() !!}
        </div>
    </div>
@stop